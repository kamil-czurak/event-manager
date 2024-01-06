<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class PasswordChangeForm extends Model
{
    public $password;
    public $repeatPassword;
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'repeatPassword'], 'required'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Hasła muszą być takie same.'],
        ];
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function change()
    {
        if ($this->validate()) {
           $user = $this->getUser();
           $user->setPassword($this->password);
           $user->save(false);
            $this->getUser()->setPassword($this->password);
            return true;
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        }

        return $this->_user;
    }
}
