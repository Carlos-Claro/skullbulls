<?php
class Login_Model extends MY_Model {

    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct(array('skullbulls'));		
    }
    
    public function verifica($email, $senha, $id_empresa = FALSE)
    {
        $data['coluna'] = 'id as user, nome as nome, ativo as ativo';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
        $filtro[] = array( 'campo' => 'email', 'valor' => $email, 'tipo' => 'where' );
        $filtro[] = array( 'campo' => 'senha', 'valor' => md5($senha), 'tipo' => 'where' );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
        return (isset($retorno['itens'][0])) ? $retorno['itens'][0] : FALSE;
    }
    
    public function esqueceu_senha( $email )
    { 
        $data['coluna'] = 'id as id, nome as nome, ativo as ativo, email as email';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
        $filtro[] = array( 'campo' => 'email', 'valor' => $email, 'tipo' => 'where' );
        $filtro[] = array( 'campo' => 'ativo', 'valor' => 1, 'tipo' => 'where' );
        //$filtro = array( 'email' => $email );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
        return (isset($retorno['itens'][0])) ? $retorno['itens'][0] : FALSE;
    }
}