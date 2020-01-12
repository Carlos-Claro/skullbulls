<?php
class Album_fotos_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'album_fotos');
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
    	$data['coluna'] = 'album_fotos.*';
    	$data['tabela'] = array(
                                array('nome' => 'album_fotos'),
                                array('nome' => 'album', 'where' => 'album.id = album_fotos.id_album', 'tipo' => 'INNER' ),
                                array('nome' => 'empresas', 'where' => 'empresas.id = album_fotos.id_empresa', 'tipo' => 'INNER' ),
                                );
    							
    	$data['filtro'] = 'album_fotos.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    
    public function get_select( $filtro = array(),$coluna = 'album_fotos.nome', $ordem = 'ASC')
    {
        $data['coluna'] = 'album_fotos.id as id, ';
            $data['coluna'] .= ' album_fotos.nome as descricao,';
    	//$data['coluna'] = 'bairros.id as id, CONCAT(bairros.nome, " - ", cidades.nome, "/", cidades.uf) as descricao, bairros.link as link ';
    	$data['tabela'] = array(
                                array('nome' => 'album_fotos'),
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
                            count(album_fotos.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'album_fotos'),
                                array('nome' => 'album', 'where' => 'album.id = album_fotos.id_album', 'tipo' => 'INNER' ),
                                array('nome' => 'empresas', 'where' => 'empresas.id = album_fotos.id_empresa', 'tipo' => 'INNER' ),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'album_fotos.id';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'album_fotos.id', $ordem = 'DESC', $off_set = NULL, $qtde_itens = 12 )
    {
    	$data['coluna'] = '	
                                album_fotos.* ';
    	$data['tabela'] = array(
                                array('nome' => 'album_fotos'),
                                array('nome' => 'album', 'where' => 'album.id = album_fotos.id_album', 'tipo' => 'INNER' ),
                                array('nome' => 'empresas', 'where' => 'empresas.id = album_fotos.id_empresa', 'tipo' => 'INNER' ),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    		$data['qtde_itens'] = $qtde_itens;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}
