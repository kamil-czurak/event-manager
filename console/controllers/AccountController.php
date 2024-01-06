<?php

namespace console\controllers;

use common\models\User;
use yii\base\Exception;
use yii\console\Exception as ConsoleException;
use yii\console\ExitCode;
use yii\helpers\Json;
use yii;


/**
 * User account commands.
 */
class AccountController extends BaseConsoleController
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $roleName = 'root';

    /**
     * Add user account.
     * Usage example 1: `./yii account/create john john@example.com`.
     * Usage example 2: `./yii account/create -u john -r admin`.
     *
     * @param string|null $username
     * @param string|null $email
     * @param string|null $roleName
     *
     * @return int
     *
     * @throws \Throwable
     * @throws yii\base\ExitException
     */
    public function actionCreate(string $username = null, string $email = null, $roleName = 'root')
    {
        $auth = Yii::$app->authManager;

        $username = $this->username ?? $username;
        $email = $this->email ?? $email;
        $roleName = $this->roleName ?? $roleName;

        try {
            $userData = $this->createUserData($username, $email);

            $userSearchData = array_filter([
                'username' => $userData['username'],
                'email' => $userData['email'],
            ]);

            if ($user = User::find()->where($userSearchData)->one()) {
                $this->echoLine("Account {$userData['username']} already added.");
            } else {
                $user = new User($userData);
            }
            $user->status = User::STATUS_ACTIVE;
            if ($user->save()) {
                $this->echoLine("Account activated.");
                // force to use password_hash is override in User model on AfterSave event
                $user->password_hash = $userData['password_hash'];
                if ($user->update(false, ['password_hash']) !== false) {
                    $this->echoLine("Account password reset.");
                }

                $this->echoSuccess(Yii::t('msg', 'All done.'));

                return ExitCode::OK;

            } else {
                throw new ConsoleException(Json::encode($user->getErrors()));
            }
        } catch (Exception $e) {
            $this->echoError($e->getMessage());

            return ExitCode::UNSPECIFIED_ERROR;
        }
    }


    protected function createUserData(string $username = null, string $email = null): array
    {
        $user = new User();
        $user->email = $email;
        $user->username = $username;
        if ($user->validate(['username', 'email']) === false) {
            throw new ConsoleException(Json::encode($user->getErrors()));
        }
        $password = $this->prompt('Podaj hasło:');
        $user->password = $password;
        if ($user->validate(['password']) === false) {
            throw new ConsoleException($user->getFirstError('password'));
        }
        $userData = [
            'username' => $username,
            'email' => $email,
            'password_hash' => Yii::$app->security->generatePasswordHash($password),
        ];

        return $userData;
    }

    protected function optionsCreate()
    {
        return ['username', 'email', 'roleName'];
    }

    protected function optionAliasesCreate()
    {
        return array_merge(parent::optionAliases(), ['u' => 'username', 'e' => 'email', 'r' => 'roleName']);
    }
}
