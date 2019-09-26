<?php

namespace Hetg\LunchGenerator\Model;

class UserModel extends Model
{
    public static $table = 'users';

    public static $primaryField = 'id';

    public static $fields = [
        'id' => 'integer',
        'username' => 'string',
        'password' => 'string',
    ];

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return UserModel
     */
    public function setId(int $id): UserModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return UserModel
     */
    public function setUsername(string $username): UserModel
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return UserModel
     */
    public function setPassword(string $password): UserModel
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }
}