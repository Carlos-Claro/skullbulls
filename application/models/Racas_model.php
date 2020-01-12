<?php

class Racas_Model extends MY_Model
{

    private $database = NULL;

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->database = array('db' => 'skullbulls', 'table' => 'racas');
    }

    public function adicionar($data = array())
    {
        return $this->adicionar_($this->database, $data);
    }
    public function adicionar_multi($data = array())
    {
        return $this->adicionar_multi_($this->database, $data);
    }

    public function editar($data = array(), $filtro = array())
    {
        return $this->editar_($this->database, $data, $filtro);
    }

    public function excluir($filtro)
    {
        return $this->excluir_($this->database, $filtro);
    }

//                            DATE_FORMAT(rastreamento_transportadora.data_cadastro, "%d-%m-%Y %H:%i") as data_cadastro,
//                                array('nome' => 'empresas',	'where' => 'rastreamento_transportadora.id_empresa = empresas.id ', 'tipo' => 'INNER'),
    public function get_item($id = '',$id_empresa = NULL)
    {
        $data['coluna'] = ' *
                            ';
        $data['tabela'] = array(
                                array('nome' => 'racas'),
                                );

        $data['filtro'] = 'racas.id = ' . $id;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0])? $retorno['itens'][0] : NULL;
    }

    public function get_item_por_filtro($filtro = NULL)
    {
        $data['coluna'] = ' 
                             racas.*, 
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'racas'),
                                );
    	$data['filtro'] = $filtro;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    
    }
    

    public function get_select($filtro = array(), $coluna = 'titulo', $ordem = 'ASC')
    {
        $data['coluna'] = 'racas.id as id, racas.titulo as descricao ';
        $data['tabela'] = array(
                                array('nome' => 'racas'),
        );

        $data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $retorno = $this->get_itens_($data);
        return $retorno['itens'];
    }
    
    
    public function get_total_itens($filtro = array())
    {
        $data['coluna'] = '	
                            count(racas.id) as qtde,
                            ';
        $data['tabela'] = array(
                            array('nome' => 'racas'),
        );
        $data['filtro'] = $filtro;
        $retorno = $this->get_itens_($data);
        
        return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }


    public function get_itens($filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL, $qtde_itens = 30 )
    {
        $data['coluna'] = 'racas.*';
       $data['tabela'] = array(
                                array('nome' => 'racas'),
                                
                                );
        $data['filtro'] = $filtro;
        if (isset($off_set)) {
            $data['off_set'] = $off_set;
            $data['qtde_itens'] = $qtde_itens;
        }
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $data['group'] = 'racas.id';
        $itens = $this->get_itens_($data);
        return $itens;
    }
    
}