<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Exceptions\Handler;

$app = new Application(
    dirname(__DIR__)
);

$app->singleton(Kernel::class, ConsoleKernel::class);
$app->singleton(ExceptionHandler::class, \Illuminate\Foundation\Exceptions\Handler::class);
$app->singleton(\Illuminate\Contracts\Http\Kernel::class, \App\Http\Kernel::class);

$app->bootstrapWith([
    Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
    Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    Illuminate\Foundation\Bootstrap\RegisterFacades::class,
    Illuminate\Foundation\Bootstrap\SetRequestForConsole::class,
    Illuminate\Foundation\Bootstrap\RegisterProviders::class,
    Illuminate\Foundation\Bootstrap\BootProviders::class,
]);

require __DIR__.'/../routes/web.php';

return $app;
