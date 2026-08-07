<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CriticalErrorNotification;
use Exception;

class ErrorNotificationService
{
    /**
     * Notify about critical error
     * Logs to file dan mengirim email notifikasi
     */
    public function notifyError(Exception $exception, string $context = 'Application Error', array $data = []): void
    {
        $errorId = \Illuminate\Support\Str::uuid();
        $timestamp = now()->format('Y-m-d H:i:s');

        // Log ke file
        Log::error("CRITICAL_ERROR [ID: $errorId] - $context", [
            'exception' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context_data' => $data,
            'timestamp' => $timestamp,
            'user_agent' => request()->userAgent() ?? 'Unknown',
            'ip_address' => request()->ip() ?? 'Unknown',
        ]);

        // Kirim email notifikasi jika configured
        if (config('mail.from.address')) {
            try {
                Mail::send(new CriticalErrorNotification(
                    errorId: $errorId,
                    context: $context,
                    exception: $exception,
                    data: $data,
                    timestamp: $timestamp
                ));
            } catch (Exception $e) {
                Log::error('Failed to send critical error notification', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Log ke database (optional monitoring)
        $this->logToDatabase([
            'error_id' => $errorId,
            'context' => $context,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'timestamp' => $timestamp,
        ]);
    }

    /**
     * Log error to database for monitoring dashboard
     */
    private function logToDatabase(array $data): void
    {
        try {
            // Simpan ke error log table (jika ada)
            // ErrorLog::create($data);
        } catch (Exception $e) {
            Log::error('Failed to log error to database', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get error statistics
     */
    public function getErrorStats(int $daysBack = 7): array
    {
        $errorLog = storage_path('logs/laravel.log');

        if (!file_exists($errorLog)) {
            return ['total_errors' => 0, 'recent_errors' => []];
        }

        $lines = file($errorLog);
        $errors = array_filter($lines, fn($line) => str_contains($line, 'CRITICAL_ERROR'));

        $recentErrors = array_slice($errors, -20);

        return [
            'total_errors' => count($errors),
            'recent_errors' => $recentErrors,
            'last_error_time' => !empty($errors) ? 'Recently' : 'None',
        ];
    }
}
