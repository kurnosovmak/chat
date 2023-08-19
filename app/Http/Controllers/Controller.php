<?php

namespace App\Http\Controllers;

use App\Domain\Messenger\Chat\Adapters\ChatAdapter;
use App\Domain\Messenger\Core\Entities\ChatInfo;
use App\Domain\Messenger\Core\Entities\PeerId;
use App\Domain\Messenger\Core\Enums\PeerIdType;
use App\Http\Resources\Entities\ChatResource;
use App\Http\Resources\Entities\ProfileResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use RuntimeException;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected readonly ChatAdapter $chatAdapter;

    public function __construct()
    {
        [$chatAdapter, $error] = ChatAdapter::create();
        if ($error !== null) {
            throw new RuntimeException($error->message);
        }
        $this->chatAdapter = $chatAdapter;
    }

    /**
     * @param (PeerId|int)[] $peerIds
     * @return array<string, mixed>
     */
    protected function getConversations(array $peerIds): array
    {
        $chatIds = [];
        $userIds = [];
        foreach ($peerIds as $peerId) {
            if (!$peerId instanceof PeerId) {
                $peerId = PeerId::create((int)$peerId);
            }
            switch ($peerId->getType()) {
                case (PeerIdType::ChatType):
                    $chatIds[$peerId->getId()] = true;
                    break;
                default:
                    // Todo error
                    break;
            }
        }

        $chatIds = array_keys($chatIds);
        /** @var ChatInfo[] $chatInfos */
        $chatInfos = [];
        if (count($chatIds) !== 0) {
            $chatInfos = $this->chatAdapter->findChatsByChatIds($chatIds);
            //Добавляем пользователей из чата в зависимоти
            /** @var ChatInfo $chatInfo */
            foreach ($chatInfos as $chatInfo) {
                $userIds[$chatInfo->getFirstUserId()->getPeerId()->getId()] = true;
                $userIds[$chatInfo->getSecondUserId()->getPeerId()->getId()] = true;
            }
        }

        $userIds = array_keys($userIds);
        // Это костыль
        $userInfos = [];
        $users = collect();
        if (count($chatIds) !== 0) {
            $users = User::findMany($userIds);
        }


        return [
            'users' => ProfileResource::collection($users)->keyBy(fn(ProfileResource $resource) => $resource->getGlobalUserId()),
            'chats' => ChatResource::create($chatInfos)->toArray(),
            'groups' => [],
            'channels' => [],
        ];
    }
}
