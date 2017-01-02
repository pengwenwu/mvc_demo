<?php

/**
 * 对商品进行操作的控制类
 */
class GoodsController extends PlatformController
{
    /**
     * 展示添加表单页面功能
     */
    public function AddAction()
    {
        require VIEW_PATH . 'goods_add.html';
    }

    /**
     * 添加商品信息
     */
    public function InsertAction()
    {
        //获取表单信息
        
        $data['goods_name'] = $_POST['goods_name'];
        $data['shop_price'] = $_POST['shop_price'];
        $data['goods_desc'] = $_POST['goods_desc'];
        $data['goods_number'] = $_POST['goods_number'];
        $data['is_best'] = isset($_POST['is_best']) ? '1' : '0';
        $data['is_new'] = isset($_POST['is_new']) ? '1' : '0';
        $data['is_hot'] = isset($_POST['is_hot']) ? '1' : '0';
        $data['is_on_sale'] = isset($_POST['is_on_sale']) ? '1' : '0';
        //获取上传文件信息
        //处理上传图片
        $t_upload = new Upload($_FILES['goods_image_ori']);
        //设置上传目录
        $t_upload->setUploadPath(ROOT . 'upload/goods/');
        //设置前缀
        $t_upload->setPrefix('goods_ori_');
        //多文件上传
        if ($result = $t_upload->UploadMulti($_FILES['goods_image_ori'])) {
            //上传成功，返回目标文件地址
            //对数组结果进行处理
            $result_list = implode(',', $result);
            $data['goods_image_ori'] = $result_list;
        } else {
            //上传失败
            $this->GoToUrl('文件上传失败，原因是：' . $t_upload->getError(),
                'index.php?p=back&c=Goods&a=Add');
            die();
        }
        //添加商品信息的管理员
        @session_start();
        $data['admin_id'] = $_SESSION['admin_info']['id'];

        $data['edit_time'] = time();

        $model = ModelFactory::M('GoodsModel');
        $result = $model->InsertGoods($data);
        if ($result) {
            //插入成功
            header("Location:index.php?p=back&c=Goods&a=List");
            die();
        } else {
            //插入失败
            $this->GoToUrl("失败，失败原因", 'index.php?p=back&c=Goods&a=Add', 10);
            die();
        }
    }

    /**
     * 商品列表
     */
    public function ListAction()
    {
        echo "商品信息列表";
    }
}

