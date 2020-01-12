<?php
/**
 * Utilizado apenas para carregamento de bairros no site de cliente, em hotsitemanager e site_paginas
 */
class Bairro_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'bairros');
    }
	
    
    
    public function get_select_site( $id_empresa,$id_cidade = FALSE, $filtro = array(), $coluna = 'bairros.nome', $ordem = 'ASC' )
    {
        if ( $id_cidade )
        {
            $data['coluna'] = 'bairros.id as id, bairros.nome as descricao, bairros.link as link ';
            $data['tabela'] = array(
                                    array('nome' => 'bairros'),
                                    array('nome' => 'cidades', 'where' => 'bairros.cidade = cidades.id', 'tipo' => 'INNER'),
                                    array('nome' => 'imoveis', 'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'INNER'),
                                    array('nome' => 'empresas', 'where' => 'empresas.id = imoveis.id_empresa', 'tipo' => 'INNER'),
                                    );
            if (is_array($filtro) )	
            {
                $data['filtro'] = $filtro;
                $data['filtro'][] = 'imoveis.id_empresa = '.$id_empresa;
                $data['filtro'][] = 'bairros.cidade = '.$id_cidade;

            }
            else
            {
                $data['filtro'][] = $filtro;
                $data['filtro'][] = 'imoveis.id_empresa = '.$id_empresa;
                $data['filtro'][] = 'bairros.cidade = '.$id_cidade;
            }
            $data['col'] = $coluna;
            $data['ordem'] = $ordem;
            $data['group'] = 'bairros.id';
            $retorno = $this->get_itens_($data);
            
        }
        else
        {
            $retorno['itens'] = array();
        }
    	return $retorno['itens'];
    }
    
    
}
