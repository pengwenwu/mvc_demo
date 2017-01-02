<?php

class ProductModel extends BaseModel{
    public function ShowAllProduct(){
        $sql = "select pro_id,pro_name,protype_name,price,pinpai,chandi from ";
        $sql .="product INNER JOIN product_type AS t ON product.protype_id =t.protype_id";
        $result = $this->db->GetRows($sql);
        return $result;
    }
    public function ProductCount(){
        $sql = "select count(*) from product";
        return $this->db->GetOneData($sql);
    }
    public function DelOneProduct($id){
        $sql = "delete from product WHERE pro_id=$id";
        return $this->db->exc($sql);
    }
}