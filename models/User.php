<?php

namespace app\models;
use app\models\Users;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $id;
    public $name;
    public $image;
    public $email;    
    public $password;    
    public $user_type;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }
    public static function findIdentity($id)
    {
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
        
        $User = Users::find()->where(["id" => $id])->one();
        if (!count($User))
        {
            return null;
        }
        else
        {
            $dbUser = [
                'id' => $User->id,
                // 'user_type'=>explode(',',$User->user_type),
                'user_type'=>$User->user_type,
                'name'=>$User->name,
                'email'=>$User->email,
                'password' => $User->password,
                'image' => $User->image,
                // 'image' => $User->profile_image,
                // 'cover_image' => $User->cover_image,
                // 'base_role' => $User->base_role,
                //'authKey' => "test".$User->id."key",
                //'accessToken' => "fashionapp".$User->id,
            ];
            return new static($dbUser);
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $type = ['A','U'];
        // $type = ['U'];
        $User = Users::find()->where(["email" => $username,'user_type'=>$type])->one();
        if (!count($User))
        {
            return null;
        }
        else
        {
            $dbUser = [
                'id' => $User->id,
                // 'user_type'=>explode(',',$User->user_type),
                'user_type'=>$User->user_type,
                'name'=>$User->name,
                'email'=>$User->email,
                'password' => $User->password,
                'image' => $User->image,
                // 'is_active'=>$User->is_active,
            ];
            return new static($dbUser);
        }
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
}
