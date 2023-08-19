<?php

namespace App\Domain\Messenger\Core\Entities;

use Carbon\Carbon;

class ChatInfo
{
    private ?ChatId $chatId;

    private ?MessageId $lastMessageId;

    private ?UserId $firstUserId;
    private ?UserId $secondUserId;

    private ?Carbon $createdAt;
    private ?Carbon $updatedAt;

    public static function create(): self
    {
        return new self();
    }

    private function __construct()
    {
    }

    /**
     * @return ?ChatId
     */
    public function getChatId(): ?ChatId
    {
        return $this->chatId;
    }

    /**
     * @param ChatId $chatId
     */
    public function setChatId(ChatId $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * @return UserId|null
     */
    public function getFirstUserId(): ?UserId
    {
        return $this->firstUserId;
    }

    /**
     * @param UserId $firstUserId
     */
    public function setFirstUserId(UserId $firstUserId): self
    {
        $this->firstUserId = $firstUserId;
        return $this;
    }

    /**
     * @return UserId|null
     */
    public function getSecondUserId(): ?UserId
    {
        return $this->secondUserId;
    }

    /**
     * @param UserId $secondUserId
     */
    public function setSecondUserId(UserId $secondUserId): self
    {
        $this->secondUserId = $secondUserId;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $createdAt
     */
    public function setCreatedAt(Carbon $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @param Carbon $updatedAt
     */
    public function setUpdatedAt(Carbon $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return MessageId|null
     */
    public function getLastMessageId(): ?MessageId
    {
        return $this->lastMessageId;
    }

    /**
     * @param MessageId $lastMessageId
     */
    public function setLastMessageId(MessageId $lastMessageId): self
    {
        $this->lastMessageId = $lastMessageId;
        return $this;
    }

}
