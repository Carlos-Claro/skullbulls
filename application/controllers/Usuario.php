<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Página de gerenciamento de usuarios
 * @version 1.0
 * @access public
 * @package usuarios
 */
class Usuario extends MY_Controller
{

    /**
     * Cria um array para validar a pagina com os campos necessrios do formulario
     * @var array
     */
    private $valida = array(
        array('field' => 'nome', 'label' => 'Nome', 'rules' => 'required'),
        array('field' => 'email', 'label' => 'E-mail', 'rules' => 'trim|valid_email'),
        array('field' => 'senha', 'label' => 'Senha', 'rules' => 'trim'),
        array('field' => 'resenha', 'label' => 'Redigite a senha', 'rules' => 'trim|matches[resenha]'),
        array('field' => 'telefone', 'label' => 'Telefone', 'rules' => 'trim'),
        array('field' => 'data_cadastro', 'label' => 'Data Cadastro', 'rules' => 'trim'),
        array('field' => 'empresa', 'label' => 'Empresa', 'rules' => 'trim'),
        //array( 'field'   => 'cargos[]',            'label'   => 'Cargo', 		'rules'   => 'required|trim'),
        array('field' => 'observacao', 'label' => 'Observação', 'rules' => 'trim'),
        array('field' => 'ativo', 'label' => 'Ativo', 'rules' => 'trim'),
    );

    /**
     * Verificador se é um usuario de uma empresa que está realizando o acesso
     */
    private $empresa = FALSE;

    /**
     * Constroi a classe e carrega valores de extends
     * e carrega models padrao para esta classe
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios_model');
    }

    /**
     * seta a classe listar
     * @version 1.0
     * @access public
     */
    public function index()
    {
        $this->listar();
    }

    /**
     * Cria a listagem de canais carregando inicia filtros, itens, total itens,
     * inicia listagem, definir URL da pagina, chama o usuario_model que vai 
     * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
     * carrega arquivos js e css opcionais
     * @param string $coluna - coluna de ordenação do banco de dados
     * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
     * @param string $off_set - pagina que esta visualizando
     * @version 1.0
     * @access public
     */
    public function listar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
    {
        $i = 'usuario';
        $acesso = $this->set_setor_usuario($i);
        $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
        $classe = strtolower(__CLASS__);
        $function = strtolower(__FUNCTION__);
        $url = base_url() . $classe . '/' . $function . '/' . $coluna . '/' . $ordem;
        $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
        $filtro = $this->_inicia_filtros($url, $valores);
        $filtro_ = $filtro->get_filtro();
        if ($this->session->userdata('id_empresa') != NULL)
        {
            $filtro_[] = array('tipo' => 'where', 'campo' => 'id_empresa', 'valor' => $this->session->userdata('id_empresa'));
            $this->empresa = TRUE;
        }
        $itens = $this->usuarios_model->get_itens($filtro_, $coluna, $ordem, $off_set);
        $total = $this->usuarios_model->get_total_itens($filtro_);
        $get_url = $filtro->get_url();
        $url = $url . ( (empty($get_url) ) ? '?' : $get_url );
        $data['paginacao'] = $this->init_paginacao($total, $url);
        $data['filtro'] = $filtro->get_html();
        $extras['url'] = base_url() . $classe . '/' . $function . '/[col]/[ordem]/' . $filtro->get_url();
        $extras['col'] = $coluna;
        $extras['ordem'] = $ordem;
        $data['listagem'] = $this->_inicia_listagem($itens, $extras);
        $this->layout
                ->set_classe($classe)
                ->set_function($function)
                ->set_include('js/listar.js', TRUE)
                ->set_include('js/usuario.js', TRUE)
//                ->set_include('js/upload2/funcs.js', TRUE)
                ->set_include('css/estilo.css', TRUE)
                ->set_include('metronic/pages/css/profile.min.css', TRUE)
                ->set_include('metronic/global/plugins/bootstrap-select/js/bootstrap-select.min.js', TRUE)
                ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.multi-select.js', TRUE)
                ->set_include('metronic/global/plugins/bootstrap-select/css/bootstrap-select.min.css', TRUE)
                ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.quicksearch.js', TRUE)
                ->set_include('metronic/global/plugins/jquery-multi-select/css/multi-select.css', TRUE)
                ->set_include('metronic/global/plugins/select2/js/select2.full.min.js', TRUE)
                ->set_include('metronic/global/plugins/select2/css/select2-bootstrap.min.css', TRUE)
                ->set_include('metronic/global/plugins/select2/css/select2.min.css', TRUE)
                ->set_breadscrumbs('Painel', 'painel', 0)
                ->set_breadscrumbs('Usuários', 'usuario', 1)
                ->set_usuario()
                ->set_menu($this->get_menu($classe, $function))
                ->view('listar', $data);
    }

    /**
     * Exportar uma lista usuarios para um arquivo excel
     * @version 1.0
     * @access public
     */
    public function exportar()
    {
        $url = base_url() . strtolower(__CLASS__) . '/' . __FUNCTION__;
        $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
        $filtro = $this->_inicia_filtros($url, $valores);
        $data = $this->usuarios_model->get_itens($filtro->get_filtro());
        exporta_excel($data, __CLASS__ . date('YmdHi'));
    }

    /**
     * Monta o formulario em branco e adiciona os campos de valida no banco de dados com suas validações
     * @version 1.0
     * @access public
     * @return void - redireciona ou monta o formulario
     */
    public function adicionar($id_empresa = NULL)
    {
        $i = 'usuario';
        $acesso = $this->set_setor_usuario($i);
        if ($this->input->is_ajax_request())
        {
            $retorno = array();
            $data = $this->_post();
            if (!isset($data['data_cadastro']))
            {
                $data['data_cadastro'] = date('Y-m-d H:i:s');
            }
            if (!isset($data['senha']))
            {
                $data['senha'] = md5(substr(time(), 5));
            }
            $id = $this->usuarios_model->adicionar($data);
            if ($id)
            {
                $dados['id'] = $id;
                $dados['nome'] = $data['nome'];
                $dados['email'] = $data['email'];
                $this->envia_email_novo_usuario($dados);

                $retorno['status'] = TRUE;
                $retorno['mensagem'] = $id;
            }
            else
            {
                $retorno['status'] = FALSE;
                $retorno['mensagem'] = 'Erro ao adicionar o usuario, tente novamente mais tarde.';
            }
            echo json_encode($retorno);
        } else
        {
            $this->form_validation->set_rules($this->valida);
            if ($this->form_validation->run())
            {
                $data = $this->_post();
                if (isset($data['sel']))
                {
                    $sel = $data['sel'];
                    unset($data['sel']);
                } else
                {
                    $sel = array();
                }

                if (isset($data['cargos']) && $data['cargos'])
                {
                    $cargos = $data['cargos'];
                    unset($data['cargos']);
                }

                $id = $this->usuarios_model->adicionar($data);

                if (isset($cargos) && $cargos)
                {
                    foreach ($cargos as $cargo)
                    {
                        $cargo = array('id_usuario' => $id, 'id_pow_cargos' => $cargo);
                        $this->usuarios_model->adicionar_has_cargos($cargo);
                    }
                }

                foreach ($sel as $s)
                {
                    $d = array('id_usuario' => $id, 'id_setor' => $s);
                    $this->usuarios_model->adicionar_has($d);
                }

                redirect(strtolower(__CLASS__) . '/editar/' . $id . '/1');
            } else
            {
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $data = $this->_inicia_select();
                $data['ckeditor_observacao'] = $this->inicia_ckeditor('observacao');
                $data['action'] = base_url() . $class . '/' . $function;
                $data['tipo'] = 'Usuário Adicionar';
                $data['edita'] = $acesso;
                $this->layout
                        ->set_function($function)
                        ->set_include('js/usuario.js', TRUE)
                        ->set_include('js/add_usuario.js', TRUE)
                        ->set_include('js/upload2/funcs.js', TRUE)
                        ->set_include('metronic/global/plugins/bootstrap-select/js/bootstrap-select.min.js', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.multi-select.js', TRUE)
                        ->set_include('metronic/global/plugins/bootstrap-select/css/bootstrap-select.min.css', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.quicksearch.js', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/css/multi-select.css', TRUE)
                        ->set_include('metronic/global/plugins/select2/js/select2.full.min.js', TRUE)
                        ->set_include('metronic/global/plugins/select2/css/select2-bootstrap.min.css', TRUE)
                        ->set_include('metronic/global/plugins/select2/css/select2.min.css', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel', 0)
                        ->set_breadscrumbs('Usuários', 'usuario', 0)
                        ->set_breadscrumbs('Adicionar', 'usuario/adicionar', 1)
                        ->set_usuario($this->set_usuario())
                        ->set_menu($this->get_menu($class, $function))
                        ->view('add_novo_usuario', $data);
            }
        }
    }

    /**
     * Monta o formulario ou edita as informações do perfil com base na this->valida 
     * @param bool $ok - verifica se os dados foram salvos 
     * @version 1.0
     * @access public
     */
    public function edita_perfil($ok = FALSE)
    {
        $codigo = $this->sessao['id'];
        $dados = $this->usuarios_model->get_item($codigo);
        if ($dados)
        {
            $this->form_validation->set_rules($this->valida);
            if ($this->form_validation->run())
            {
                $data = $this->_post();
                $data['ativo'] = 1;
                unset($data['sel']);
                unset($data['image']);

                $this->usuarios_model->excluir_has_cargos('usuarios_has_cargos.id_usuario = ' . $codigo);
                if (isset($data['cargos']) && $data['cargos'])
                {
                    $cargos = $data['cargos'];
                    $data_cargo['id_usuario'] = $codigo;
                    foreach ($cargos as $cargo)
                    {
                        $data_cargo['id_pow_cargos'] = $cargo;
                        $this->usuarios_model->adicionar_has_cargos($data_cargo);
                    }
                    unset($data['cargos']);
                }

                $id = $this->usuarios_model->editar($data, array('usuarios.id' => $codigo));
                redirect(strtolower(__CLASS__) . '/edita_perfil/1');
            } else
            {
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $data = $this->_inicia_select($codigo);
                $data['action'] = base_url() . $class . '/' . $function . '/' . $codigo;
                $data['action_novo'] = base_url() . $class . '/adicionar/';
                $data['tipo'] = 'Usuário Editar'; //$data = $this->_init_selects();
                $data['item'] = $dados;
                $data['mostra_id'] = TRUE;
                $data['self'] = true;
                $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                $data['edita'] = array('edita' => 1);
                $this->layout
                        ->set_function($function)
                        ->set_include('js/usuario.js', TRUE)
                        ->set_include('js/upload2/funcs.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('metronic/pages/css/profile.min.css', TRUE)
                        ->set_include('metronic/global/plugins/bootstrap-select/js/bootstrap-select.min.js', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.multi-select.js', TRUE)
                        ->set_include('metronic/global/plugins/bootstrap-select/css/bootstrap-select.min.css', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.quicksearch.js', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/css/multi-select.css', TRUE)
                        ->set_include('metronic/global/plugins/select2/js/select2.full.min.js', TRUE)
                        ->set_include('metronic/global/plugins/select2/css/select2-bootstrap.min.css', TRUE)
                        ->set_include('metronic/global/plugins/select2/css/select2.min.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel', 0)
                        ->set_transparente()
                        ->set_breadscrumbs('Usuários', 'usuario', 0)
                        ->set_breadscrumbs('Editar', 'usuario/editar/' . $codigo, 1)
                        ->set_usuario($this->set_usuario())
                        ->set_menu($this->get_menu($class, $function))
                        ->view('add_usuario', $data);
            }
        } else
        {
            redirect('painel/');
        }
    }

    /**
     * Monta o formulario ou edita as informações com base na this->valida 
     * @param string $codigo
     * @param bool $ok - verifica se os dados foram salvos 
     * @version 1.0
     * @access public
     */
    public function editar($codigo = NULL, $ok = FALSE)
    {
        $i = 'usuario';
        $acesso = $this->set_setor_usuario($i);
        $dados = $this->usuarios_model->get_item($codigo);
        if ($dados)
        {
            $this->form_validation->set_rules($this->valida);
            if ($this->form_validation->run())
            {
                $data = $this->_post();
                $this->usuarios_model->excluir_has_cargos('usuarios_has_cargos.id_usuario = ' . $codigo);
                if (isset($data['cargos']) && $data['cargos'])
                {
                    $cargos = $data['cargos'];
                    $data_cargo['id_usuario'] = $codigo;
                    foreach ($cargos as $cargo)
                    {
                        $data_cargo['id_pow_cargos'] = $cargo;
                        $this->usuarios_model->adicionar_has_cargos($data_cargo);
                    }
                    unset($data['cargos']);
                }
                if (isset($data['sel']))
                {
                    unset($data['sel']);
                }
                if (isset($data['image']))
                {
                    unset($data['image']);
                }
                if (isset($data['setores']))
                {
                    unset($data['setores']);
                }
                $id = $this->usuarios_model->editar($data, array('usuarios.id' => $codigo));
                redirect(strtolower(__CLASS__) . '/editar/' . $codigo . '/1');
            } else
            {
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $data = $this->_inicia_select($codigo);
                $data['ckeditor_observacao'] = $this->inicia_ckeditor('observacao');
                $data['action'] = base_url() . $class . '/' . $function . '/' . $codigo;
                $data['action_novo'] = base_url() . $class . '/adicionar/';
                $data['tipo'] = 'Usuário Editar'; //$data = $this->_init_selects();
                $data['item'] = $dados;
                $data['mostra_id'] = TRUE;
                $data['edita'] = $acesso;
                $data['editavel'] = $acesso['edita'];
                $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                $this->layout
                        ->set_function($function)
                        ->set_include('metronic/pages/css/profile.min.css', TRUE)
                        ->set_include('js/upload2/funcs.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('metronic/global/plugins/bootstrap-select/js/bootstrap-select.min.js', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.multi-select.js', TRUE)
                        ->set_include('metronic/global/plugins/bootstrap-select/css/bootstrap-select.min.css', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/js/jquery.quicksearch.js', TRUE)
                        ->set_include('metronic/global/plugins/jquery-multi-select/css/multi-select.css', TRUE)
                        ->set_include('metronic/global/plugins/select2/js/select2.full.min.js', TRUE)
                        ->set_include('metronic/global/plugins/select2/css/select2-bootstrap.min.css', TRUE)
                        ->set_include('metronic/global/plugins/select2/css/select2.min.css', TRUE)
                        ->set_include('js/usuario.js', TRUE)
                        ->set_breadscrumbs('Painel', 'painel', 0)
                        ->set_breadscrumbs('Usuários', 'usuario', 0)
                        ->set_breadscrumbs($dados->nome, 'usuario', 1)
                        ->set_transparente()
                        //->set_breadscrumbs('Editar', 'usuario/editar/'.$codigo, 1)
                        ->set_usuario($this->set_usuario())
                        ->set_menu($this->get_menu($class, $function))
                        ->view('add_usuario', $data);
            }
        } else
        {
            redirect('usuario/listar');
        }
    }

    public function monta_cronograma($id_usuario)
    {
        $this->load->model(array('tarefas_model'));
        $data['datas'] = $this->tarefas_model->get_maior_menor_data_por_usuario($id_usuario);
        //var_dump($data['datas']);
        $filtro = 'usuarios.id = ' . $id_usuario . ' AND tarefas.id_tarefas_status = 1';
        $tarefas = $this->tarefas_model->get_itens($filtro, 'tarefas.data_inicio', 'ASC');
        if (isset($tarefas) && $tarefas['qtde'] > 0)
        {
            foreach ($tarefas['itens'] as $tarefa)
            {
                $data['tarefas'][$tarefa->id]['item'] = $tarefa;
                $data['tarefas'][$tarefa->id]['horas_trabalhado'] = get_tempo($this->tarefas_model->get_tempo_trabalhado($tarefa->id));
            }
            $this->load->library('cronograma');
            $retorno['cronograma'] = $this->cronograma->get_html($data);
            $retorno['status'] = TRUE;
        } else
        {
            $retorno['mensagem'] = 'Nenhum tarefa registrada.';
        }
        //echo $retorno['cronograma'];
        echo json_encode($retorno);
    }

    /**
     * 
     * @param bool $id
     * @return array
     * @version 1.0
     * @access private
     */
    private function _inicia_select($id = FALSE)
    {
        $this->load->model(array('setores_model', 'empresas_model', 'pow_cargos_model', 'images_model'));
        $retorno['empresas'] = $this->empresas_model->get_select();

        $retorno['cargos'] = $this->pow_cargos_model->get_select();
        if ($id)
        {
            $usuario = $this->usuarios_model->get_item($id);
            $retorno['cargos_selecionados'] = $this->usuarios_model->get_selected_cargos('usuarios.id = ' . $id);
            $selecionados = $this->usuarios_model->get_item_has($id);
            if (isset($selecionados))
            {
                foreach ($selecionados as $selecionado)
                {
                    $retorno['selecionados'][$selecionado->id] = $selecionado->edita;
                }
            }
            $retorno['setores_selecionados'] = $selecionados;
        }
        if((isset($usuario->id_empresa) && $usuario->id_empresa))
        {
            if ($this->session->userdata('id_empresa') != NULL )
            {
                $retorno['setores'] = $this->setores_model->get_select_has_usuario($this->get_id_usuario().' AND setores.empresa = 1');
            } 
            else
            {
                $retorno['setores'] = $this->setores_model->get_select(' setores.empresa = 1');
            }
        }
        else
        {
            $retorno['setores'] = $this->setores_model->get_select();
        }
        
        if(isset($usuario->id_empresa))
        {
            $filtro_[] = 'image_arquivo.id_empresa = '.$usuario->id_empresa;
            $retorno['image_arquivo'] = $this->images_model->get_total_images($filtro_);
        }
        
        return $retorno;
    }

    /**
     * Deleta um usuario e suas conexões
     * @param string $id
     * @version 1.0
     * @access public
     */
    public function remover($id = NULL)
    {
        $selecionados = $this->input->post('selecionados');
        $this->usuarios_model->excluir_has('usuarios_setores.id_usuario in (' . implode(',', $selecionados) . ')');
        $this->usuarios_model->excluir_has_cargos('usuarios_has_cargos.id_usuario in (' . implode(',', $selecionados) . ')');
        $quantidade = $this->usuarios_model->excluir('usuarios.id in (' . implode(',', $selecionados) . ')');
        if ($quantidade > 0)
        {
            print $quantidade . ' itens foram apagados.';
        } else
        {
            print 'Nenhum item apagado.';
        }
    }

    /**
     * Cria uma lista de canais no estilo listagem normal,
     * chama os campos necessarios para criar o cabeçalho e 
     * define id como chave
     * @param array $itens
     * @param array $extras
     * @param bool $exportar - se falso cabeçalho fica vazio
     * @return array $retorno - instancia com a classe listagem
     * @version 1.0
     * @access private
     */
    private function _inicia_listagem($itens, $extras = NULL, $exportar = FALSE)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($exportar)
        {
            $cabecalho = ' ';
        } else
        {
            $data['cabecalho'] = array(
                (object) array('chave' => 'foto', 'titulo' => 'Foto'),
                (object) array('chave' => 'id', 'titulo' => 'ID', 'link' => str_replace(array('[col]', '[ordem]'), array('id', ( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' )), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight' . ( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),
                (object) array('chave' => 'nome', 'titulo' => 'Nome', 'link' => str_replace(array('[col]', '[ordem]'), array('nome', ( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' )), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight' . ( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),
                (object) array('chave' => 'cargo', 'titulo' => 'Cargo', 'link' => str_replace(array('[col]', '[ordem]'), array('cargo', ( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cargo') ? 'DESC' : 'ASC' )), $extras['url']), 'class' => ( ($extras['col'] == 'cargo' ) ? 'ui-state-highlight' . ( ($extras['col'] == 'cargo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),
                (object) array('chave' => 'email', 'titulo' => 'E-mail', 'link' => str_replace(array('[col]', '[ordem]'), array('email', ( ($extras['ordem'] == 'ASC' && $extras['col'] == 'email') ? 'DESC' : 'ASC' )), $extras['url']), 'class' => ( ($extras['col'] == 'email' ) ? 'ui-state-highlight' . ( ($extras['col'] == 'email' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),
                (object) array('chave' => 'ativo', 'titulo' => 'Ativo', 'link' => str_replace(array('[col]', '[ordem]'), array('ativo', ( ($extras['ordem'] == 'ASC' && $extras['col'] == 'ativo') ? 'DESC' : 'ASC' )), $extras['url']), 'class' => ( ($extras['col'] == 'ativo' ) ? 'ui-state-highlight' . ( ($extras['col'] == 'ativo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),
            );

            $data['operacoes'] = array(
                (object) array('titulo' => 'Editar', 'class' => 'btn btn-success', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
            );
            if (!$this->empresa)
            {
                if (LOCALHOST || $ip === IP_POW)
                {
                    $data['operacoes'][] = (object) array('titulo' => 'Forçar acesso', 'class' => 'btn btn-danger', 'icone' => '<span class="glyphicon glyphicon-sunglasses"></span>', 'link' => 'login/trocar/[id]', 'extra' => '');
                }
                $data['cabecalho'][] = (object) array('chave' => 'empresa', 'titulo' => 'Empresa', 'link' => str_replace(array('[col]', '[ordem]'), array('empresa', ( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' )), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight' . ( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ));
                $data['cabecalho'][] = (object) array('chave' => 'setores', 'titulo' => 'Setores', 'link' => str_replace(array('[col]', '[ordem]'), array('setores', ( ($extras['ordem'] == 'ASC' && $extras['col'] == 'setores') ? 'DESC' : 'ASC' )), $extras['url']), 'class' => ( ($extras['col'] == 'setores' ) ? 'ui-state-highlight' . ( ($extras['col'] == 'setores' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ));
            }
            $data['chave'] = 'id';
            $data['itens'] = $itens['itens'];
            $data['extras'] = $extras;
            $this->listagem->inicia($data);
            $retorno = $this->listagem->get_html();
        }
        return $retorno;
    }

    /**
     * Cria um filtro por id, nome e email,
     * cria botões de adicionar, exportar e deletar selecionados
     * @param string $url
     * @param array $valores
     * @return array $filtro - instancia com classe filtro
     * @version 1.0
     * @access private
     */
    private function _inicia_filtros($url = '', $valores = array())
    {
        $config['itens'] = array(
            array('name' => 'id', 'titulo' => 'ID: ', 'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'usuarios.id', 'valor' => '')),
            array('name' => 'nome', 'titulo' => 'Nome: ', 'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array('tipo' => 'like', 'campo' => 'usuarios.nome', 'valor' => '')),
            array('name' => 'email', 'titulo' => 'E-mail: ', 'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array('tipo' => 'like', 'campo' => 'usuarios.email', 'valor' => '')),
        );
        
        if(!isset($this->sessao['id_empresa']))
        {
            $config['itens'][] = array('name' => 'id_empresa', 'titulo' => 'Id da empresa: ', 'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array('tipo' => 'where', 'campo' => 'usuarios.id_empresa', 'valor' => ''));
        }
        $config['colunas'] = 3;
        $config['extras'] = '';
        $config['url'] = $url;
        $config['valores'] = $valores;
        $config['botoes'] = ' <a href="' . base_url() . strtolower(__CLASS__) . '/adicionar' . '" class="btn btn-primary">Add Novo</a>';
        $config['botoes'] .= ' <a href="' . base_url() . strtolower(__CLASS__) . '/exportar[filtro]' . '" class="btn btn-default">Exportar</a>';
        //$config['botoes'] .= ' <a class="btn  btn-info editar">Editar Selecionados</a>';
        $config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';

        $filtro = $this->filtro->inicia($config);
        return $filtro;
    }

    /**
     * Mostra se esta ativo ou inativo
     * @return array $retorno
     * @version 1.0
     * @access private
     */
    private function _get_ativo()
    {
        $retorno = array(
            (object) array('id' => '0', 'descricao' => 'Inativo'),
            (object) array('id' => '1', 'descricao' => 'Ativo'),
        );
        return $retorno;
    }

    /**
     * Mostra se usuario é admin ou vendedor
     * @return array $retorno
     */
    private function _get_tipo()
    {
        $retorno = array(
            (object) array('id' => 'ADM', 'descricao' => 'Administrador'),
            (object) array('id' => 'NOR', 'descricao' => 'Vendedor')
        );
        return $retorno;
    }

    public function set_select()
    {
        $filtro = 'ativo = 1';
        $retorno = $this->usuarios_model->get_select($filtro);
        echo json_encode($retorno);
    }

    public function has_setores()
    {
        $post = $this->input->post(NULL, TRUE);
        if (isset($post))
        {
            if (isset($post['id_usuario']) && empty($post['id_usuario']))
            {
                $post['id_usuario'] = $this->sessao['id'];
            }
            $add = $post;
//                $add['edita'] = (isset($post['edita']) && $post['edita'] ? 1 : 0);
            if (isset($post['edita']))
            {
                unset($post['edita']);
            }
            $this->usuarios_model->excluir_has($post);
            $this->usuarios_model->adicionar_has($add);
            $retorno['erro']['status'] = FALSE;
            $retorno['erro']['message'] = 'Adicionado com sucesso.';
        } else
        {
            $retorno['erro']['status'] = TRUE;
            $retorno['erro']['message'] = 'Não foi possivel alterar setor.';
        }
        echo json_encode($retorno);
    }

    public function has_cargos()
    {
        $post = $this->input->post(NULL, TRUE);
        if (isset($post))
        {
            $add = $post;
            unset($add['add']);
            if ($post['add'])
            {
                $this->usuarios_model->adicionar_has_cargos($add);
                $retorno['erro']['message'] = 'Cargo adicionado com sucesso.';
            } else
            {
                $this->usuarios_model->excluir_has_cargos($post);
                $retorno['erro']['message'] = 'Cargo removido com sucesso.';
            }
            $retorno['erro']['status'] = FALSE;
        } else
        {
            $retorno['erro']['status'] = TRUE;
            $retorno['erro']['message'] = 'Não foi possivel alterar setor.';
        }
        echo json_encode($retorno);
    }

    public function deleta_has_setores()
    {
        $post = $this->input->post(NULL, TRUE);
        if (isset($post))
        {
            $this->usuarios_model->excluir_has($post);
            $retorno['erro']['status'] = FALSE;
            $retorno['erro']['message'] = 'excluido com sucesso.';
        } else
        {
            $retorno['erro']['status'] = TRUE;
            $retorno['erro']['message'] = 'Não foi possivel alterar setor.';
        }
        echo json_encode($retorno);
    }

    public function get_setores()
    {
        $post = $this->input->post('id', TRUE);
        $dados = $this->_inicia_select($post);
        echo json_encode($dados['setores']);
    }
    public function get_setores_selecionados()
    {
        $post = $this->input->post('id', TRUE);
        $dados = $this->_inicia_select($post);
        echo json_encode($dados['setores_selecionados']);
    }

    /**
     * request o post do formulario para ser usado no editar e adicionar,
     * trata valores de checkbox
     * @return array $data - com todos os campos setados no formulario
     * @version 1.0
     * @access private
     */
    private function _post()
    {
        $data = $this->input->post(NULL, TRUE);
        if (!isset($data['ativo']))
        {
            $data['ativo'] = 0;
        }

        if (empty($data['senha']))
        {
            unset($data['senha']);
        } else
        {
            $data['senha'] = md5($data['senha']);
        }
        if (empty($data['resenha']))
        {
            unset($data['resenha']);
        } else
        {
            unset($data['resenha']);
        }
        return $data;
    }

    public function deleta_temporario()
    {
        $post = $this->_post();
        $arquivo = CWD_IMAGE . '/images/usuarios/' . $post['arquivo'];
        $data['foto'] = NULL;
        $this->usuarios_model->editar($data, 'id = ' . $this->sessao['id']);
        if (file_exists($arquivo))
        {
            unlink($arquivo);
            $retorno['erro'] = FALSE;
            $retorno['id'] = $post['sequencia'];
        } else
        {
            $retorno['erro'] = TRUE;
            $retorno['mensagem'] = 'Esta imagem já foi removida, restaure sua pagina.';
        }
        echo json_encode($retorno);
    }

    public function adiciona_foto($id_user = NULL)
    {
        $post = $this->_post();
        if (isset($post['arquivo']) && !empty($post['arquivo']))
        {
            $id_usuario = (isset($id_user) ? $id_user : $this->sessao['id']);
            $verifica_imagem = $this->usuarios_model->get_item($id_usuario);
            if ($verifica_imagem->foto != NULL)
            {
                $foto_antiga = CWD_IMAGE . '/images/usuarios/' . $verifica_imagem->foto;
                unlink($foto_antiga);
            }
            $data['foto'] = $post['arquivo'];
            $this->usuarios_model->editar($data, 'id = ' . $id_usuario);
            $retorno['erro'] = FALSE;
            $retorno['mensagem'] = 'Foto alterada com sucesso';
        } else
        {
            $retorno['erro'] = TRUE;
            $retorno['mensagem'] = 'Tente novamente mais tarde';
        }
        echo json_encode($retorno);
    }

    /**
     * 
     * @param type $dados
     * [nome]
     * [email]
     * [id]
     * 
     */
    public function envia_email_novo_usuario($dados = array())
    {
        $this->load->library('encryption');
        $this->encryption->initialize(array('driver' => 'openssl'));
        $encrypt = $dados['email'] . '-' . $dados['id'];

        $hash = urlencode($this->encryption->encrypt($encrypt));

        $data['url'] = base_url() . 'login/novo/?h=' . $hash;
        $data['nome'] = $dados['nome'];

        $email['assunto'] = 'POW: Usuário do painel';
        $email['mensagem'] = $this->layout->view('email_novo_usuario', $data, 'layout/sem_head', TRUE);
        $email['retorno'] = TRUE;
        $email['to'] = $dados['email'];
        return $this->envio($email);
    }

    public function set_usuario_empresa()
    {
        $dados = $this->input->post(NULL, TRUE);
        $retorno = array();
        if ((isset($dados['nome']) && !empty($dados['nome'])) && (isset($dados['email']) && !empty($dados['email'])) && (isset($dados['id_empresa']) && !empty($dados['id_empresa'])))
        {

            $dados['ativo'] = 0;
            $dados['principal'] = 1;
            $adiciona = $this->usuarios_model->adicionar($dados);
            if ($adiciona)
            {
                $retorno['adicionar'] = $adiciona;
                $dados['id'] = $adiciona;
                $status = $this->envia_email_novo_usuario($dados);
                if ($status['status'])
                {
                    $retorno['status'] = TRUE;
                    $retorno['titulo'] = 'Usuário inserido com sucesso';
                    $retorno['mensagem'] = 'Foi enviado um e-mail para <b>' . $dados['email'] . '</b> com os dados anteriormente mostrados '
                            . 'e com o passo a passo de como entrar no sistema.';
                } else
                {
                    $retorno['status'] = TRUE;
                    $retorno['titulo'] = 'Usuário inserido porém e-mail não enviado';
                    $retorno['mensagem'] = 'Cadastro realizado com sucesso, porém o e-mail não foi enviado, detalhes na linha a seguir'
                            . '<hr>'
                            . (isset($status['debugger']) ? $status['debugger'] : '') . '<hr>';
                }
            }
        } else
        {
            $retorno['status'] = FALSE;
            $retorno['titulo'] = 'Ocorreu um erro';
            $retorno['mensagem'] = 'Verifique os dados e tente novamente';
        }
        echo json_encode($retorno);
    }

    public function get_ramais()
    {
        echo json_encode($this->usuarios_model->get_itens('id_empresa = 0 AND usuarios.ativo = 1 AND ramal IS NOT NULL'));
    }

    public function set_ramal()
    {
        $ramal = $this->input->post('ramal', TRUE);
        if ($ramal)
        {
            $dados['ramal'] = $ramal;
            $filtro = 'id = ' . $this->sessao['id'];
            $altera = $this->usuarios_model->editar($dados, $filtro);
            if ($altera)
            {
                $retorno['status'] = TRUE;
                $retorno['mensagem'] = 'Ramal Alterado com sucesso';
                $this->sessao['ramal'] = $ramal;
            } else
            {
                if ($ramal === $this->sessao['ramal'])
                {
                    $retorno['status'] = TRUE;
                    $retorno['mensagem'] = 'Ramal alterado com sucesso.';
                } else
                {
                    $retorno['status'] = FALSE;
                    $retorno['mensagem'] = 'Ocorreu um erro, tente novamente.';
                }
            }
        } else
        {
            $retorno['status'] = FALSE;
            $retorno['mensagem'] = 'Falha na captura dos dados, tente novamente.';
        }
        echo json_encode($retorno);
    }
    
    public function get_select()
    {
        $q = $this->input->get('q',TRUE);
        $q = str_replace(' ', '%', $q);
        $filtro = array(
            array('tipo' => 'or_like','campo' => 'nome','valor' => $q,'unescape' => TRUE),
            array('tipo' => 'or_like','campo' => 'email','valor' => $q),
            array('tipo' => 'or_like','campo' => 'telefone','valor' => $q),
        );
        $retorno = $this->usuarios_model->get_select($filtro);
        echo json_encode($retorno);
    }

    
    
    public function convite_aluno(){
        $data = $this->input->post(NULL,TRUE);
        $data['id_empresa'] = $this->sessao['id_empresa'];
        $data['empresa'] = $this->sessao['id_empresa'];
        $data['senha'] = md5(substr($data['nome'], -3));
        $retorno = ['status'=>FALSE,'message'=>'Algum problema com o convite. Tente novamente. Erro CONV001'];
        $pesquisa = 'usuarios.email = "'.$data['email'].'" AND usuarios.id_empresa = '.$data['id_empresa'];
        $tem = $this->usuarios_model->get_item_filtro($pesquisa);
        if ( isset($tem) )
        {
            $retorno = ['status'=>FALSE,'message'=>'Aluno já tem um cadastro no sistema, para sua empresa. Erro CONV002'];
        }
        else
        {
            $id = $this->usuarios_model->adicionar($data);
            if ( $id )
            {
                $cargo = array('id_usuario' => $id, 'id_pow_cargos' => 9);
                $this->usuarios_model->adicionar_has_cargos($cargo);
                $dados = $data;
                $dados['id'] = $id;
                $dados['empresa'] = $this->empresas_model->get_item_administrar($dados['id_empresa']);
                $r = $this->envia_email_novo_usuario_cursos($dados);
                $retorno = ['status'=>FALSE,'message'=>'Não foi possivel enviar o email de validação apra o usuário.'];
                if ( $r['status'] ){
                    $retorno = ['status'=>TRUE,'message'=>''];
                }
            }
        }
        echo json_encode($retorno);
    }
    
    public function envia_email_novo_usuario_cursos($dados = array())
    {
        $this->load->library('encryption');
        $this->encryption->initialize(array('driver' => 'openssl'));
        $encrypt = $dados['email'] . '-' . $dados['id'];

        $hash = urlencode($this->encryption->encrypt($encrypt));
        $data = $dados;
        $data['url'] = $dados['empresa']->empresa_dominio . '/sistema?login=novo&h=' . $hash;
        $data['nome'] = $dados['nome'];
        $data['iagente'] = TRUE;
        $email['assunto'] = $dados['empresa']->empresa_nome_fantasia.': Usuário do curso';
        $email['mensagem'] = $this->layout->view('clientes/'.$dados['id_empresa'].'/email_novo_usuario_empresa', $data, 'layout/sem_head', TRUE);
        $email['retorno'] = TRUE;
        $email['to'] = $dados['email'];
        return $this->envio($email);
    }
    
}
