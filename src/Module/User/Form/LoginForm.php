<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use App\Module\User\Repository\ModuleSettingsRepository;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\ValidatorFactoryInterface;
use Yiisoft\Validator\Rule\Required;

use function strtolower;

final class LoginForm extends FormModel
{
    private string $login = '';
    private string $password = '';
    private ModuleSettingsRepository $settings;

    public function __construct(ModuleSettingsRepository $settings, ValidatorFactoryInterface $validator)
    {
        $this->settings = $settings;

        parent::__construct($validator);
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Login',
            'password' => 'Password'
        ];
    }

    public function formName(): string
    {
        return 'Login';
    }

    public function getLogin(): string
    {
        if (!$this->settings->getUserNameCaseSensitive()) {
            $this->login = strtolower($this->login);
        }

        return $this->login;
    }

    public function rules(): array
    {
        return [
            'login' => [new Required()],
            'password' => [new Required()]
        ];
    }
}
