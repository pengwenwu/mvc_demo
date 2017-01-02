<?php

class ModelFactory
{
    static $all_model = array(); //用于存储各个模型类的唯一实例（单例）

    static function M($model_name)
    {
        if (!isset(static::$all_model[$model_name]) || //如果不存在
            !(static::$all_model[$model_name] instanceof $model_name)
        )   //或不是其实例
        {
            static::$all_model[$model_name] = new $model_name();
        }
        return static::$all_model[$model_name];
    }
}