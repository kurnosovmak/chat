<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Message\Adapters;

use App\Core\Logger\Logger;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Core\Entities\MessageInfo;
use App\Domain\Messenger\Core\Errors\ErrorData;
use App\Domain\Messenger\Message\DTO\MessageServiceQueriesEngine;
use App\Domain\Messenger\Message\Repositories\MessageMysqlRepositories;
use App\Domain\Messenger\Message\Services\MessageService;
use \RuntimeException;

final class MessageAdapter
{
    /**
     * @return array [?MessageAdapter, ?ErrorData]
     */
    public static function create(): array
    {
        $repository = MessageMysqlRepositories::create();

        [$messageService, $error] = MessageService::create($repository);
        if ($error !== null) {
            return [null, $error];
        }

        $messageServiceQueriesEngine = MessageServiceQueriesEngine::create();

        $logger = Logger::createWithLaravelLogger();

        return [new self($messageService, $messageServiceQueriesEngine, $logger), null];
    }

    private function __construct(
        private readonly MessageService              $messageService,
        private readonly MessageServiceQueriesEngine $messageServiceQueriesEngine,
        private readonly Logger                      $logger,
    )
    {
    }

    /**
     * @param int $chatId
     * @param int $startMessageId
     * @param int $per
     * @return MessageInfo[]
     */
    public function getHistory(int $chatId, int $startMessageId, int $per): array
    {
        [$getHistoryQuery, $error] = $this->messageServiceQueriesEngine->generateGetHistoryQuery($chatId, $startMessageId, $per);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        [$chatId, $error] = $this->messageService->getHistory($getHistoryQuery);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        return $chatId;
    }

    /**
     * @param int $chatId
     * @param int $userId
     * @param string $body
     * @return MessageId|null [, ?ErrorData]
     */
    public function sendMessage(int $chatId, int $userId, string $body): ?MessageId
    {
        [$sendMessageQuery, $error] = $this->messageServiceQueriesEngine->generateSendMessageQuery($chatId, $userId, $body);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }
        [$messageId, $error] = $this->messageService->sendMessage($sendMessageQuery);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        return $messageId;
    }

}
