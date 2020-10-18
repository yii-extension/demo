<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\ActiveRecord\TokenAR;
use App\Service\Mailer;
use RuntimeException;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Security\Random;

final class TokenRepository
{
    private Aliases $aliases;
    private ConnectionInterface $db;
    private Mailer $mailer;
    private TokenAR $token;
    private ?ActiveQuery $tokenQuery = null;
    private UrlGeneratorInterface $url;

    public function __construct(
        Aliases $aliases,
        ConnectionInterface $db,
        Mailer $mailer,
        TokenAR $token,
        UrlGeneratorInterface $url
    ) {
        $this->aliases = $aliases;
        $this->db = $db;
        $this->mailer = $mailer;
        $this->token = $token;
        $this->url = $url;
        $this->tokenQuery();
    }

    public function findTokenByCondition(array $condition): ?ActiveRecordInterface
    {
        return $this->tokenQuery()->findOne($condition);
    }

    public function findTokenById(int $id): ?ActiveRecordInterface
    {
        return $this->findTokenByCondition(['user_id' => $id]);
    }

    public function findTokenByParams(int $id, string $code, int $type): ?ActiveRecordInterface
    {
        return $this->findTokenByCondition(['user_id' => $id, 'code' => $code, 'type' => $type]);
    }

    public function register(int $id, int $token): bool
    {
        $result = false;

        if ($this->token->getIsNewRecord() === false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        $transaction = $this->db->beginTransaction();

        try {
            $this->token->setAttribute('user_id', $id);
            $this->token->setAttribute('type', $token);
            $this->token->setAttribute('created_at', time());
            $this->token->setAttribute('code', Random::string());

            if (!$this->token->save()) {
                $transaction->rollBack();
            } else {
                $this->token->deleteAll(
                    [
                        'user_id' => $this->token->getAttribute('user_id'),
                        'type' => $this->token->getAttribute('type')
                    ]
                );

                $transaction->commit();

                $result = true;
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $result;
    }

    public function sendMailer(
        int $id,
        string $email,
        string $username,
        string $subjectMessage,
        array $template
    ): bool {
        /** @var TokenAR $query */
        $query = $this->findTokenById($id);

        return $this->mailer->run(
            $email,
            $subjectMessage,
            $this->aliases->get('@user/resources/mail'),
            $template,
            [
                'username' => $username,
                'url' => $this->url->generateAbsolute(
                    $query->toUrl(),
                    ['id' => $query->getAttribute('user_id'), 'code' => $query->getAttribute('code')]
                )
            ]
        );
    }

    private function tokenQuery(): ActiveQueryInterface
    {
        if ($this->tokenQuery === null) {
            $this->tokenQuery = new ActiveQuery(TokenAR::class, $this->db);
        }

        return $this->tokenQuery;
    }
}
