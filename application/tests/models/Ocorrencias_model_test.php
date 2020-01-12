<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Ocorrencias_model_test extends TestCase
{
    public $model ;
    
    public function setUp()
    {
        $this->model = $this->newModel('ocorrencias_model');
    }

    public function test_get_itens()
    {
        $list = $this->model->get_itens();
        $this->assertContainsOnly('int',$list);
    }
    
    public function test_get_itens_false()
    {
        $list = $this->model->get_itens();
        $this->assertArrayHasKey('item',$list);
    }
    
}
