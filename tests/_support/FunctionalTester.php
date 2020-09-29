<?php
namespace App\Tests;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * Define custom actions here
     */
    public function confirmedUser(): void
    {
        $this->haveInDatabase(
            'user',
            [
                'id' => 1,
                'username'        => 'admin',
                'email'           => 'administrator@example.com',
                'password_hash'   => '$argon2i$v=19$m=65536,t=4,p=1$ZVlUZk1NS2wwdi45d0t6dw$pn/0BLB3EzYtNdm3NSj6Yntk9lUT1pEOFsd85xV3Ig4',
                'auth_key'        => 'pjvxHoTbCuJ4JH9puYqJbgMibqqWpNTg',
                'registration_ip' => '127.0.0.1',
                'created_at'      => 1564590107,
                'updated_at'      => 1564590107,
                'confirmed_at'    => 1564590137
            ]
        );
    }

    public function unconfirmedUser(): void
    {
        $time = time();

        $this->haveInDatabase(
            'user',
            [
                'id' => 3,
                'username'      => 'joe',
                'email'         => 'joe@example.com',
                'password_hash' => '$argon2i$v=19$m=65536,t=4,p=1$ZVlUZk1NS2wwdi45d0t6dw$pn/0BLB3EzYtNdm3NSj6Yntk9lUT1pEOFsd85xV3Ig4',
                'auth_key'      => 'mhh1A6KfqQLmHP-MiWN0WB0M90Q2u5OE',
                'registration_ip' => '127.0.0.1',
                'created_at'    => $time,
                'updated_at'    => $time
            ]
        );

        $this->haveInDatabase(
            'token',
            [
                'user_id'    => 3,
                'code'       => 'NO2aCmBIjFQX624xmAc3VBu7Th3NJoa6',
                'type'       => 0,
                'created_at' => $time
            ]
        );
    }

    public function unconfirmedTokenExpiredUser(): void
    {
        $time = time();

        $this->haveInDatabase(
            'user',
            [
                'id' => 4,
                'username'      => 'john',
                'email'         => 'john@example.com',
                'password_hash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
                'auth_key'      => 'h6OS9csJbZEOW59ZILmJxU6bCiqVno9A',
                'created_at'    => $time - 90000,
                'updated_at'    => $time - 90000
            ]
        );

        $this->haveInDatabase(
            'token',
            [
                'user_id'    => 4,
                'code'       => 'qxYa315rqRgCOjYGk82GFHMEAV3T82AX',
                'type'       => 0,
                'created_at' => $time - 90000,
            ]
        );
    }

    public function recoveryTokenExpiredUser(): void
    {
        $time = time();

        $this->haveInDatabase(
            'user',
            [
                'id' => 6,
                'username'      => 'andrew',
                'email'         => 'andrew@example.com',
                'password_hash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
                'auth_key'      => 'qxYa315rqRgCOjYGk82GFHMEAV3T82AX',
                'created_at'    => $time - 90000,
                'updated_at'    => $time - 90000,
                'confirmed_at'  => $time - 90000
            ]
        );

        $this->haveInDatabase(
            'token',
            [
                'user_id'    => 6,
                'code'       => 'a5839d0e73b9c525942c2f59e88c1aaf',
                'type'       => 1,
                'created_at' => $time - 90000
            ]
        );
    }

    public function recoveryResetUser(): void
    {
        $time = time();

        $this->haveInDatabase(
            'user',
            [
                'id' => 7,
                'username'      => 'alex',
                'email'         => 'alex@example.com',
                'password_hash' => '$argon2i$v=19$m=65536,t=4,p=1$ZVlUZk1NS2wwdi45d0t6dw$pn/0BLB3EzYtNdm3NSj6Yntk9lUT1pEOFsd85xV3Ig4',
                'auth_key'      => 'zQh1A65We0AmHPOMiWN0WB0M90Q24ziU',
                'created_at'    => $time,
                'updated_at'    => $time,
                'confirmed_at'  => $time
            ]
        );

        $this->haveInDatabase(
            'token',
            [
                'user_id'    => 7,
                'code'       => '6f5d0dad53ef73e6ba6f01a441c0e602',
                'type'       => 1,
                'created_at' => $time
            ]
        );
    }
}
