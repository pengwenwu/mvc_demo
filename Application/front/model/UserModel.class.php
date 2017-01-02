<?php

class UserModel extends BaseModel
{
    public function GetAllUser()
    {
        $sql = "select * from user_info_zuoye10";
        $result = $this->db->GetRows($sql);
        return $result;
    }

    public function GetCount()
    {
        $sql = "select count(*) from user_info_zuoye10";
        $result = $this->db->GetOneData($sql);
        return $result;
    }

    public function DelUserById($id)
    {
        $sql = "delete from user_info_zuoye10 WHERE user_id=$id";
        return $this->db->exc($sql);
    }

    public function AddNewUser($name, $pwd, $age, $education, $aihao, $origin)
    {
        $sql = "INSERT INTO user_info_zuoye10 (user_name, user_pwd, user_age,
          user_edu, user_hob, user_ori, user_reg) VALUES ('$name',md5('$pwd'),
            $age,'$education','$aihao','$origin',now())";
        return $this->db->exc($sql);
    }

    public function UserDetailById($id)
    {
        $sql = "select * from user_info_zuoye10 WHERE user_id=$id";
        $data = $this->db->GetOneRow($sql);
        return $data;
    }

    public function UpdateUserById($id, $name, $pwd, $age, $education, $aihao, $origin)
    {
        $sql = "update user_info_zuoye10 set user_name='$name'";
        $sql .= ",user_age='$age'";
        if (!empty($_POST['pwd'])) {
            $sql .= ",user_pwd=md5('$pwd')";
        }
        $sql .= ",user_edu='$education'";
        $sql .= ",user_hob='$aihao'";
        $sql .= ",user_ori='$origin'";
        $sql .= " where user_id='$id'";
        return $this->db->exc($sql);
    }
}