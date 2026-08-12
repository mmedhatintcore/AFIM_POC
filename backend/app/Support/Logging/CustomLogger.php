<?php

namespace App\Support\Logging;

use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomLogger
{
    public function error(string $context, Throwable $exception): void
    {
        Log::error($context.': '.$exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
    }
}
