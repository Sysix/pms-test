<?php

namespace AddOn\Authorization\Model;

use PmsOne\Sql\DataObject;
use Spot\EntityInterface;
use Spot\MapperInterface;


class User extends DataObject
{
    protected static $table = 'oauth_users';

    const SALT_LENGTH = 22;

    public function createUser(array $data)
    {
        $salt = $this->createSalt();
        $data['password'] = $this->hashPassword($data['password'], $salt);
        $data['salt'] = $salt;

        return $this->getMapper()->create($data);
    }

    protected function createSalt()
    {
        $allowed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $length = strlen($allowed) - 1;
        $str = '';
        for ($i = 0; $i < self::SALT_LENGTH; $i++) {
            $str .= $allowed[rand(0, $length)];
        }

        return $str;
    }

    protected function hashPassword($password, $salt)
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'salt' => $salt
        ]);
    }

    public static function fields()
    {
        return [
            'user_id' => [
                'type' => 'integer',
                'length' => 80,
                'primary' => true,
                'autoincrement' => true
            ],
            'username' => [
                'type' => 'string'
            ],
            'password' => [
                'type' => 'string',
                'length' => 255
            ],
            'email' => [
                'type' => 'string',
                'required' => true
            ],
            'first_name' => [
                'type' => 'string',
            ],
            'last_name' => [
                'type' => 'string'
            ],
            'salt' => [
                'type' => 'string',
                'required' => true
            ]
        ];
    }
}