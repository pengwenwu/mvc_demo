<?php

/**
 * 上传工具类
 */
class Upload
{
    private $_error;  //存储当前错误信息

    /**
     * 获取错误信息
     * @return string 返回错误信息
     */
    public function getError()
    {
        return $this->_error;
    }

    private $_upload_path;  //上传目录
    private $_prefix;       //前缀
    private $_max_size;     //最大size
    private $_ext_list;     //允许的后缀列表
    private $_mime_list;    //允许的mime列表

    public function setUploadPath($upload_path)
    {
        //不允许使用不存在的目录作为上传目录
        if (is_dir($upload_path)) {
            $this->_upload_path = $upload_path;
        } else {
            trigger_error('上传目录设置失败，使用默认');
        }
    }

    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
    }

    public function setMaxSize($max_size)
    {
        $this->_max_size = $max_size;
    }

    public function setExtList($ext_list)
    {
        $this->_ext_list = $ext_list;
        $this->_setMimeList($ext_list);
    }

    public function __construct()
    {
        //为选项设置默认值
        $this->_upload_path = './';
        $this->_prefix = '';
        $this->_max_size = 1 * 1024 * 1024; //1M
        $this->_ext_list = array('.jpg', '.png', '.gif', '.jpeg');
        $this->_setMimeList($this->_ext_list);
    }

    private $_ext_mime = array(
        '.jpg' => 'image/jpeg',
        '.jpeg' => 'image/jpeg',
        '.png' => 'image/png',
        '.gif' => 'image/gif'
    );

    /**
     *将mime列表和允许的后缀列表一一映射
     */
    private function _setMimeList($ext_list)
    {
        //遍历获得每一个后缀名
        foreach ($ext_list as $ext) {
            //利用当前后缀名，获取对应的mime,存入Mime列表中
            $_mime_list[] = $this->_ext_mime[$ext];
        }
        //赋值到MIME列表属性上
        $this->_mime_list = $_mime_list;
    }


    /**
     * 单文件上传
     * @param array $file_info 某个临时上传文件的5个信息，由$_FILES获得
     * @return mixed string:成功:目标文件名；失败：false
     */
    public function UploadFile($file_info)
    {
        //先判断是否有错误
        if ($file_info['error'] != 0) {
            $this->_error = '上传文件存在错误';
            return false;
        }

        //判断文件类型
        //后缀名判断
        $ext_list = $this->_ext_list; //允许的后缀名列表
        $ext = strrchr($file_info['name'], '.'); //获取后缀名
        if (!in_array($ext, $ext_list)) {
            //如果后缀名不在该列表中
            $this->_error = '类型：后缀不合法';
            return false;
        }

        //MIME判断类型
        $mime_list = $this->_mime_list;//允许的MIME列表
        if (!in_array($file_info['type'], $mime_list)) {
            $this->_error = '类型：MIME不合法';
            return false;
        }

        //PHP检测MIME
        //实例化一个可以获得文件MIME类型的对象
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        //利用对象获得某个文件的MIME类型
        $mime_type = $finfo->file($file_info['tmp_name']);
        if (!in_array($mime_type, $mime_list)) {
            $this->_error = '类型：PHP检测MIME类型不合法';
            return false;
        }

        //判断大小
        $max_size = $this->_max_size; //允许的最大尺寸
        if ($file_info['size'] > $max_size) {
            $this->_error = '类型：文件过大';
            return false;
        }

        //设置目标文件地址
        //上传目录
        $upload_path = $this->_upload_path;
        //采取子目录存储
        //获取当前需要的子目录名(目录/天)
        $sub_dir = date('Ymd') . '/'; //当前
        //判断当前目录是否存在
        if (!is_dir($upload_path . $sub_dir)) {
            //不存在，创建
            mkdir($upload_path . $sub_dir);
        }

        //目标文件名
        $prefix = $this->_prefix; //前缀
        $dst_name = uniqid($prefix, true) . $ext; //加强唯一性

        //检测是否为HTTP上传的文件
        //判断临时文件，是否为真实的上传文件
        //在文件移动之前进行判断
        if (!is_uploaded_file($file_info['tmp_name'])) {
            $this->_error = '不是HTTP临时上传的文件';
            return false;
        }

        //移动
        if (move_uploaded_file($file_info['tmp_name'], $upload_path . $sub_dir . $dst_name)) {
            //移动成功，返回上传目录后的地址
            return $sub_dir . $dst_name;
        } else {
            $this->_error = '移动失败';
            return false;
        }
    }

    /**
     * 多文件上传
     * @param array $file_list 上传文件信息列表(二维数组)
     * @return mixed array:成功：返回目标文件地址数组;bool:失败：返回false
     */
    public function UploadMulti($file_list)
    {
        //遍历文件信息列表，获取索引信息
        foreach ($file_list['name'] as $key => $value) {
            $file_info['name'] = $file_list['name'][$key];
            $file_info['type'] = $file_list['type'][$key];
            $file_info['tmp_name'] = $file_list['tmp_name'][$key];
            $file_info['error'] = $file_list['error'][$key];
            $file_info['size'] = $file_list['size'][$key];
            //上传文件
            //并存储每个文件的上传结果，与$key对应
            $result_list[$key] = $this->UploadFile($file_info);
        }
        return $result_list;
    }
}

