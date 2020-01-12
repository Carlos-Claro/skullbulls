<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Classe que gerencia o login no sistema, com verificação de status e hierarquia.
 * @access public
 * @version 0.1
 * @author Carlos Claro
 * @package Login
 * 
 */
class Painel extends MY_Controller 
{
    
    /**
     * Controi a classe e carrega valores de extends
     * e carrega models padrao para esta classe
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('usuarios_model'));
    }
    
    /**
     * cria o lay-out de acordo com a listagem,
     * carrega arquivos js e css opcionais
     * @param string $coluna
     * @param string $ordem
     * @param type string $off_set
     * @version 1.0
     * @access public
     */
    public function index($coluna = 'empresas_ocorrencia.data_retorno_inicio', $ordem = 'DESC', $off_set = 0)
    {
        $layout = $this->layout;
        $classe = strtolower(__CLASS__);
        $function = strtolower(__FUNCTION__);
        $data['inacessivel'] = isset($_GET['inacessivel']) ? $_GET['inacessivel'] : NULL;
        
        $layout
                    ->set_include('css/estilo.css', TRUE)
                    ->view('painel', $data, 'layout/layout');
    }
    
    /**
     * caregas ocorrencias_model, pow_campanhas_model e tarefas_model
     * @return array $retorno
     * @version 1.0
     * @access private
     */
    private function _dados_listagem()
    {
        $this->load->model(array('ocorrencias_model', 'pow_campanhas_model', 'tarefas_model'));
        $retorno = NULL;
       
        $retorno['itens']['ocorrencias'] = $this->ocorrencias_model->get_itens_ocorrencias('empresas_ocorrencia.id_usuario_ativo = '.$this->get_id_usuario().' AND (empresas_status_ocorrencia.id = 4)', 'empresas_ocorrencia.data_retorno_inicio', 'DESC', 0 );
        $retorno['total']['ocorrencias'] = $this->ocorrencias_model->get_total_itens_ocorrencias( 'empresas_ocorrencia.id_usuario_ativo = '.$this->get_id_usuario().' AND (empresas_status_ocorrencia.id = 4)' );
        
        //$retorno['total']['tarefas'] = $this->tarefas_model->get_itens('tarefas.id_usuario = '.$this->get_id_usuario().' AND tarefas.id_tarefas_status = 1', 'data_inicio', 'ASC');
        //$retorno['itens']['tarefas'] = $this->tarefas_model->get_itens('tarefas.id_usuario = '.$this->get_id_usuario().' AND tarefas.id_tarefas_status = 1', 'data_inicio', 'ASC');
        
        $i = 'empresas/administrar';
        $acesso = $this->set_setor_usuario($i, FALSE);
        if ( $acesso['status'] )
        {
            $this->load->model('empresas_model');
            $data_inicio = mktime(date('H'), date('i'),0, date('n') + 1, date('j'), date('Y'));
            $data = mktime(date('H'), date('i'),0, date('n'), date('j'), date('Y'));
            $retorno['total']['vencendo'] = $this->empresas_model->get_itens('empresas.servicos_pagina_termino >= ' . $data . ' AND empresas.servicos_pagina_termino <= ' . $data_inicio . ' AND servicos_pagina= "1" AND empresas.bloqueado = 0 ', 'empresas.servicos_pagina_termino', 'DESC');
            $retorno['itens']['vencendo'] = $this->empresas_model->get_itens('empresas.servicos_pagina_termino >= ' . $data . ' AND empresas.servicos_pagina_termino <= ' . $data_inicio . ' AND servicos_pagina= "1" AND empresas.bloqueado = 0 ', 'empresas.servicos_pagina_termino', 'ASC');
            
            $data = mktime(date('H'), date('i'),0, date('n'), date('j'), date('Y'));
            $retorno['total']['vencimento'] = $this->empresas_model->get_itens('empresas.servicos_pagina_termino <=' . $data . ' AND servicos_pagina= "1" AND empresas.bloqueado = 0 ', 'empresas.servicos_pagina_termino', 'DESC');
            $retorno['itens']['vencimento'] = $this->empresas_model->get_itens('empresas.servicos_pagina_termino <= '.$data. ' AND servicos_pagina= "1" AND empresas.bloqueado = 0 ', 'empresas.servicos_pagina_termino', 'DESC');
            //var_dump($retorno['itens']['vencimento']);
            
        }
        
        
        
        return $retorno;
    }
    
    /**
     * Carregas os dados da listagem e inicia a listagem
     * @return array $retorno
     * @version 1.0
     * @access private
     */
    private function _get_listagem()
    {
        $retorno = NULL;
        $dados = $this->_dados_listagem();
        if(isset($dados['itens']) && !empty($dados['itens']))
        {
            foreach($dados['itens'] as $chave => $valor)
            {
                
                if(isset($valor) && !empty($valor))
                {
                    $retorno[$chave] = $this->_inicia_listagem($chave, $valor, NULL);
                }
            }
            //var_dump($retorno['vencimento']);
        }
        return $retorno;
    }
    
    /**
     * Cria uma lista de canais no estilo listagem_etiqueta,
         * chama os campos necessários para criar o cabeçalho e 
         * define id como chave
         * @param bool $tipo - cada caso retorna um resoltado diferente 
         * @param array $itens
         * @param array $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
     */
    private function _inicia_listagem($tipo = FALSE, $itens, $extras = NULL )
    {
            switch($tipo)
            {
                case 'ocorrencias' :
                    $data['cabecalho'] = array(
                                                (object)array( 'chave' => 'empresa',                'titulo' => 'Empresa',               'classe' => 'col-lg-9 col-sm-9 col-md-9 col-xs-9',              'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? ' '.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'assunto',                'titulo' => 'Assunto',           'classe' => 'pull-right col-lg-3 col-sm-3 col-md-3 col-xs-3',   'link' => str_replace(array('[col]','[ordem]'), array('assunto',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'assunto') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'assunto' ) ? ''.( ($extras['col'] == 'assunto' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'data_inicio',    'titulo' => 'Data de inicio', 	'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? ''.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'retorno_inicio',       'titulo' => 'Data de Retorno', 	'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('retorno_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'retorno_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'retorno_inicio' ) ? ''.( ($extras['col'] == 'retorno_inicio' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                /*
                                                (object)array( 'chave' => 'empresa', 'titulo' => 'Empresa', 	'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) , 'classe_destino' => 'empresas/editar/[id]'),
                                                (object)array( 'chave' => 'assunto', 'titulo' => 'Assunto', 	'link' => str_replace(array('[col]','[ordem]'), array('assunto',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'assunto') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'assunto' ) ? 'ui-state-highlight'.( ($extras['col'] == 'assunto' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                (object)array( 'chave' => 'data_inicio', 'titulo' => 'Inicio', 	'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                (object)array( 'chave' => 'retorno_inicio',  'titulo' => 'Data de Retorno',    'link' => str_replace(array('[col]','[ordem]'), array('retorno_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'retorno_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'retorno_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'retorno_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                */
                                            );
                    $data['titulo'] = '<a href="'.base_url().'ocorrencias/listar/envolvidas" class="btn btn-info">ver todas as Ocorrencias</a>';
                    break;
                case 'campanhas' :
                    $data['cabecalho'] = array(
                                                (object)array( 'chave' => 'titulo',                'titulo' => 'Titulo',               'classe' => 'col-lg-9 col-sm-9 col-md-9 col-xs-9',              'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? ' '.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                //(object)array( 'chave' => 'assunto', 'titulo' => 'Assunto', 	'link' => str_replace(array('[col]','[ordem]'), array('assunto',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'assunto') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'assunto' ) ? 'ui-state-highlight'.( ($extras['col'] == 'assunto' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                //(object)array( 'chave' => 'data_inicio', 'titulo' => 'Inicio', 	'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                //(object)array( 'chave' => 'retorno_inicio',  'titulo' => 'Data de Retorno',    'link' => str_replace(array('[col]','[ordem]'), array('retorno_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'retorno_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'retorno_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'retorno_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                            );
                    $data['titulo'] = '<a href="'.base_url().'/campanhas" class="btn btn-info">Campanhas</a>';
                    break;
                case 'tarefas' :
                    $data['cabecalho'] = array(
                                                (object)array( 'chave' => 'id',             'titulo' => 'ID',               'classe' => 'col-lg-9 col-sm-9 col-md-9 col-xs-9',              'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? ' '.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'tarefa_status',  'titulo' => 'Status',           'classe' => 'pull-right col-lg-3 col-sm-3 col-md-3 col-xs-3',   'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? ''.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'titulo',         'titulo' => 'Titulo',           'classe' => 'col-lg-12 col-sm-12 col-md-12 col-xs-12',              'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? ''.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'data_inicio',    'titulo' => 'Data de inicio', 	'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? ''.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'data_fim',       'titulo' => 'Data de Fim', 	'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? ''.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'usuario',        'titulo' => 'Quem Abriu', 	'classe' => 'col-lg-12 col-sm-12 col-md-12 col-xs-12',              'link' => str_replace(array('[col]','[ordem]'), array('usuario',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'usuario') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'usuario' ) ? ''.( ($extras['col'] == 'usuario' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'usuarios',       'titulo' => 'Envolvidos', 	'classe' => 'col-lg-12 col-sm-12 col-md-12 col-xs-12',              'link' => str_replace(array('[col]','[ordem]'), array('usuarios',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'usuarios') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'usuarios' ) ? ''.( ($extras['col'] == 'usuarios' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'qtde_atividades','titulo' => 'Qtde Atividades', 	'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-12',              'link' => str_replace(array('[col]','[ordem]'), array('qtde_atividades',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_atividades') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_atividades' ) ? ''.( ($extras['col'] == 'qtde_atividades' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'empresas',       'titulo' => 'Empresas', 	'classe' => 'col-lg-8 col-sm-8 col-md-8 col-xs-12',              'link' => str_replace(array('[col]','[ordem]'), array('empresas',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresas') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresas' ) ? ''.( ($extras['col'] == 'empresas' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                            );
                    $data['operacoes'] = array(
                                                (object) array('titulo' => 'Ver', 'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3 btn btn-info pull-right', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                            );
                    $data['titulo'] = '<a href="'.base_url().'/tarefas/agenda" class="btn btn-info">Tarefas</a>';
                    break;
                case 'vencimento' :
                    $data['cabecalho'] = array(
                                                (object)array( 'chave' => 'id',                     'titulo' => 'ID',               'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? ' '.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'nome_fantasia',          'titulo' => 'Nome Fantasia',    'classe' => 'col-lg-9 col-sm-9 col-md-9 col-xs-6',   'link' => str_replace(array('[col]','[ordem]'), array('nome_fantasia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_fantasia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_fantasia' ) ? ''.( ($extras['col'] == 'nome_fantasia' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'servicos_pagina_termino','titulo' => 'Data',             'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-12',              'link' => str_replace(array('[col]','[ordem]'), array('servicos_pagina_termino',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'servicos_pagina_termino') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'servicos_pagina_termino' ) ? ''.( ($extras['col'] == 'servicos_pagina_termino' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                            );
                    $data['operacoes'] = array(
                                                (object) array('titulo' => 'Ver', 'class' => 'col-lg-6 col-sm-6 col-md-6 col-xs-12 btn btn-danger pull-left', 'icone' => '<span class="glyphicon glyphicon-ok"></span>', 'link' => 'empresas/editar_administrar/[id]', 'extra' => 'target="_blank"'),
                                            );
                    $data['titulo'] = '<a href="'.base_url().'empresas/administrar" class="btn btn-info">Serviço Vencidos</a>';
                    break;
                case 'vencendo' :
                    $data['cabecalho'] = array(
                                                (object)array( 'chave' => 'id',                     'titulo' => 'ID',               'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? ' '.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'nome_fantasia',          'titulo' => 'Nome Fantasia',    'classe' => 'col-lg-9 col-sm-9 col-md-9 col-xs-6',   'link' => str_replace(array('[col]','[ordem]'), array('nome_fantasia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_fantasia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_fantasia' ) ? ''.( ($extras['col'] == 'nome_fantasia' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                (object)array( 'chave' => 'servicos_pagina_termino','titulo' => 'Data',             'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-12',              'link' => str_replace(array('[col]','[ordem]'), array('servicos_pagina_termino',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'servicos_pagina_termino') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'servicos_pagina_termino' ) ? ''.( ($extras['col'] == 'servicos_pagina_termino' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                            );
                    $data['operacoes'] = array(
                                                (object) array('titulo' => 'Ver', 'class' => 'col-lg-6 col-sm-6 col-md-6 col-xs-12 btn btn-warning pull-left', 'icone' => '<span class="glyphicon glyphicon-ok"></span>', 'link' => 'empresas/editar_administrar/[id]', 'extra' => 'target="_blank"'),
                                            );
                    $data['titulo'] = '<a href="'.base_url().'/empresas/administrar" class="btn btn-info">Serviço vencendo no proximo mês</a>';
                    break;
            }

            $data['chave'] = 'id';
            $data['itens'] = $itens['itens'];
            $data['extras'] = $extras;
            /*
            switch($tipo)
            {
                case 'ocorrencias':
                case 'campanhas':
                    $this->listagem->inicia( $data );
                    $retorno = $this->listagem->get_html();
                    break;
                case 'tarefas':
                    $data['qtde_por_linha'] = 1;
                    $this->load->library('listagem_etiqueta');
                    $this->listagem_etiqueta->inicia( $data );
                    $retorno = $this->listagem_etiqueta->get_html();
                    break;
            }*/
            $data['qtde_por_linha'] = 1;
            $this->load->library('listagem_etiqueta');
            $this->listagem_etiqueta->inicia( $data );
            $retorno = $this->listagem_etiqueta->get_html();
            return $retorno;
    }
    
    
    private function empresa_setores()
    {
        $retorno = array();
        $id_empresa = $this->sessao['id_empresa'];
        $mes_anterior = date('Y-m-d', strtotime('-1 months', strtotime(date('Y-m-d'))));
        $this->load->model(array('logs_dia_model','empresas_model','contatos_site_model'));
        
        if($this->set_setor_usuario('ofer_lanc_serv', FALSE)['status'])
        {
            $retorno['estatisticas']['dos_produtos']['setor'] = 'ofer_lanc_serv';
        }
        if($this->set_setor_usuario('site_paginas', FALSE)['status'])
        {
            $retorno['estatisticas']['do_site']['setor'] = 'empresas';
        }
//        if($this->set_setor_usuario('imoveis', FALSE)['status'])
//        {
//            $portal = $this->empresas_model->get_item($this->sessao['id']);
//            if(isset($portal) && !is_null($portal->plano_publicidade))
//            {
//                $retorno['estatisticas_portal'] = TRUE;
//            }
//            if(isset($portal) && $portal->tem_site == 1)
//            {
//                $retorno['estatisticas']['dos_imoveis']['setor'] = 'imoveis';
//            }
//        }
        if($this->set_setor_usuario('contatos_site/contatos_empresa', FALSE)['status'])
        {
            if(isset($this->sessao['corretor']))
            {
                $retorno['contatos'] = $this->contatos_site_model->get_itens_corretor('contatos_site.id_empresa = '.$id_empresa.' AND imoveis.id_corretor = '.$this->sessao['id']);
            }
            else
            {
                $retorno['contatos'] = $this->contatos_site_model->get_itens('id_empresa = '.$id_empresa);
            }
            
        }

        return $retorno;
    }
    /*
    private function _inicia_filtros($tipo = FALSE, $url = '', $valores = array() )
    {
            $config['itens'] = array(
                                        //array( 'name' => 'id',              'titulo' => 'ID da Campanha: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'pow_campanhas.id', 	'valor' => '' ) ),
                                        array( 'name' => 'empresa',              'titulo' => 'Nome da Empresa: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_nome_fantasia', 	'valor' => '' ) ),
                                        array( 'name' => 'retorno_inicio',              'titulo' => 'Retorno Inicio: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_ocorrencia.data_retorno_inicio', 	'valor' => '' ) ),
                                        array( 'name' => 'assunto',          'titulo' => 'Assunto: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_ocorrencia_assunto.titulo', 	'valor' => '' ) ),
                                        //array( 'name' => 'status',              'titulo' => 'Status: ',             'tipo' => 'select', 'valor' => $this->empresas_status_ocorrencia_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_status_ocorrencia.id', 	'valor' => '' ) ),
                                        //array( 'name' => 'link',           'titulo' => 'Link: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'culinaria_categorias.link', 		'valor' => '' ) ),
                            );
            $config['colunas'] = 3;
            $config['url']     = $url;
            $config['extras']  = '';
            $config['valores'] = $valores;
            $config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';

            $filtro = $this->filtro->inicia($config);
            return $filtro;
    }*/
    
    /*
    public function index()
    {
        $coluna = 'empresas_ocorrencia.id ';
        $ordem = 'DESC'; 
        $off_set = 0;
        $classe = strtolower(__CLASS__);
        $function = strtolower(__FUNCTION__);
        $id = $this->get_id_usuario();
        $itens = $this->ocorrencias_model->get_itens_abertos('empresas_status_ocorrencia.id = 2 OR empresas_status_ocorrencia.id = 3', $coluna, $ordem, $off_set );
        $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/';
        $extras['col'] = 'empresas_ocorrencia.id';
        $extras['ordem'] = 'DESC'; 
        $data['newsletter'] = $this->newsletter_library->get_html();
        $data['listagem_ocorrencia'] = $this->_inicia_listagem_ocorrencia( $itens, $extras );
        $this->layout
                    ->set_titulo('')
                    ->set_keywords('')
                    ->set_description('')
                    ->set_usuario()
                    ->set_include('js/login.js', TRUE)
                    ->set_include('js/newsletter.js', TRUE)
                    ->set_include('css/estilo.css', TRUE)
                    ->set_menu($this->get_menu(strtolower(__CLASS__), strtolower(__FUNCTION__)))
                    ->view('painel', $data, 'layout/layout'); 
    }
    
    private function _inicia_listagem_ocorrencia( $itens, $extras = NULL, $exportar = FALSE )
    {
            if ( $exportar )
            {
                    $cabecalho = ' ';
            }
            else 
            {
                    $data['cabecalho'] = array(
                                                (object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                (object)array( 'chave' => 'empresa', 'titulo' => 'Empresa', 	'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) , 'classe_destino' => 'empresas/editar'),
                                                //(object)array( 'chave' => 'id_usuario_retorno','titulo' => 'Usuario de Retorno', 	'link' => str_replace(array('[col]','[ordem]'), array('id_usuario_retorno',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_usuario_retorno') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_usuario_retorno' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id_usuario_retorno' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                (object)array( 'chave' => 'data_retorno',  'titulo' => 'Data de Retorno',    'link' => str_replace(array('[col]','[ordem]'), array('data_retorno',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_retorno') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_retorno' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_retorno' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                );

                    $data['chave'] = 'id';
                    $data['itens'] = $itens['itens'];
                    $data['extras'] = $extras;
                    $this->listagem->inicia( $data );
                    $retorno = $this->listagem->get_html();
            }
            return $retorno;
    }*/
}