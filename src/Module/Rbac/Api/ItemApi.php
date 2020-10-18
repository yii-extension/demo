<?php

declare(strict_types=1);

namespace App\Module\Rbac\Api;

use App\Module\Rbac\ActiveRecord\ItemAR;
use App\Module\Rbac\Repository\ItemRepository;

final class ItemApi
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function all(): array
    {
        $rows = $this->itemRepository->findItemAllAsArray();

        foreach ($rows as $row) {
            $items[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'type' => $row['type'] === 'role' ? 'Role' : 'Permission',
                'created_at' => date('Y-m-d', (int) $row['created_at']),
                'updated_at' => date('Y-m-d', (int) $row['updated_at'])
            ];
        }

        return $items ?? [];
    }
}
