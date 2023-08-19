<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Chat\Adapters;

use App\Domain\Messenger\Chat\DTO\ChatServiceQueriesEngine;
use App\Domain\Messenger\Chat\Repositories\ChatMysqlRepositories;
use App\Domain\Messenger\Chat\Services\ChatService;
use App\Domain\Messenger\Core\Entities\ChatId;
use App\Core\Logger\Logger;
use App\Domain\Messenger\Core\Entities\ChatInfo;
use \RuntimeException;

final class ChatAdapter
{
    /**
     * @return array [?ChatAdapter, ?ErrorData]
     */
    public static function create(): array
    {
        [$chatRepository, $error] = ChatMysqlRepositories::create();
        if ($error !== null) {
            return [null, $error];
        }
        [$chatService, $error] = ChatService::create($chatRepository);
        if ($error !== null) {
            return [null, $error];
        }

        [$chatServiceQueriesEngine, $error] = ChatServiceQueriesEngine::create();
        if ($error !== null) {
            return [null, $error];
        }

        $logger = Logger::createWithLaravelLogger();

        return [new self($chatService, $chatServiceQueriesEngine, $logger), null];
    }

    private function __construct(
        private readonly ChatService              $chatService,
        private readonly ChatServiceQueriesEngine $chatServiceQueriesEngine,
        private readonly Logger                   $logger,
    )
    {
    }

    public function createChat(int $firstUserId, int $secondUserId): ChatId
    {
        [$createChatQuery, $error] = $this->chatServiceQueriesEngine->generateCreateChatQuery($firstUserId, $secondUserId);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }
        [$chatId, $error] = $this->chatService->createChat($createChatQuery);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        return $chatId;
    }

    public function findChatByChatId(int $chatId): ?ChatInfo
    {
        [$findChatByChatIdQuery, $error] = $this->chatServiceQueriesEngine->generateFindChatByChatIdQuery($chatId);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }
        [$chatInfo, $error] = $this->chatService->findChatByChatId($findChatByChatIdQuery);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        return $chatInfo;
    }

    /**
     * @param array<int> $chatIds
     * @return array<int, ChatInfo>
     */
    public function findChatsByChatIds(array $chatIds): array
    {
        [$findChatsByChatIdsQuery, $error] = $this->chatServiceQueriesEngine->generateFindChatsByChatIdsQuery($chatIds);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }
        [$chatInfo, $error] = $this->chatService->findChatsByChatIds($findChatsByChatIdsQuery);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        return $chatInfo;
    }

    public function findChatByUserIds(int $firstUserId, int $secondUserId): ?ChatId
    {
        [$findChatsByUserIdsQuery, $error] = $this->chatServiceQueriesEngine->generateFindChatsByUserIdsQuery($firstUserId, $secondUserId);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }
        [$chatId, $error] = $this->chatService->findChatByUserIds($findChatsByUserIdsQuery);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        return $chatId;
    }

    /**
     * @param int $userId
     * @return array<ChatId>
     */
    public function findChatsByUserId(int $userId): array
    {
        [$findChatsByUserIdQuery, $error] = $this->chatServiceQueriesEngine->generateFindChatsByUserIdQuery($userId);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }
        [$chatIds, $error] = $this->chatService->findChatsByUserId($findChatsByUserIdQuery);
        if ($error !== null) {
            $this->logger->logErrorData($error);
            throw new RuntimeException($error->message);
        }

        return $chatIds;
    }

}
