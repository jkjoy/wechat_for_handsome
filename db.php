<?php
include('config.php');

class Db
{
    private $db;

    function __construct()
    {
        global $sqlite_conf;
        $this->db = new PDO('sqlite:' . __DIR__ . '/' . $sqlite_conf['db']);
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }
}