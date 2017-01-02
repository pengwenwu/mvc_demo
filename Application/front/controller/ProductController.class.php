<?php

class ProductController extends BaseController
{
    public function IndexAction()
    {
        $obj = ModelFactory::M('ProductModel');
        $data1 = $obj->ShowAllProduct();
        $data2 = $obj->ProductCount();
        include VIEW_PATH . "ShowAllProduct.html";
    }

    public function DelAction()
    {
        $id = $_GET['id'];
        $obj = ModelFactory::M('ProductModel');
        $obj->DelOneProduct($id);
        $this->GoToUrl("删除成功！", '?c=Product', 3);
    }
}
