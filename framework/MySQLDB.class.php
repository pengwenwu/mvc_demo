<?php

class MySQLDB
{
    private $link = null;
    private $host;
    private $port;
    private $user;
    private $pwd;
    private $charset;
    private $dbname;

    //用于存储唯一的单例对象
    private static $instance = null;

    static function GetInstance($config)
    {
        if (!(self::$instance instanceof self)) {  //还没有对象
            self::$instance = new self($config);  //创建并保存起来
        }
        return self::$instance;  //如果有对象
    }

    //单例化
    private function __construct($config)
    {
        $this->host = !empty($config['host']) ? $config['host'] : 'localhost';
        $this->port = !empty($config['port']) ? $config['port'] : 3306;
        $this->user = !empty($config['user']) ? $config['user'] : 'root';
        $this->pwd = !empty($config['pwd']) ? $config['pwd'] : 123;
        $this->charset = !empty($config['charset']) ? $config['charset'] : 'utf8';
        $this->dbname = !empty($config['dbname']) ? $config['dbname'] : 'utf8';
        //连接数据库
        $this->link = @mysqli_connect("{$this->host}:$this->port",
            "$this->user", "$this->pwd", "$this->dbname") or die("连接失败");
        //设置编码
        $this->setcharset($this->charset);
    }

    function selectDB($dbname)
    {
        mysqli_query($this->link, "use $dbname");
    }

    function setcharset($charset)
    {
        mysqli_query($this->link, "set names $charset");
    }

    function closeDB()
    {
        mysqli_close($this->link);
    }

    //执行一条增删改语句，返回真假结果
    function exc($sql)
    {
        return $this->query($sql);
    }

    //执行一条查询语句，返回一条结果集(一行多列)
    function GetOneRow($sql)
    {
        $result = $this->query($sql);
        //语句执行成功，处理结果，返回结果集
        $rec = mysqli_fetch_assoc($result);
        mysqli_free_result($result); //提前释放资源(销毁结果集)，否则需要等到页面结束才自动销毁
        return $rec;
    }

    //执行一条查询语句，返回多条结果集(多行多列)
    function GetRows($sql)
    {
        $result = $this->query($sql);
        //语句执行成功，处理结果，返回结果集
        $arr = array();
        while ($rec = mysqli_fetch_assoc($result)) {
            $arr[] = $rec;      //此时$arr就是二维数组了
        }
        mysqli_free_result($result); //提前释放资源(销毁结果集)，否则需要等到页面结束才自动销毁
        return $arr;
    }

    //这个方法为了执行一条返回一个数据的语句，它可以返回一个直接值(一行一列)
    //sql语句常常类似于这样：select name from Users where id = 1;
    //或：select count(*) as c from Users;
    function GetOneData($sql)
    {
        $result = $this->query($sql);
        //语句执行成功，处理结果，返回一个数据
        $rec = mysqli_fetch_row($result); //这里需要使用fetch_row()这个函数(数字索引取值);
        mysqli_free_result($result); //提前释放资源(销毁结果集)，否则需要等到页面结束才自动销毁
        return $rec[0];
    }

    //用于执行sql语句，并进行错误处理，或返回执行结果
    private function query($sql)
    {
        $result = mysqli_query($this->link, $sql);
        if ($result === false) {
            //语句执行失败，处理结果
            echo "<br>sql语句执行失败，请参考如下信息：";
            echo "<br>错误代码：" . mysqli_errno($this->link); //获取错误信息代号
            echo "<br>错误信息：" . mysqli_error($this->link); //获取错误提示信息内容
            echo "<br>错误语句：" . $sql;
            die();
        }
        return $result;
    }
}

