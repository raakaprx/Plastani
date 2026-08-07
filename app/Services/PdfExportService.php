<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfExportService
{
    /**
     * Export data to HTML file (as PDF alternative)
     * Generates HTML report file that can be printed to PDF
     */
    public function exportToHtml(string $filename, string $title, array $columns, array $data, string $reportType = 'table'): string
    {
        $html = $this->generateHtml($title, $columns, $data, $reportType);

        $filePath = 'exports/' . date('Y/m/d') . '/' . $filename . '.html';
        Storage::disk('public')->put($filePath, $html);

        return $filePath;
    }

    /**
     * Export data to CSV file
     */
    public function exportToCsv(string $filename, array $columns, array $data): string
    {
        $csv = $this->generateCsv($columns, $data);

        $filePath = 'exports/' . date('Y/m/d') . '/' . $filename . '.csv';
        Storage::disk('public')->put($filePath, $csv);

        return $filePath;
    }

    /**
     * Generate HTML content for report
     */
    private function generateHtml(string $title, array $columns, array $data, string $reportType): string
    {
        $currentDate = now()->format('d F Y H:i:s');
        $columnHeaders = implode('</th><th>', $columns);

        $rows = '';
        foreach ($data as $item) {
            $cells = '';
            foreach (array_keys($columns) as $key) {
                $value = $item[$key] ?? '-';
                // Format nilai untuk display
                if (is_numeric($value) && $key !== 'id' && $key !== 'user_id' && $key !== 'product_id') {
                    $value = number_format($value, 2, ',', '.');
                }
                $cells .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $rows .= '<tr>' . $cells . '</tr>';
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .meta {
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1a252f;
        }
        td {
            padding: 12px;
            border: 1px solid #ddd;
            background-color: #fafafa;
        }
        tr:nth-child(even) td {
            background-color: #f0f0f0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
            text-align: center;
        }
        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 $title</h1>
            <div class="meta">
                <p>Generated on: $currentDate</p>
                <p>Report Type: $reportType</p>
            </div>
        </div>

        <table>
            <thead>
                <tr><th>$columnHeaders</th></tr>
            </thead>
            <tbody>
                $rows
            </tbody>
        </table>

        <div class="footer">
            <p>© 2026 Plastani System | Laporan ini dibuat secara otomatis dan berlaku sah sesuai dengan data sistem.</p>
            <p>Untuk versi PDF, silakan gunakan fitur Print → Save as PDF dari browser Anda.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate CSV content
     */
    private function generateCsv(array $columns, array $data): string
    {
        $csv = "data:text/csv;charset=utf-8,\xEF\xBB\xBF";

        // Add headers
        $headers = array_values($columns);
        $csv .= implode(',', array_map(fn($h) => '"' . str_replace('"', '""', $h) . '"', $headers)) . "\n";

        // Add data rows
        foreach ($data as $row) {
            $values = array_map(function($value) {
                if (is_numeric($value)) {
                    $value = str_replace('.', ',', (string)$value);
                }
                return '"' . str_replace('"', '""', (string)$value) . '"';
            }, array_values($row));
            $csv .= implode(',', $values) . "\n";
        }

        return $csv;
    }

    /**
     * Get file download URL
     */
    public function getDownloadUrl(string $filePath): string
    {
        return asset('storage/' . $filePath);
    }
}
