<?php

namespace AddOn\Authorization\Storage;

class Pdo extends \OAuth2\Storage\Pdo
{
    protected function checkPassword($user, $password)
    {
        return password_verify($password, $user['password']);
    }

    public function getUser($username)
    {
        $stmt = $this->db->prepare($sql = sprintf('SELECT * from %s where username=:username', $this->config['user_table']));
        $stmt->execute(array('username' => $username));

        if (!$userInfo = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return false;
        }

        return $userInfo;
    }
}