<?php

namespace Hetg\Framework\Controller;

use Hetg\Framework\Driver\MySQLDriver;
use Hetg\Framework\Model\UserModel;

class AdminController
{
    /**
     * @var MySQLDriver
     */
    private $userDriver;

    public function __construct()
    {
        $this->userDriver = new MySQLDriver('mysql:host=localhost;dbname=lunch_generator', 'hetg', 'root', [], UserModel::class);
    }

    public function indexAction($a)
    {

        $users = $this->userDriver->find(['username' => 'admin']);


        return is_array($users) ? $users[0]->getUsername() : $a;
    }

}