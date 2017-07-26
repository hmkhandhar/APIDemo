<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email_id
 * @property string $mobile_number
 * @property string $password
 * @property string $dob
 * @property string $address
 * @property string $image
 * @property string $user_type
 * @property integer $i_date
 * @property integer $u_date
 * @property integer $is_active
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $old_password;
    public $PasswordConfirm;
    public $new_password;
    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'mobile', 'address', 'user_type', 'i_date', 'u_date', 'is_active'], 'required'],
            [['dob'], 'safe'],
            [['i_date', 'u_date', 'is_active'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 30],
            [['mobile'], 'string', 'max' => 13],
            [['password'], 'safe'],
            [['address', 'image'], 'string', 'max' => 250],
            [['user_type'], 'string', 'max' => 2],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Full Name',
            'email' => 'Email ID',
            'mobile' => 'Mobile Number',
            'password' => 'Password',
            'dob' => 'Dob',
            'address' => 'Address',
            'image' => 'Image',
            'user_type' => 'User Type',
            'i_date' => 'I Date',
            'u_date' => 'U Date',
            'is_active' => 'Is Active',
        ];
    }
}
