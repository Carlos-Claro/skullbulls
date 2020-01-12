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
class Caes extends MY_Controller {
    
    /**
         * cria um array validar a página com os campos necessários
         * @var array valida
         */
	private $valida = array(
                                array( 'field'   => 'nome',               'label'   => 'Nome', 		'rules'   => 'required'),
                                array( 'field'   => 'id_raca',               'label'   => 'Raça',         'rules'   => 'required'),
                                array( 'field'   => 'id_cor',        'label'   => 'Cor',  'rules'   => 'trim'),
                                array( 'field'   => 'id_mae',       'label'   => 'Mãe', 'rules'   => 'trim'),
                                array( 'field'   => 'id_pai',         'label'   => 'Pai',   'rules'   => 'trim'),
                                array( 'field'   => 'id_canil_origem',              'label'   => 'Canil Origem',     'rules'   => 'trim'),
                                array( 'field'   => 'id_canil_atual',          'label'   => 'Canil Atual', 'rules'   => 'trim'),
                                array( 'field'   => 'sexo',         'label'   => 'sexo','rules'   => 'required'),
                                array( 'field'   => 'local_atual',              'label'   => 'Local atual',     'rules'   => 'trim'),
                                array( 'field'   => 'local_nascimento',         'label'   => 'Local Nascimento','rules'   => 'trim'),
                                array( 'field'   => 'data_nascimento',             'label'   => 'Data Nascimento',    'rules'   => 'trim'),
                                array( 'field'   => 'ativo',            'label'   => 'Ativo',   'rules'   => 'trim'),
                                );
    
    public function __construct() {
        parent::__construct();
        $this->load->model([
            'caes_model',
            'canis_model',
            'cores_model',
            'racas_model',
                            ]);
    }
    
    public function index()
    {
        $this->listar();
    }

    public function listar($coluna = 'caes.id', $ordem = 'DESC', $off_set = 0)
    {
        $off_set = ((isset($_GET['per_page'])) ? $_GET['per_page'] : 0);
        $classe = strtolower(__CLASS__);
        $function = strtolower(__FUNCTION__);
        $url = base_url() . $classe . '/' . $function . '/' . $coluna . '/' . $ordem;
        $valores = (isset($_GET['b']) ? $_GET['b'] : array());
        $filtro = $this->_inicia_filtros($url, $valores);
        $filtro_ = $filtro->get_filtro();
        $itens = $this->caes_model->get_itens($filtro_, $coluna, $ordem, $off_set);
        $total = $this->caes_model->get_total_itens($filtro_);
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
            ->set_include('js/caes.js', TRUE)
            ->set_include('js/jquery.mask.js', TRUE)
            ->set_include('css/estilo.css', TRUE)
            ->set_breadscrumbs('Painel', 'painel', 0)
            ->set_breadscrumbs('Cães', 'caes', 1)
            ->view('listar', $data);
    }
    
    public function exportar()
    {
        $url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
        $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
        $filtro = $this->_inicia_filtros( $url, $valores );
        $data = $this->caes_model->get_itens( $filtro->get_filtro() );
        exporta_excel($data, __CLASS__.date('YmdHi'));
    }
    
    
    public function adicionar($id_cliente = NULL)
    {
        $data = $this->_post();
        $data['ativo'] = 1;
        $id = $this->caes_model->adicionar($data);
        redirect(base_url().'caes/editar/'.$id);
    }

    public function editar($codigo = NULL, $ok = NULL){
        $this->form_validation->set_rules($this->valida);
        if ($this->form_validation->run())
        {
                $data = $this->_post();
                if(isset($codigo))
                {
                    $id = $this->caes_model->editar($data, 'caes.id = '.$codigo);
                }
                else
                {
                    $id = $this->caes_model->adicionar($data);
                }
                redirect(strtolower(__CLASS__).'/editar/'.(isset($codigo)?$codigo:$id).'/1');
        }
        else
        {
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $data = $this->_inicia_select(isset($codigo)?$codigo:NULL);//erro
                
                $data['action'] = base_url().$class.'/'.$function.'/'.isset($codigo) ? $codigo : '';
                $data['tipo'] = 'Cães Editar';//$data = $this->_init_selects();
                $data['mostra_id'] = isset($codigo)? TRUE : NULL;
                $data['erro'] = (isset($ok)) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                $this->layout
                        ->set_function( $function )
                        ->set_include('js/caes.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('css/datepicker.css', TRUE)
                        ->set_include('js/upload2/funcs.js', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('cães', 'caes', 0)
                        ->set_breadscrumbs($data['item']->nome, 'caes', 1)
                        ->view('add_caes', $data);
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
		$quantidade = $this->caes_model->excluir('caes.id in ('.implode(',',$selecionados).')');
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
        if ( isset($codigo) ){
            $retorno['item'] = $this->caes_model->get_item($codigo);
            $retorno['pai'] = $this->caes_model->get_select('caes.sexo LIKE "M" AND caes.data_nascimento > "'.$retorno['item']->data_nascimento.'"');
            $retorno['mae'] = $this->caes_model->get_select('caes.sexo LIKE "F" AND caes.data_nascimento > "'.$retorno['item']->data_nascimento.'"');
            
        }else{
            $retorno['pai'] = $this->caes_model->get_select('caes.sexo LIKE "M"');
            $retorno['mae'] = $this->caes_model->get_select('caes.sexo LIKE "F"');
            
        }
        $retorno['sexo'] = [
            (object)['id'=>'','descricao'=>'Selecione'],
            (object)['id'=>'M','descricao'=>'Macho'],
            (object)['id'=>'F','descricao'=>'Femea'],
        ];
        $retorno['canis'] = $this->canis_model->get_select('ativo = 1');
        $retorno['cores'] = $this->cores_model->get_select();
        $retorno['raca'] = $this->racas_model->get_select();
        return $retorno;
    }
        
    private function _inicia_filtros($url = '', $valores = array())
    {
        $select = $this->_inicia_select();
        
        $config['itens'] = array(
            array('name' => 'id',           'titulo' => 'ID: ',     'tipo' => 'text',   'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'caes.id',         'valor' => '')),
            array('name' => 'nome',           'titulo' => 'Nome: ',     'tipo' => 'text',   'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'like', 'campo' => 'caes.nome',         'valor' => '')),
            array('name' => 'id_raca',           'titulo' => 'Raça: ',     'tipo' => 'select',   'valor' => $select['raca'], 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'caes.id_raca',         'valor' => '')),
            array('name' => 'id_cor',           'titulo' => 'Raça: ',     'tipo' => 'select',   'valor' => $select['raca'], 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'caes.id_raca',         'valor' => '')),
            array('name' => 'id_pai',           'titulo' => 'Pai: ',     'tipo' => 'select',   'valor' => $select['pai'], 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'caes.id_pai',         'valor' => '')),
            array('name' => 'id_mae',           'titulo' => 'Mãe: ',     'tipo' => 'select',   'valor' => $select['mae'], 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'caes.id_mae',         'valor' => '')),
            array('name' => 'sexo',           'titulo' => 'Sexo: ',     'tipo' => 'select',   'valor' => $select['sexo'], 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'caes.sexo',         'valor' => '')),
            array('name' => 'canil_atual',           'titulo' => 'Canil_atual: ',     'tipo' => 'select',   'valor' => $select['canis'], 'classe' => 'form-control ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'caes.id_canil_atual',         'valor' => '')),
        );
        $config['colunas'] = 4;
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
                            ['id' => 'nome', 'descricao' => 'Nome'],
                            ['id' => 'pai_nome', 'descricao' => 'Pai'],
                            ['id' => 'mae_nome', 'descricao' => 'Mãe'],
                            ['id' => 'data_nascimento', 'descricao' => 'Data nascimento'],
                            ];
            
            
        $data['cabecalho'] = [];
        foreach($cabecalho as $a){
            $data['cabecalho'][] = (object)array( 'chave' => $a['id'],'titulo' => $a['descricao'],'link' => str_replace(array('[col]','[ordem]'), array($a['id'],( ($extras['ordem'] == 'ASC' && $extras['col'] == $a['id']) ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == $a['id'] ) ? 'ui-state-highlight'.( ($extras['col'] == $a['id'] && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
        }

            $data['operacoes'] = array(
                                        (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'link' => 'caes/editar/[id]'),
                                    );

            $data['chave'] = 'id';
            $data['itens'] = $itens['itens'];
            $data['extras'] = $extras;
            $data['extras']['botao'] = '<a href="'.base_url().'caes/adicionar/" class="add-curso btn btn-success pull-right" target="_blank">Adicionar novo cão</a>';
            $data['extras']['botao'] .= '<a href="'.base_url().'caes/exportar'.$extras['get_url'].'" class="exportar btn btn-info pull-right" target="_blank">Exportar lista</a>';
            $this->listagem->inicia( $data );
            $retorno = $this->listagem->get_html();
        }
        return $retorno;
    }
    
    private function _post() {
        $dados = $this->input->post(NULL,TRUE);
        $dados['data_nascimento'] = ($dados['data_nascimento'] ? converte_data_mysql($dados['data_nascimento']) : date('Y-m-d H:i:s') );
        $dados['ativo'] = isset($dados['ativo']) ? $dados['ativo'] : 1;
        return $dados;
    }
    

    
    public function get_arvore($id){
        $data['itens'] = $this->caes_model->get_itens_arvore($id);
        $i = 0;
        if ( isset($data['itens']['item']) ){
            $d['item'] = $data['itens']['item'];
            $data['cao'] = $this->layout->view('arvore_ficha', $d, 'layout/sem_menu_includes',TRUE);
            if ( isset($data['itens']['itens']['pai']['item']) ){
                $dp['item'] = $data['itens']['itens']['pai']['item'];
                $data['pai'] = $this->layout->view('arvore_ficha', $dp, 'layout/sem_menu_includes', TRUE);
                if ( isset($data['itens']['itens']['pai']['itens']['pai']['item']) ){
                    $dpp['item'] = $data['itens']['itens']['pai']['itens']['pai']['item'];
                    $data['pai_pai'] = $this->layout->view('arvore_ficha', $dpp, 'layout/sem_menu_includes', TRUE);
                }
                if ( isset($data['itens']['itens']['pai']['itens']['mae']['item']) ){
                    $dpm['item'] = $data['itens']['itens']['pai']['itens']['mae']['item'];
                    $data['pai_mae'] = $this->layout->view('arvore_ficha', $dpm, 'layout/sem_menu_includes', TRUE);
                }
                
            }
            if ( isset($data['itens']['itens']['mae']['item']) ){
                $dm['item'] = $data['itens']['itens']['mae']['item'];
                $data['mae'] = $this->layout->view('arvore_ficha', $dm, 'layout/sem_menu_includes', TRUE);
                if ( isset($data['itens']['itens']['mae']['itens']['pai']['item']) ){
                    $dmp['item'] = $data['itens']['itens']['mae']['itens']['pai']['item'];
                    $data['mae_pai'] = $this->layout->view('arvore_ficha', $dmp, 'layout/sem_menu_includes', TRUE);
                }
                if ( isset($data['itens']['itens']['mae']['itens']['mae']['item']) ){
                    $dmm['item'] = $data['itens']['itens']['mae']['itens']['mae']['item'];
                    $data['mae_mae'] = $this->layout->view('arvore_ficha', $dmm, 'layout/sem_menu_includes', TRUE);
                }
            }
        }
        $retorno = $this->layout->view('arvore', $data, 'layout/sem_menu_includes', TRUE);
        echo $retorno;
    }
}
