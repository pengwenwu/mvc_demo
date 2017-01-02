<?php

/**
 * 后台back的平台控制器
 */
class PlatformController extends BaseController
{
    //构造方法，实例化的时候调用
    public function __construct()
    {
        //构造方法重写时强制调用父类构造方法
        parent::__construct();

        //校验是否登录
        $this->_check();
    }

    /**
     * 校验是否登录
     */
    protected function _check()
    {
        //开启session
        @session_start();

        //判断当前请求是否是特例，是否需要校验
        //获取当前的控制器和动作
        $current_controller = CONTROLLER;
        $current_action = ACTION;
        //条件，当前控制器为Admin，并且动作为Index或者ChekcLogin时为特例，无需校验
        //显示验证码Captcha动作也是特例
        if ($current_controller == 'Admin' && ($current_action == 'Index' ||
                $current_action == 'CheckLogin' || $current_action =='Captcha'
            )
        ) {
            //无需校验，后续代码不需要执行
            return;
        }

        //校验是否具有登录标识，session
        if (!isset($_SESSION['admin_info'])) {
            //如果没有标识
            //判断是否记住登录状态
            //判断条件：admin_id存在&&admin_pass存在&&数据库验证通过
            $model = ModelFactory::M('AdminModel');
            if (isset($_COOKIE['admin_id']) && isset($_COOKIE['admin_pass'])
                && $admin_info = $model->CheckCookieInfo($_COOKIE['admin_id'], $_COOKIE['admin_pass'])
            ) {
                //具有记住登录状态，分配标识
                $_SESSION['admin_info'] = $admin_info;
            } else {
                //没有记住登录状态
                //没有登录标识，返回登录首页
                header("Location:index.php?p=back&c=Admin&a=Index");
                die();
            }
        }
    }
}

