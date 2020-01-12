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
class Login extends MY_Controller {
	/**
	 * 
	 * Variavel de verificação de formulário caso não html5
	 * @var valida_login
	 */
	private $valida_login = array(
                                        //array( 'field'   => 'email', 'label'   => 'E-mail', 'rules'   => 'required|valid_email'),
                                        array( 'field'   => 'email', 'label'   => 'E-mail', 'rules'   => 'required'),
                  			array( 'field'   => 'senha', 'label'   => 'Senha', 'rules'   => 'required|min_length[3]')
                                        );
	
        private $url;
        
	public function __construct()
	{
            parent::__construct(FALSE);
            $this->load->model('login_model');
            $this->load->model('usuarios_model');
            //$this->load->library('facebook_lib');
	}
	
	public function index()
	{
            if(isset($this->session->userdata['login']))
            {
                redirect($this->url.'painel');
            }
            else
            {
                $this->login();
            }
	}
	
	public function login($url = NULL)
	{
            $this->form_validation->set_rules($this->valida_login);
            if  ( $this->form_validation->run() ){
                $email = $this->input->post('email', TRUE);
                $senha = $this->input->post('senha', TRUE);
                $login_v = $this->login_model->verifica($email, $senha);
                if( $login_v ){
                    if ( $login_v->ativo == 1 ){
                        $sessao['id'] = $login_v->user;
                        $sessao['usuario'] = $login_v->nome;
                        $sessao['login'] = TRUE;
                        $sessao['usuario_origem'] = FALSE;
                        $this->session->set_userdata($sessao);	
                        redirect(base_url().urldecode($this->url),'refresh');

                    }else{
                        $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__.'/?u='.$this->url;
                        $data['erro'] = array('class' => 'text-warning', 'texto' => 'Usuário Inativo, Contate o administrador para liberação do acesso.');
                        $this->layout
                                ->set_titulo('Login')
                                ->set_keywords('')
                                ->set_description('')
                                ->set_include('metronic/layouts/layout4/css/login.min.css', TRUE)
                                ->set_include('js/login.js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->view('login', $data, 'layout/login'); 

                    }	
                }else{
                    
                    $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__.'/?u='.$this->url;
                    $data['erro'] = array( 'class' => 'alert alert-danger ', 'texto' => 'E-mail ou senha inválidos.');
                    $this->layout
                            ->set_titulo('Login')
                            ->set_keywords('')
                            ->set_description('')
                            ->set_include('metronic/layouts/layout4/css/login.min.css', TRUE)
                            ->set_include('js/login.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)
                            ->view('login', $data, 'layout/login'); 
                }
            }else{
                $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__.'/?u='.$this->url;
                $data['erro'] = array( 'class' => 'text-info', 'texto' => 'Preencha seus dados de acesso.<br/><br/>');
                $this->layout
                                ->set_titulo('Login')
                                ->set_keywords('')
                                ->set_description('')
                                ->set_include('metronic/layouts/layout4/css/login.min.css', TRUE)
                                ->set_include('js/login.js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->view('login', $data, 'layout/login'); 
            }
            
	}

        public function esqueceu(  ){
            $email = $this->input->post('email', TRUE);
            if ( isset($email) && $email ){
                $this->load->helper('email');
                if ( valid_email($email) ){
                    $user = $this->login_model->esqueceu_senha($email);
                    if ( $user && $user->ativo > 0 ){
                        $login = $user->email;
                        $senha = $this->_gera_senha();
                        $this->usuario_model->altera_senha($user->id, md5( $senha['bd'] ) );

                        $array_envio['assunto'] = 'Recuperar Senha';
                        $array_envio['from'] = 'contato@skullbulls.com';
                        $array_envio['to'] = $email;
                        
                        $mensagem = 'Olá, '.$user->nome.PHP_EOL;
                        $mensagem .= PHP_EOL.'O sistema gerou uma nova senha de acesso para você, Guarde-a em um lugar seguro.'.PHP_EOL;
                        $mensagem .= PHP_EOL.'Login: '.$user->email.PHP_EOL;
                        $mensagem .= 'Senha: '.$senha['user'].PHP_EOL;
                        $mensagem .= PHP_EOL.'Obrigado por usar nosso painel.'.PHP_EOL.'Att.'.PHP_EOL.'Administrador.';
                        $array_envio['mensagem'] = $mensagem;
                        $retorno = $this->envio($array_envio);
                        echo '<p class="text-success">Sua senha foi alterada e enviada para seu e-mail com sucesso.</p>';
                    }else{
                        echo '<p class="text-warning">Usuário inativo ou inexistente, contate o administrador.</p>';
                    }
                }else{
                    echo '<p class="text-error">E-mail Inválido!</p>';
                }
            }else{
                echo '<p class="text-warning">Preencha o campo e-mail!</p>';
            }
	}
	
	public function logout(){
            $url = $_GET['u'] ?? '';
            $this->session->unset_userdata('login');
            $this->session->unset_userdata('id');
            $this->session->unset_userdata('id_empresa');
            $this->session->unset_userdata('tipo');
            $this->session->unset_userdata('foto');
            $this->session->unset_userdata('usuario');
            $this->session->unset_userdata('corretor');
            //$this->facebook_lib->destroySession();
            redirect(base_url().'login/index/?u='.urlencode($url));
	}
        
}
