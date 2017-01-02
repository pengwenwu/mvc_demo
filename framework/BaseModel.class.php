<?php

class BaseModel{
    protected $db = null; //用于存储数据库工具类的实例（对象）
    function __construct()
    {
        $config = array(
            'host' => "localhost",
            'port' => 3306,
            'user' => "root",
            'pass' => "123",
            'charset' => "utf8",
            'dbname' => "demo"
        );
        $this->db = MySQLDB::GetInstance($config); //单例化
    }
}
