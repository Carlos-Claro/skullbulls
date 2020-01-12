<?php

class Caes_Model extends MY_Model
{

    private $database = NULL;

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->database = array('db' => 'skullbulls', 'table' => 'caes');
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
    
    public function get_item($id = '')
    {
        $data['coluna'] = ' caes.*,
                            DATE_FORMAT(caes.data_nascimento, "%d/%m/%Y %H:%i") as data_nascimento,
                            atual.nome as canil_atual,
                            cores.titulo as cor,
                            racas.titulo as raca,
                            if (caes.sexo = "M", "Macho", "Femea") as sexo_,
                            ';
        $data['tabela'] = array(
                                array('nome' => 'caes'),
                                array('nome' => 'caes mae',	'where' => 'mae.id = caes.id_mae', 'tipo' => 'LEFT'),
                                array('nome' => 'caes pai',	'where' => 'pai.id = caes.id_pai', 'tipo' => 'LEFT'),
                                array('nome' => 'canis origem',	'where' => 'origem.id = caes.id_canil_origem', 'tipo' => 'LEFT'),
                                array('nome' => 'canis atual',	'where' => 'atual.id = caes.id_canil_atual', 'tipo' => 'LEFT'),
                                array('nome' => 'cores',	'where' => 'cores.id = caes.id_cor', 'tipo' => 'LEFT'),
                                array('nome' => 'racas',	'where' => 'racas.id = caes.id_raca', 'tipo' => 'LEFT'),
                                );

        $data['filtro'] = 'caes.id = ' . $id;
        $retorno = $this->get_itens_($data);
        $item = isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
        return $item;
    }

    public function get_item_por_filtro($filtro = NULL)
    {
        $data['coluna'] = ' 
                             caes.*, 
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'caes'),
                                array('nome' => 'caes mae',	'where' => 'mae.id = caes.id_mae', 'tipo' => 'LEFT'),
                                array('nome' => 'caes pai',	'where' => 'pai.id = caes.id_pai', 'tipo' => 'LEFT'),
                                array('nome' => 'canis origem',	'where' => 'origem.id = caes.id_canil_origem', 'tipo' => 'LEFT'),
                                array('nome' => 'canis atual',	'where' => 'atual.id = caes.id_canil_atual', 'tipo' => 'LEFT'),
                                array('nome' => 'cores',	'where' => 'cores.id = caes.id_cor', 'tipo' => 'LEFT'),
                                array('nome' => 'racas',	'where' => 'racas.id = caes.id_raca', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    
    }
    

    public function get_select($filtro = array(), $coluna = 'caes.nome', $ordem = 'ASC')
    {
        $data['coluna'] = 'caes.id as id, if (mae.nome,CONCAT(caes.nome, " - Mãe: ", mae.nome),caes.nome) as descricao ';
        $data['tabela'] = array(
                                array('nome' => 'caes'),
                                array('nome' => 'caes mae',	'where' => 'mae.id = caes.id_mae', 'tipo' => 'LEFT'),
                                array('nome' => 'caes pai',	'where' => 'pai.id = caes.id_pai', 'tipo' => 'LEFT'),
                                array('nome' => 'canis origem',	'where' => 'origem.id = caes.id_canil_origem', 'tipo' => 'LEFT'),
                                array('nome' => 'canis atual',	'where' => 'atual.id = caes.id_canil_atual', 'tipo' => 'LEFT'),
                                array('nome' => 'cores',	'where' => 'cores.id = caes.id_cor', 'tipo' => 'LEFT'),
                                array('nome' => 'racas',	'where' => 'racas.id = caes.id_raca', 'tipo' => 'LEFT'),
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
                            count(caes.id) as qtde,
                            ';
        $data['tabela'] = array(
                                array('nome' => 'caes'),
                                array('nome' => 'caes mae',	'where' => 'mae.id = caes.id_mae', 'tipo' => 'LEFT'),
                                array('nome' => 'caes pai',	'where' => 'pai.id = caes.id_pai', 'tipo' => 'LEFT'),
                                array('nome' => 'canis origem',	'where' => 'origem.id = caes.id_canil_origem', 'tipo' => 'LEFT'),
                                array('nome' => 'canis atual',	'where' => 'atual.id = caes.id_canil_atual', 'tipo' => 'LEFT'),
                                array('nome' => 'cores',	'where' => 'cores.id = caes.id_cor', 'tipo' => 'LEFT'),
                                array('nome' => 'racas',	'where' => 'racas.id = caes.id_raca', 'tipo' => 'LEFT'),
        );
        $data['filtro'] = $filtro;
        $retorno = $this->get_itens_($data);
        
        return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }


    public function get_itens($filtro = array(), $coluna = ' caes.id', $ordem = 'DESC', $off_set = NULL, $qtde_itens = 30 )
    {
        $data['coluna'] = '
                            caes.*,
                            DATE_FORMAT(caes.data_nascimento, "%d/%m/%Y %H:%i") as data_nascimento,
                            IF ( caes.ativo = 1, "Ativo", "Inativo" ) as ativo,
                            mae.nome as mae_nome,
                            mae.id as mae_id,
                            pai.nome as pai_nome,
                            pai.id as pai_id,
                            cores.titulo as cor,
                            racas.titulo as raca,
                            IF ( caes.sexo = "M", "Macho", "Femea" ) as sexo,
                            CONCAT(origem.nome, " - ", origem.proprietario) as canil_origem,
                            CONCAT(atual.nome, " - ", atual.proprietario) as canil_atual,
                            
                            ';
        $data['tabela'] = array(
                                array('nome' => 'caes'),
                                array('nome' => 'caes mae',	'where' => 'mae.id = caes.id_mae', 'tipo' => 'LEFT'),
                                array('nome' => 'caes pai',	'where' => 'pai.id = caes.id_pai', 'tipo' => 'LEFT'),
                                array('nome' => 'canis origem',	'where' => 'origem.id = caes.id_canil_origem', 'tipo' => 'LEFT'),
                                array('nome' => 'canis atual',	'where' => 'atual.id = caes.id_canil_atual', 'tipo' => 'LEFT'),
                                array('nome' => 'cores',	'where' => 'cores.id = caes.id_cor', 'tipo' => 'LEFT'),
                                array('nome' => 'racas',	'where' => 'racas.id = caes.id_raca', 'tipo' => 'LEFT'),
                                );
        $data['filtro'] = $filtro;
        if (isset($off_set)) {
            $data['off_set'] = $off_set;
            $data['qtde_itens'] = $qtde_itens;
        }
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $data['group'] = 'caes.id';
        $itens = $this->get_itens_($data);
        return $itens;
    }
    
    public function get_pais($item, $camada = 0){
        $retorno = [];
        $camada++;
        if ( $camada < $this->camada_max){
            $pai = $this->get_item($item->id_pai);
            if ( isset($pai) ){
                $retorno['pai']['item'] = $pai;
                $retorno['pai']['itens'] = $this->get_pais($pai, $camada);
            }
            $mae = $this->get_item($item->id_mae);
            if ( isset($mae) ){
                $retorno['mae']['item'] = $mae;
                $retorno['mae']['itens'] = $this->get_pais($mae, $camada);
            }
        }
        return $retorno;
    }
    
    private $camada_max = 3;
    
    public function get_itens_arvore($id){
        $retorno = [];
        $item = $this->get_item($id);
        if ( isset($item) ){
            $retorno['item'] = $item;
            $retorno['itens'] = $this->get_pais($item);
        }
        return $retorno;
        
        
    }
}