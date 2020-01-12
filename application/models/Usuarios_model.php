<?php
class Usuarios_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'skullbulls', 'table' => 'usuarios');
    }
	
    public function adicionar( $data = array() )
    {
        return $this->adicionar_($this->database, $data);
    }
    
    public function editar($data = array(),$filtro = array())
    {
        return $this->editar_($this->database, $data, $filtro);
    }
    
    public function excluir($filtro)
    {
        return $this->excluir_($this->database, $filtro);
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = 'usuarios.*';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
    	$data['filtro'] = 'usuarios.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    
    public function get_item_filtro($filtro= array())
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function altera_senha( $id_usuario, $senha )
    {
    	$where = array('id' => $id_usuario);
    	$valor = array('senha' => $senha);
    	$table = 'usuarios';
    	$up = $this->editar($valor, $where);
    	return $up; 
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'usuarios.id as id, usuarios.nome as descricao, usuarios.email as email ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['ordem'] = 'ASC';
    	$data['col'] = 'usuarios.nome';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            usuarios.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            usuarios.id as id,
                            usuarios.nome as nome,
                            usuarios.email as email,
                            IF ( usuarios.ativo = 0, "<center><i class=\'fa fa-close font-red\'></i></center>", "<center><i class=\'fa fa-check font-green-jungle\'></i></center>" )as ativo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}