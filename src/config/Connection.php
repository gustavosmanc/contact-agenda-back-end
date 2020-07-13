<?php

class Connection
{
    protected $pdo;

    function  __construct()
    {
        define('DB_PATH', '../database/contacts.db');
        $this->openConn();
    }

    protected function openConn()
    {
        $this->pdo = new PDO('sqlite:' . DB_PATH);
    }

    public function getConn()
    {
        return $this->pdo;
    }

    public function closeConn()
    {
        $this->pdo->close();
    }
}
