<?php

namespace Hetg\LunchGenerator\Controller;

use Hetg\LunchGenerator\Driver\MySQLDriver;
use Hetg\LunchGenerator\Model\UserModel;

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

    public function indexAction()
    {

        $users = $this->userDriver->find(['username' => 'admin']);


        return is_array($users) ? $users[0]->getUsername() : '';
    }

}