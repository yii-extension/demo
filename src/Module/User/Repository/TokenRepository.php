<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\Entity\Token;
use App\Service\Mailer;
use App\Service\Parameters;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Security\Random;

final class TokenRepository
{
    private Aliases $aliases;
    private Parameters $app;
    private Mailer $mailer;
    private Token $token;
    private UrlGeneratorInterface $url;

    public function __construct(
        Aliases $aliases,
        Parameters $app,
        Mailer $mailer,
        Token $token,
        UrlGeneratorInterface $url
    ) {
        $this->aliases = $aliases;
        $this->app = $app;
        $this->mailer = $mailer;
        $this->token = $token;
        $this->url = $url;
    }

    public function findTokenById(int $id): ?ActiveRecordInterface
    {
        return $this->token->findOne(['user_id' => $id]);
    }

    public function findTokenByParams(int $id, string $code, int $type): ?ActiveRecordInterface
    {
        return $this->token->findOne(['user_id' => $id, 'code' => $code, 'type' => $type]);
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

    public function sendEmail(
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
            $this->app->get($subjectMessage),
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
}
