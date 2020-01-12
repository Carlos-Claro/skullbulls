<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Imoveis_model_test extends TestCase
{
    public $model ;
    
    public function setUp()
    {
        $this->model = $this->newModel('imoveis_model');
    }

    public function test_get_itens()
    {
        $list = $this->model->get_itens();
        $this->assertArrayHasKey('itens',$list);
    }
    
    public function test_get_itens_false()
    {
        $list = $this->model->get_itens();
        $this->assertArrayHasKey('item',$list);
    }
    
}
