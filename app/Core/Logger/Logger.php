<?php

namespace App\Core\Logger;

use App\Domain\Messenger\Core\Errors\ErrorData;
use Log;
use Psr\Log\LoggerInterface;

final class Logger
{

    public static function createWithLaravelLogger(): Logger {
        return new self(Log::getLogger());
    }

    private function __construct(private readonly LoggerInterface $logger)
    {
    }


    public function log(string $errorMessage, mixed $data = null): void {
        $this->logger->log(1, $errorMessage, $data);
    }

    public function logErrorData(ErrorData $errorData): void {
        $this->log($errorData->message, $errorData->data);
    }
}
