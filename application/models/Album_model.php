<?php
class Album_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'album');
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
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'album'),
                                );
    							
    	$data['filtro'] = 'album.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_filtro( $filtro = array() )
    {
    	$data['coluna'] = 'album.*';
    	$data['tabela'] = array(
                                array('nome' => 'album'),
                                array('nome' => 'empresas', 'where' => 'album.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_com_fotos( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'album'),
                                );
    							
    	$data['filtro'] = 'album.id = '.$id;
    	$resultado = $this->get_itens_($data);
        $retorno = array();
        if(isset($resultado['itens'][0]))
        {
            $dados = $resultado['itens'][0];
            $this->load->model('album_fotos_model');
            $fotos = $this->album_fotos_model->get_itens('id_album = '.$id,'ordem','asc');
            $retorno = $dados;
            $retorno->fotos = $fotos['itens'];
        }
    	return $retorno;
    }
    
    
    public function get_select( $filtro = array(),$coluna = 'album.nome', $ordem = 'ASC')
    {
        $data['coluna'] = 'album.id as id, ';
            $data['coluna'] .= ' album.nome as descricao,';
    	//$data['coluna'] = 'bairros.id as id, CONCAT(bairros.nome, " - ", cidades.nome, "/", cidades.uf) as descricao, bairros.link as link ';
    	$data['tabela'] = array(
                                array('nome' => 'album'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(album.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'album'),
                                array('nome' => 'empresas', 'where' => 'album.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'album.id';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'album.id', $ordem = 'DESC', $off_set = NULL, $qtde_itens = N_ITENS )
    {
    	$data['coluna'] = '	
                                album.* ';
    	$data['tabela'] = array(
                                array('nome' => 'album'),
                                array('nome' => 'empresas', 'where' => 'album.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde_itens;
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}
