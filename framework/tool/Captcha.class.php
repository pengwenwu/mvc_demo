<?php

/**
 * 验证码工具类
 */

class Captcha
{
    /**
     * @param string $value 输入的验证码的值
     * @return bool true/false
     */
    public function Checkcode($value){
        @session_start();

        //存在且相等，忽略大小写
        $result = isset($value) && isset($_SESSION['code']) &&
            strtoupper($value) == strtoupper($_SESSION['code']);

        //销毁已经使用过的验证码
        unset($_SESSION['code']);
        return $result;
    }
    /**
     * @param int $code_len 码值长度
     */
    public function Makeimage($code_len = 4)
    {
        //处理码值
        $char_list = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789'; //o跟0易混淆，排除
        $char_list_len = strlen($char_list); //字符串长度
        $code = ''; //初始化码值字符串
        for ($i = 1; $i <= $code_len; ++$i) {
            //随机下标
            $rand_index = mt_rand(0, $char_list_len-1);
            //字符串支持下标操作
            $code .= $char_list[$rand_index];
        }
        //存储在session中
        @session_start();
        $_SESSION['code'] = $code;

        //处理验证码图片
        //创建画布
        $bg_file = FRAMEWORK . 'tool/captcha/captcha_bg' . mt_rand(1,5) . '.jpg';
        $image = imagecreatefromjpeg($bg_file);

        //操作画布
        //给画布分配一个颜色
        //随机分配，字体为白色或者黑色
        if (mt_rand(1, 2) == 1) {
            $str_color = imagecolorallocate($image, 255, 255, 255); //白色
        } else {
            $str_color = imagecolorallocate($image, 0, 0, 0); //黑色
        }

        //将字符串显示在图片上
        //让验证码居中显示

        $image_w = imagesx($image);   //计算图片宽
        $image_h = imagesy($image);   //计算图片高
        $font = 5;                    //字体大小
        $font_w = imagefontwidth($font);//字体宽
        $font_h = imagefontheight($font);//字体宽

        //位置
        $x = ($image_w - $font_w * 4) / 2;
        $y = ($image_h - $font_h) / 2;

        imagestring($image, $font, $x, $y, $code, $str_color);

        //直接输出图片
        //告诉浏览器输出的是一个图片
        header("Content-Type:image/jpeg");
        imagejpeg($image);

        //销毁资源
        imagedestroy($image);
    }
}
