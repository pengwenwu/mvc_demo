<?php
/*
 * 请求分发器
 */
//确定当前平台
$p = !empty($_GET['p']) ? $_GET['p'] : "front";
define("PLAT", $p);

//确定当前控制器
$c = !empty($_GET['c']) ? $_GET['c'] : "User";
define('CONTROLLER', $c);

//确定当前动作
$a = !empty($_GET['a']) ? $_GET['a'] : "Index";
define('ACTION', $a);

define("DS", DIRECTORY_SEPARATOR);  //目录分隔符
define("ROOT", __DIR__ . DS);    //当前mvc框架所在的根目录
define("APP", ROOT . 'Application' . DS); //application的完整路径
define("FRAMEWORK", ROOT . DS . 'framework' . DS); //框架基础类所在的路径
define("PLAT_PATH", APP . PLAT . DS); //当前平台所在的目录
define("CTRL_PATH", PLAT_PATH . 'controller' . DS); //当前控制器所在的目录
define("MODEL_PATH", PLAT_PATH . 'model' . DS); //当前模型类所在的目录
define("VIEW_PATH", PLAT_PATH . 'view' . DS); //当前视图所在的目录

/**
 * 处理自动加载函数，将所有的框架基础类与类文件地址，做一个映射对应
 * @param string $class 需要调用的工具类类名
 */
function __autoload($class)
{
    $base_class['BaseController'] = FRAMEWORK . 'BaseController.class.php';
    $base_class['BaseModel'] = FRAMEWORK . 'BaseModel.class.php';
    $base_class['ModelFactory'] = FRAMEWORK . 'ModelFactory.class.php';
    $base_class['MySQLDB'] = FRAMEWORK . 'MySQLDB.class.php';
    $base_class['Captcha'] = FRAMEWORK . 'tool/Captcha.class.php';
    $base_class['Upload'] = FRAMEWORK . 'tool/Upload.class.php';
    //判断，如果设置了该类，则调用
    if (isset($base_class[$class])) { //加载基础类
        require $base_class[$class];
    } elseif (substr($class, -5) == 'Model') { //截取最后5个字符串，即调用Model类
        require MODEL_PATH . $class . '.class.php';
    } elseif (substr($class, -10) == 'Controller') { //调用Controller类
        require CTRL_PATH . $class . '.class.php';
    }
}

$controller_name = $c . "Controller"; //构建控制器的类名
$ctrl = new $controller_name(); //可变类
$action = $a . "Action";
$ctrl->$action(); //可变函数

