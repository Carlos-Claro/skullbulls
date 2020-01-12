<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cursos
 *
 * @author programador
 */
class Cores extends MY_Controller {
    
    /**
         * cria um array validar a página com os campos necessários
         * @var array valida
         */
	private $valida = array(
                                array( 'field'   => 'titulo',               'label'   => 'Titulo',      'rules'   => 'required'),
                                );
    
    public function __construct() {
        parent::__construct();
        $this->load->model([
            'cores_model'
                            ]);
    }
    
    public function index()
    {
        $this->listar();
    }

    public function listar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
    {
        $off_set = ((isset($_GET['per_page'])) ? $_GET['per_page'] : 0);
        $classe = strtolower(__CLASS__);
        $function = strtolower(__FUNCTION__);
        $url = base_url() . $classe . '/' . $function . '/' . $coluna . '/' . $ordem;
        $valores = (isset($_GET['b']) ? $_GET['b'] : array());
        $filtro = $this->_inicia_filtros($url, $valores);
        $filtro_ = $filtro->get_filtro();
        $itens = $this->cores_model->get_itens($filtro_, $coluna, $ordem, $off_set);
        $total = $this->cores_model->get_total_itens($filtro_);
        $get_url = $filtro->get_url();
        $url = $url . ((empty($get_url)) ? '?' : $get_url);
        $data['paginacao'] = $this->init_paginacao($total, $url);
        $data['filtro'] = $filtro->get_html();
        $extras['url'] = base_url() . $classe . '/' . $function . '/[col]/[ordem]/' . $filtro->get_url();
        $extras['col'] = $coluna;
        $extras['ordem'] = $ordem;
        $extras['total_itens'] = $total;
        $extras['get_url'] = $filtro->get_url();
        $data['listagem'] = $this->_inicia_listagem($itens, $extras);
        $this->layout
            ->set_classe($classe)
            ->set_function($function)
            ->set_include('js/listar.js', TRUE)
            ->set_include('js/cores.js', TRUE)
            ->set_include('js/jquery.mask.js', TRUE)
            ->set_include('css/estilo.css', TRUE)
            ->set_breadscrumbs('Painel', 'painel', 0)
            ->set_breadscrumbs('Cores', 'cores', 1)
            ->view('listar', $data);
    }
    
    public function exportar()
    {
        $url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
        $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
        $filtro = $this->_inicia_filtros( $url, $valores );
        $data = $this->cores_model->get_itens( $filtro->get_filtro() );
        exporta_excel($data, __CLASS__.date('YmdHi'));
    }
    
    
    public function adicionar($id_empresa = NULL)
    {
        $data['titulo'] = "Nova Cor";
        $id = $this->cores_model->adicionar($data);
        redirect(base_url().'cores/editar/'.$id);
    }

    public function editar($codigo = NULL, $ok = NULL){
        $this->form_validation->set_rules($this->valida);
        if ($this->form_validation->run())
        {
            $data = $this->_post();
            if(isset($codigo))
            {
                $id = $this->cores_model->editar($data, 'cores.id = '.$codigo);
            }
            else
            {
                $id = $this->cores_model->adicionar($data);
            }
            redirect(strtolower(__CLASS__).'/editar/'.(isset($codigo)?$codigo:$id).'/1');
        }
        else
        {
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $data = $this->_inicia_select(isset($codigo)?$codigo:NULL);//erro
                $data['action'] = base_url().$class.'/'.$function.'/'.isset($codigo) ? $codigo : '';
                $data['tipo'] = 'Cores Editar';//$data = $this->_init_selects();
                $data['item'] = $this->cores_model->get_item($codigo);
                $data['mostra_id'] = isset($codigo)? TRUE : NULL;
                $data['erro'] = (isset($ok)) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                $this->layout
                        ->set_function( $function )
                        ->set_include('js/cores.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Cores', 'cores', 0)
                        ->set_breadscrumbs($data['item']->titulo, 'cores', 1)
                        ->view('add_cores', $data);
        }
        
        
    }
    
    /**
         * deleta um contato site e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
            $selecionados = $this->input->post('selecionados');
            $quantidade = $this->cores_model->excluir('cores.id in ('.implode(',',$selecionados).')');
            if ($quantidade>0)
            {
                    print $quantidade.' itens foram apagados.';
            }
            else 
            {
                    print 'Nenhum item apagado.';
            }
	}
        
    
    private function _inicia_select($codigo = NULL, $id_empresa = NULL){
        $retorno = [];
        return $retorno;
    }
        
    private function _inicia_filtros($url = '', $valores = array())
    {
        $select = $this->_inicia_select();
        
        $config['itens'] = array(
            array('name' => 'id',           'titulo' => 'ID: ',     'tipo' => 'text',   'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'cores.id',         'valor' => '')),
            array('name' => 'titulo',   'titulo' => 'Titulo: ',   'tipo' => 'text',   'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'like', 'campo' => 'cores.titulo', 'valor' => '')),
        );
        $config['colunas'] = 2;
        $config['extras'] = '';
        $config['url'] = $url;
        $config['valores'] = $valores;
        $filtro = $this->filtro->inicia($config);
        return $filtro;

    }
    
    private function _inicia_listagem( $itens, $extras = NULL, $exportar = FALSE )
    {
        if ( $exportar )
        {
            $cabecalho = ' ';
        }
        else 
        {
            $cabecalho = [
                            ['id' => 'id', 'descricao' => 'ID'],
                            ['id' => 'titulo', 'descricao' => 'titulo'],
                            ];
            
            
        $data['cabecalho'] = [];
        foreach($cabecalho as $a){
            $data['cabecalho'][] = (object)array( 'chave' => $a['id'],'titulo' => $a['descricao'],'link' => str_replace(array('[col]','[ordem]'), array($a['id'],( ($extras['ordem'] == 'ASC' && $extras['col'] == $a['id']) ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == $a['id'] ) ? 'ui-state-highlight'.( ($extras['col'] == $a['id'] && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
        }

            $data['operacoes'] = [
                                        (object) ['titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'link' => 'cores/editar/[id]'],
                                    ];

            $data['chave'] = 'id';
            $data['itens'] = $itens['itens'];
            $data['extras'] = $extras;
            $data['extras']['botao'] = '<a href="'.base_url().'cores/adicionar/" class="add-curso btn btn-success pull-right" target="_blank">Adicionar novo cores</a>';
            $data['extras']['botao'] .= '<a href="'.base_url().'cores/exportar'.$extras['get_url'].'" class="exportar btn btn-info pull-right" target="_blank">Exportar lista</a>';
            $this->listagem->inicia( $data );
            $retorno = $this->listagem->get_html();
        }
        return $retorno;
    }
    
    private function _post() {
        $dados = $this->input->post(NULL,TRUE);
        return $dados;
    }
    
    

}
