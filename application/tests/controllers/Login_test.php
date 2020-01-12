<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Login_test extends TestCase
{
    public function test_index()
    {
        $output = $this->request('GET', '/');
        $this->assertContains('Preencha seus dados de acesso.', $output);
    }
    
    public function test_login()
    {
        $response = $this->request('POST', '/login/login/', array('email' => 'programacao@pow.com.br', 'senha' => '9873214') );
        $this->assertTrue(FALSE);
//        $output = $this->request('GET', '/');
//        $this->assertContains('Preencha seus dados de acesso.', $output);
    }
}
