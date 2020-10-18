<?php

declare(strict_types=1);

namespace App\Module\User\Api;

use App\Module\User\Repository\UserRepository;

final class UserApi
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(): ?array
    {
        $items = [];
        $rows = $this->userRepository->findUserAllAsArray();

        foreach ($rows as $row) {
            $items[] = [
                'id' => $row['id'],
                'username' => $row['username'],
                'email' => $row['email'],
                'registration_ip' => $row['registration_ip'],
                'created_at' => date('Y-m-d', (int) $row['created_at']),
                'last_login_at' => $this->lastLogin($row['last_login_at']),
                'confirm' => $row['confirmed_at'],
                'blocked' => $row['blocked_at']
            ];
        }

        return $items;
    }

    private function lastLogin(?string $field): string
    {
        return $field === null ? 'never' : date('Y-m-d', (int) $field);
    }
}
