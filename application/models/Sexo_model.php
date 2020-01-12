<?php
class Sexo_model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
//        $this->database = array('db' => 'guiasjp', 'table' => 'sexo');
    }
	
    public function get_select_site( $id_empresa, $filtro = array(), $coluna = 'grupo.id', $ordem = 'ASC' )
    {
        
        $retorno = array(
            (object)array('id' => 'Feminino','descricao' => 'Feminino'),
            (object)array('id' => 'Masculino','descricao' => 'Masculino'),
        );
        
    	return $retorno;
    }
    
}