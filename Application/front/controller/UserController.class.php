<?php

class UserController extends BaseController
{
    public function IndexAction()
    {
        $obj = ModelFactory::M('UserModel');
        $data1 = $obj->GetAllUser();
        $data2 = $obj->GetCount();
        include VIEW_PATH . "showAllUser.html";
    }

    public function DelAction()
    {
        $id = $_GET['id'];
        $obj = ModelFactory::M('UserModel');
        $obj->DelUserById($id);
        $this->GoToUrl("删除成功！", '?c=User', 3);
    }

    public function AddFormAction()
    {
        include "./Application/front/view/AddForm_view.html";
    }

    public function AddNewUserAction()
    {
        $obj = ModelFactory::M('UserModel');
        $name = $_POST["name"];
        $pwd = $_POST["pwd"];
        $age = $_POST["age"];
        $education = $_POST["education"];
        $hobby = $_POST["hobby"];
        $aihao = array_sum($hobby); //处理多选框数据
        $origin = $_POST["origin"];
        $obj->AddNewUser($name, $pwd, $age, $education, $aihao, $origin);
        $this->GoToUrl("添加用户成功！", '?c=User', 3);
    }

    public function DetailAction()
    {
        $id = $_GET['id'];
        $obj = ModelFactory::M('UserModel');
        $result = $obj->UserDetailById($id);
        echo "<h3>查询成功</h3>";
        echo "<a href='?'>返回</a><br>";
        include VIEW_PATH . "UserDetail.html";
    }

    public function EditFormAction()
    {
        $id = $_GET['id'];
        $obj = ModelFactory::M('UserModel');
        $result = $obj->UserDetailById($id);
        $hob = $result['user_hob'];
        $aihao = explode(",", $hob);
        include VIEW_PATH . "EditForm_view.html";
    }

    public function UpdateUserAction()
    {
        $id = $_POST['id'];
        $name = $_POST["name"];
        $pwd = $_POST["pwd"];
        $age = $_POST["age"];
        $education = $_POST["education"];
        $hobby = $_POST["hobby"];
        $aihao = array_sum($hobby); //处理多选框数据
        $origin = $_POST["origin"];
        $obj = ModelFactory::M('UserModel');
        $obj->UpdateUserById($id, $name, $pwd, $age, $education, $aihao, $origin);
        $this->GoToUrl("修改成功！", '?c=User', 3);
    }
}





