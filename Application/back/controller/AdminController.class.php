<?php

class AdminController extends PlatformController
{
    /**
     * 首页
     */
    
    public function IndexAction()
    {
        include VIEW_PATH . "login.html";
    }

    /**
     * 显示验证码动作
     */
    public function CaptchaAction()
    {
        //通过验证码工具类直接完成
        $t_captcha = new Captcha();
        $t_captcha->Makeimage();
    }

    /**
     * 验证登录动作
     */
    public function CheckLoginAction()
    {
        //在验证登录之前，先判断验证码是否正确
        $t_captcha = new Captcha();
        if (!$t_captcha->Checkcode($_POST['captcha'])) {
            //验证码错误，提示，跳转到登录首页
            $this->GoToUrl('验证码错误！', 'index.php?p=back&c=Admin&a=Index', 3);
        }

        //验证码正确，则验证身份信息
        $username = $_POST['username'];
        $pass = $_POST['password'];
        $model = ModelFactory::M('AdminModel');
        $admin_info = $model->CheckAdminInfo($username, $pass);
        //非空数组自动转化为bool类型
        if ($admin_info) {
            @session_start();
            $_SESSION['admin_info'] = $admin_info;

            //记住登录状态
            if (isset($_POST['remember'])) {
                //需要记录，通常是在原始数据上，添加混淆字符串（盐值）后，再加密
                setcookie('admin_id', md5($admin_info['id'] . 'SALT'), time() + 3600 * 24 * 7);//七天后过期
                setcookie('admin_pass', md5($admin_info['admin_pass'] . 'SALT'), time() + 3600 * 24 * 7);//七天后过期
            }

            //跳转到后台首页
            header("Location:?p=back&c=Manage&a=Index");
        } else {
            $this->GoToUrl("登录失败！", "?p=back&c=Admin&a=Index");
        }
    }

}