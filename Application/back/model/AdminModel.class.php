<?php

class AdminModel extends BaseModel
{
    public function CheckAdminInfo($username, $pass)
    {
        $sql = "select * from admin_user WHERE admin_user='$username' 
                  AND admin_pass=md5('$pass')";
        return $this->db->GetOneRow($sql);
    }

    /**
     * 校验通过加密后的id和密码是否合法
     * @param string $id 加密（加盐）
     * @param string $pass 加密（加盐）
     * @return mixed  验证通过返回一行结果集；验证失败，返回false
     */
    public function CheckCookieInfo($id, $pass)
    {
        $sql = "SELECT * FROM admin_user WHERE md5(concat(id,'SALT'))='$id'
                  AND md5(concat(admin_pass,'SALT'))='$pass'";
        return $this->db->GetOneRow($sql);
    }

}