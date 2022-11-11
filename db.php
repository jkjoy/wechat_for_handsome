<?php
include('config.php');
class Db
{
    private $db;

    function  __construct()
    {
        global $mysql_conf;
        $this->db = new PDO('mysql:host=' . $mysql_conf['host'] . ';dbname=' . $mysql_conf['db'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
    }
    public function query($sql)
    {
        return $this->db->query($sql);
    }
}
