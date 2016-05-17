<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 接口返回相应实体
 *
 * @author maozi
 */
namespace Home\Model;

Class ResponseEntity {

    public $code;  // 状态码
    public $isSuccess;  // 请求是否成功
    public $msg; // 提示信息
    public $data;  // 数据。null/对象/数组
    public $count;  // 总数。用于分页
    
    /**
     * 
     * @param type $code
     * @param type $isSuccess
     * @param type $msg
     * @param type $data
     */
    function setResponseEntity($code, $isSuccess, $msg, $data) {
        $this->code = $code;
        $this->isSuccess = $isSuccess;
        $this->msg = $msg;
        $this->data = $data;
        $this->count = 1;
    }
    
    function setResponseEntityWithCount($code, $isSuccess, $msg, $data, $count) {
        $this->code = $code;
        $this->isSuccess = $isSuccess;
        $this->msg = $msg;
        $this->data = $data;
        $this->count = $count;
    }
    
    function __get($propertyName) {
        if(isset($this->$propertyName)) {
            return ($this->$propertyName);
        } else {
            return (NULL);
        }
    }
    
    function __set($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }
    
}
