<?php
class GoodsModel extends BaseModel{
    /**
     * @param array $data 商品信息
     * @return bool 返回添加信息后的执行结果
     */
    public function InsertGoods($data){
        $sql="insert into goods VALUES (NULL ,'{$data['goods_name']}'
          ,'{$data['shop_price']}','{$data['goods_desc']}'
          ,'{$data['goods_number']}','{$data['is_best']}'
          ,'{$data['is_new']}','{$data['is_hot']}'
          ,'{$data['is_on_sale']}','{$data['goods_image_ori']}'
          ,'{$data['admin_id']}','{$data['edit_time']}'
          )";
        return $this->db->exc($sql);
    }
}

