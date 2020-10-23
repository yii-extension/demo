<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use App\Module\User\Repository\ModuleSettingsRepository;
use Yiisoft\Form\FormModel;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Validator\ValidatorFactoryInterface;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\MatchRegularExpression;

use function strtolower;

final class RegisterForm extends FormModel
{
    private string $email = '';
    private string $username = '';
    private string $password = '';
    private string $ip = '';
    private ModuleSettingsRepository $settings;
    private UrlMatcherInterface $urlMatcher;

    public function __construct(
        ModuleSettingsRepository $settings,
        UrlMatcherInterface $urlMatcher,
        ValidatorFactoryInterface $validator
    ) {
        $this->settings = $settings;
        $this->urlMatcher = $urlMatcher;

        parent::__construct($validator);
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'username' => 'Username',
            'password' => 'Password'
        ];
    }

    public function formName(): string
    {
        return 'Register';
    }

    public function getEmail(): string
    {
        return strtolower($this->email);
    }

    public function getUsername(): string
    {
        if ($this->settings->getUsernameCaseSensitive()) {
            $this->username = strtolower($this->username);
        }

        return $this->username;
    }

    public function ip(string $value): void
    {
        $this->ip = $value;
    }

    public function rules(): array
    {
        return [
            'email' => [
                new Required(),
                new Email()
            ],
            'username' => [
                new Required(),
                (new HasLength())->min(3)->max(255)->tooShortMessage('Username should contain at least 3 characters.'),
                new MatchRegularExpression($this->settings->getUsernameRegExp())
            ],
            'password' => $this->passwordRules()
        ];
    }

    private function passwordRules(): array
    {
        $route = $this->urlMatcher->getCurrentRoute()->getName();
        $result = [];

        if ($this->settings->isGeneratingPassword() === false &&  $route === 'registration/register') {
            $result = [
                new Required(),
                (new HasLength())->min(6)->max(72)->tooShortMessage('Password should contain at least 6 characters.')
            ];
        }

        return $result;
    }
}
