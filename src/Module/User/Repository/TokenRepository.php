<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\Entity\Token;
use App\Service\Mailer;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Security\Random;

final class TokenRepository
{
    private Aliases $aliases;
    private ConnectionInterface $db;
    private Mailer $mailer;
    private Token $token;
    private ?ActiveQueryInterface $tokenQuery = null;
    private UrlGeneratorInterface $url;

    public function __construct(
        Aliases $aliases,
        ConnectionInterface $db,
        Mailer $mailer,
        Token $token,
        UrlGeneratorInterface $url
    ) {
        $this->aliases = $aliases;
        $this->db = $db;
        $this->mailer = $mailer;
        $this->token = $token;
        $this->url = $url;
        $this->tokenQuery();
    }

    /**
     * @return array|bool
     *
     * @param (int|string)[] $condition
     */
    public function findTokenbyWhere(array $condition)
    {
        return $this->tokenQuery->where($condition)->one();
    }

    /**
     * @return array|bool
     */
    public function findTokenById(int $id)
    {
        return $this->findTokenByWhere(['user_id' => $id]);
    }

    /**
     * @return array|bool
     */
    public function findTokenByParams(int $id, string $code, int $type)
    {
        return $this->findTokenByWhere(['user_id' => $id, 'code' => $code, 'type' => $type]);
    }

    public function register(int $id, int $token): bool
    {
        $this->token->setAttribute('user_id', $id);
        $this->token->setAttribute('type', $token);

        $this->token->deleteAll(
            [
                'user_id' => $this->token->getAttribute('user_id'),
                'type' => $this->token->getAttribute('type')
            ]
        );

        $this->token->setAttribute('created_at', time());
        $this->token->setAttribute('code', Random::string());

        return $this->token->save();
    }

    public function sendMailer(
        int $id,
        string $email,
        string $username,
        string $subjectMessage,
        array $template
    ): bool {
        /** @var Token $query */
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
            $this->tokenQuery = new ActiveQuery(Token::class, $this->db);
        }

        return $this->tokenQuery;
    }
}
