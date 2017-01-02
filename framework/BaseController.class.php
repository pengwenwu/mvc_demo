<?php
class BaseController{
    function __construct()
    {
        header("content-type:text/html;charset=utf-8");
    }
    function GoToUrl($message,$url,$time=3){//显示一定的简短文字，然后自动跳转
        echo "<h3>$message</h3>";
        echo "<a href='$url'>返回</a><br>";
        echo "<br>页面将在{$time}秒后自动跳转！";
        header("refresh:$time,$url");//自动跳转
        die();  //停止脚本
    }
}