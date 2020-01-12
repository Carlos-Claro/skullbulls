<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use voku\helper\HtmlMin;
/**  
 * Classe responsável pela formação das principais partes do layout, meta, grupos e gerenciadores
 * @author Carlos Claro 
 * @package Admin
 *  
 */
class Layout {	
    /** 
     * instancia do CodeIgniter
     * @name CI	 
     * @access private	 
     */
    private $CI;		
    /**
     * Classe que esta chamando o layout, serve para definir o menu ativo
     * @var type string
     */
    private $classe = NULL;
    /**
     * Function principal que chama o layout, usado em menu e sub menu
     * @var type string
     */
    private $function = NULL;	
    /**
     * setado por função, carrega o titulo da pagina, melhoria de SEO
     * @var type string
     */
    private $titulo = NULL;		
    /**
     * seta as keywords do sistema
     * @var type array
     */
    private $keywords = array('');		
    /**
     * description da pagina, NULL by default	 	 
     * @var type string
     */
    private $description = '';
    /**
     * Separador para titulo ou outras situações que precisem de separador
     * @var type string
     */
    private $separador = ' - ';		
    /**
     * agrupa os includes da pagina, normalmente, defaults, jquery, bootstrap...
     * @var type array
     */
    private $file_includes = array();	
    /**
     * pode ser string ou array, guarda os dados do usuario da sessão para montar menus e afins
     * @var type string
     */
    private $usuario = '';
    /**
     * armazena o caminho de breadscrumbs
     * @var type array
     */
    private $breadscrumbs = NULL;
    /**
     * 
     */
    private $menu = NULL;
    
    /**
     * adiciona o menu lateral
     * @var type string
     */
    private $menu_lateral_direito = NULL;
    
    /**
     * adiciona o menu lateral
     * @var type string
     */
    private $menu_lateral_esquerdo = NULL;
    
    /*
     * Adiciona o banner topo
     * @var type BOOL
     */
    private $banner = NULL;
    
    /**
     *  Determina o fundo do conteudo
     * @var type BOOL
     */
    private $fundo_transparente = FALSE;
    private $meta = '';
    private $header = '';
    private $favicon = '';
    
    /**	 	 
     * Contrutor da Classe	 	 
     */	
    public function __construct() 		
    {
        $this->CI =& get_instance();		
        $this->set_includes_defaults();				
        $this->set_titulo(FALSE);	
    }	
 
    private function get_usuario_origem( )
    {
        $tem_origem = $this->CI->session->userdata('usuario_origem');
        if ( isset( $tem_origem ) && $tem_origem )
        {
            $retorno = '<a href="'.base_url().'login/voltar/" class="btn btn-danger pull-right">Você esta simulando um usuário no momento. Clique para voltar a origem</a>';
        }
        else
        {
            $retorno = NULL;
        }
        return $retorno;
    }
    
    /**
     * Seta as informações do Usuario do sistema
     * @param type $usuario default FALSE
     * @return \Layout
     */
    public function set_usuario( $usuario = FALSE )	
    {
        if ( $usuario )
        {
            $this->usuario = $usuario;
        }
        else
        {
            $this->usuario = $this->CI->sessao;
        }
        return $this;	
    }	
    /**
     * Carrega o usuario quando solicitado
     * @return type string
     */
    private function get_usuario()	
    {		
        return $this->usuario;	
        
    }
    /**
     * Seta a classe para a variavel
     * @param type $classe
     * @return \Layout
     */
    
    public function set_classe( $classe )	
    {		
        $this->classe = $classe;		
        return $this;	
    }	
    /**
     * carrega a classe
     * @return type string
     */
    private function get_menu()	
    {		
        return $this->menu;	
    }	
    /**
     * Seta a classe para a variavel
     * @param type $classe
     * @return \Layout
     */
    public function set_menu( $menu )	
    {		
        $this->menu = $menu;		
        return $this;	
    }
    /**
     * carrega a classe
     * @return type string
     */
    private function get_meta()	
    {		
        return $this->meta;	
    }	
    /**
     * Seta a classe para a variavel
     * @param type $classe
     * @return \Layout
     */
    public function set_meta( $meta )	
    {		
        $this->meta = $meta;		
        return $this;	
    }
    /**
     * carrega a classe
     * @return type string
     */
    private function get_header()	
    {		
        return $this->header;	
    }	
    /**
     * Seta a classe para a variavel
     * @param type $classe
     * @return \Layout
     */
    public function set_header( $header )	
    {		
        $this->header .= $header;		
        return $this;	
    }
    /**
     * carrega a classe
     * @return type string
     */
    private function get_favicon()	
    {		
        return $this->favicon;	
    }	
    /**
     * Seta a classe para a variavel
     * @param type $classe
     * @return \Layout
     */
    public function set_favicon( $favicon )	
    {		
        $this->favicon = $favicon;		
        return $this;	
    }
    /**
     * Seta o menu lateral esquerdo
     * @param type $tipo_do_menu
     * Pode ser um menu de categorias ou o menu normal
     * @return type \Layout
     */
    public function set_menu_lateral_esquerdo( $menu )
    {
        $this->menu_lateral_esquerdo= $menu;
        return $this;
    }
    /**
     * carrega o menu
     */
    private function get_menu_lateral_esquerdo()
    {
        return $this->menu_lateral_esquerdo;
    }
    /**
     * Seta o menu lateral
     * @param type $tipo_do_menu
     * Pode ser um menu de categorias ou o menu normal
     * @return type \Layout
     */
    public function set_menu_lateral_direito( $menu )
    {
        $this->menu_lateral_direito = $menu;
        return $this;
    }
    /**
     * carrega o menu
     */
    private function get_menu_lateral_direito()
    {
        return $this->menu_lateral_direito;
    }

    /**
     * carrega a classe
     * @return type string
     */
    private function get_classe()	
    {		
        return $this->classe;	
    }	
    /**
     * Seta a função para a variavel
     * @param type $function
     * @return \Layout
     */
    public function set_function( $function )	
    {		
        $this->function = $function;		
        return $this;	
    }	
    /**
     * carrega a funcao para a variavel
     * @return type string
     */
    private function get_function()	
    {		
        return $this->function;	
    }	
    /**
     * Seta o titulo da página
     * @param type $titulo
     * @return \Layout
     */
    public function set_titulo($titulo) 		
    {				
        if ($titulo) 				
        {						
            $this->titulo = $titulo . $this->separador .( ! HOTSITE_MANAGER  ? ' SkullBulls :.' : '');				
        }				
        else 				
        {						
            $this->titulo = ( ! HOTSITE_MANAGER  ? ' SkullBulls :.' : '');				
        }				
        return $this;		
    }
    /**
     * Carrega o titulo quando solicitado
     * @return type string
     */
    private function get_titulo() 		
    {				
        return $this->titulo;		
    }	
    /**
     * Seta os keywords para a variavel
     * @param type array $keywords
     * @return \Layout
     */
    public function set_keywords( $keywords )
    {				
        if (is_array($keywords))				
        {						
            $this->keywords = $keywords;				
        }					
        else				
        {						
            $this->keywords[] = $keywords;				
        }				
        return $this;		
        
    }
    /**
     * Carrega as keywords quando necessário
     * @deprecated since version 2015-09-15 função não ultilizada
     *  @return type string
     */
    private function get_keywords() 		
    {	/**			
        if (count($this->keywords) > 0)
        {						
            $this->keywords = implode(', ', $this->keywords);				
        }				
        return $this->keywords;	
     * 
     */	
    }	
    
    /**
     * Seta o banner no topo 
     * @return \Layout
     */
    public function set_banner() {
        $this->banner = TRUE;
        return $this;
    }
    
    /**
     * carrega o banner no topo 
     * @return bool
     */
    private function get_banner() {
        return $this->banner;
    }
    
    
    
    /**
     * Seta description da pagina para SEO
     * @param type $description
     * @return \Layout
     */
    public function set_description($description) 		
    {			
        $this->description  = $description;				
        return $this;		
    }	
    /**
     * carrega description quando solicitado
     * @return type string
     */
    private function get_description() 		
    {				
        return $this->description;		
    }		
    /**
     * Seta os includes usando a função set_include
     */
    private function set_includes_defaults() 		
    {				
        if ( ! LOCALHOST )
        {
            
        $this
                ->set_include('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all', FALSE);
        }
        $this
                ->set_include('metronic/global/plugins/font-awesome/css/font-awesome.min.css', TRUE, TRUE)
                ->set_include('metronic/global/plugins/simple-line-icons/simple-line-icons.min.css', TRUE, TRUE)
                ->set_include('metronic/global/plugins/bootstrap/css/bootstrap.min.css', TRUE, TRUE)
                //->set_include('metronic/global/plugins/gritter/css/jquery.gritter.css', TRUE)
                ->set_include('metronic/global/plugins/bootstrap-daterangepicker/daterangepicker.css', TRUE, TRUE)
                ->set_include('metronic/global/plugins/fullcalendar/fullcalendar.css', TRUE, TRUE)
                ->set_include('metronic/global/plugins/jqvmap/jqvmap/jqvmap.css', TRUE, TRUE)
                ->set_include('metronic/global/plugins/jqvmap/jqvmap/jqvmap.css', TRUE, TRUE)
                ->set_include('metronic/pages/css/tasks.css', TRUE, TRUE)
                ->set_include('metronic/global/css/components.min.css', TRUE, TRUE)
                
                ->set_include('metronic/layouts/layout4/css/layout.min.css', TRUE, TRUE)
                ->set_include('metronic/layouts/layout4/css/themes/default.min.css', TRUE, TRUE)
                ->set_include('metronic/layouts/layout4/css/custom.css', TRUE, TRUE)
                
                
//                ->set_include('js/jquery-3.1.1.min.js', TRUE)
//                ->set_include('js/jquery-migrate-3.0.0.js', TRUE)
//                ->set_include('js/jquery-1.10.2.min.js', TRUE)
                ->set_include('js/jquery-2.0.3.min.js', TRUE)
//                ->set_include('js/jquery-migrate-3.0.0.js', TRUE)
                ->set_include('js/bootstrap.min.js', TRUE)
                ->set_include('js/meiomask.js', TRUE)
                ->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE, TRUE)
                ->set_include('js/datetimepicker/moment.js', TRUE)
                ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
                ->set_include('js/funcs.js', TRUE)
//                ->set_include('js/chat.js', TRUE)
//                ->set_include('css/chat.css', TRUE)
                ->set_include('metronic/global/scripts/app.min.js', TRUE)
                ->set_include('metronic/global/plugins/js.cookie.min.js', TRUE)
                ->set_include('metronic/layouts/layout4/scripts/layout.js', TRUE)
//                ->set_include('metronic/layouts/global/scripts/quick-sidebar.js', TRUE)
                /**
                 * Include responsável pelas notificações
                 */
                ->set_include('metronic/pages/scripts/ui-toastr.min.js', TRUE)
                ->set_include('metronic/global/plugins/bootstrap-toastr/toastr.min.js', TRUE)
                ->set_include('metronic/global/plugins/bootstrap-toastr/toastr.min.css', TRUE, TRUE)
                /**
                 * Switch
                 */
                ->set_include('metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css', TRUE, TRUE)
                ->set_include('metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js', TRUE)
                /**
                 * Include dos sweetalerts
                 */
                ->set_include('metronic/global/plugins/bootstrap-sweetalert/sweetalert.css', TRUE, TRUE)
                ->set_include('metronic/global/plugins/bootstrap-sweetalert/sweetalert.min.js', TRUE)
                ->set_include('css/estilo.css', TRUE, TRUE)
                /**
                 * include do autocomplete
                 */
                ->set_include('metronic/global/plugins/jquery-ui/jquery-ui.min.js', TRUE)
                ->set_include('metronic/global/plugins/jquery-ui/jquery-ui.min.css', TRUE, TRUE);
                /*
                 * 
                //->set_include('js/jquery.maskedinput.js', TRUE)
                ->set_include('js/ckeditor/ckeditor.js', TRUE)
                ->set_include('js/ckeditor/config.js', TRUE)
                 * 
                 */			
    }
    
    public function unset_includes()
    {
        $this->file_includes = array();	
        return $this;
    }
    
    public function set_includes_minimo()
    {
        $this->file_includes = array();				
        $this
                ->set_include('js/jquery-2.0.3.min.js', TRUE)
                ->set_include('js/bootstrap.min.js', TRUE)
                ->set_include('js/funcs.js', TRUE)
                ->set_include('css/bootstrap.css', TRUE)
                ->set_include('css/estilo.css', TRUE);
        
    }
    
    /**
     * Seta os includes, verificando extensão e se entra no pacote, separa por extensão
     * @param string $path endereçamento do arquivo
     * @param type $prepend_base_url - se utiliza o endereço completo
     * @return \Layout
     */
    public function set_include($path, $prepend_base_url = TRUE, $replace = FALSE)		
    {				
        if ($prepend_base_url)				
        {						
            $path = base_url().$path;				
            
        }	
        if ( base_url() != 'https://admin.powempresas.com/' )
        {
            $path = str_replace(['https://admin.powempresas.com/', 'http://admin.powempresas.com/'],base_url(),$path);
        }
        $path_verifica = str_replace(base_url(),'', $path);
        if (preg_match('/js/', $path_verifica) )				
        {
            $this->file_includes['js'][] = $path.( $replace ? '?replace' : '');				
        }				
        elseif (preg_match('/css$/', $path_verifica))				
        {						
            $this->file_includes['css'][] = $path.( strstr($replace,'?') ? '&replace' : '?replace');				
        }				
        return $this;		
    
    }
    /**
     * monta os includes por tipo para colar no layout
     * @return string
     */
    public function get_includes()		
    {			
        $final_includes = '';				
        foreach ($this->file_includes['css'] as $include) 				
        {
            
                    $final_includes .= '<link rel="stylesheet" href="'.(strstr($include,'?') ? $include.'&' : $include.'?').'?t='.time().'" type="text/css" />'.PHP_EOL;				
        }				
        if ( isset($this->file_includes['js']) )
        {
            foreach ($this->file_includes['js'] as $include) 				
            {						
                if ( base_url() != 'https://admin.powempresas.com/' )
                {
                    $include = str_replace(['https://admin.powempresas.com/', 'http://admin.powempresas.com/'],base_url(),$include);
                }
                
                    $final_includes .= '<script type="text/javascript" src="'.(strstr($include,'?') ? $include.'&' : $include.'?').'t='.time().'"></script>'.PHP_EOL;				
            }				
        }
        return $final_includes;		
    }	
    
    private function set_url_ponto($url)
    {
        $a = explode('/', $url);
        array_pop($a);
        array_pop($a);
        $retorno = implode('/', $a).'/';
        return $retorno;
    }
    
    
    
    /**
     * monta os includes por tipo para colar no layout
     * @return string
     */
    public function get_includes_separado($incorpora = TRUE)		
    {				
        $final_includes = '';				
//        $final_includes['js'] = '';	
        foreach ($this->file_includes['css'] as $include) 				
        {
            if ( $incorpora && ! strstr($include,'fonts.googleapis') )
            {
                if ( strstr($include,'replace') )
                {
                    $e = explode('/',$include);
                    array_pop($e);
                    $a = implode('/', $e);
                    array_pop($e);
                    $b = implode('/',$e);
                    array_pop($e);
                    $c = implode('/',$e);
                    $file = file_get_contents(str_replace(['&replace','?replace',base_url()], '',$include));
                    $final_includes .= '<!-- arquivo.'.$include.' --><style>'.str_replace(['../../', '../', './'], [$c.'/',$b.'/',$a.'/'], $file).'</style>';
                }
                else
                {
                    $file = file_get_contents(str_replace(base_url(), '',$include));
                    $final_includes .= '<!-- '.$include.' --><style>'.$file.'</style>';
                }
            }
            else
            {
            $final_includes .= '<link rel="stylesheet" href="'.(strstr($include,'?') ? $include.'&' : $include.'?').'?t='.time().'" type="text/css" />'.PHP_EOL;				
            }

        }				
        if ( isset($this->file_includes['js']) )
        {
            foreach ($this->file_includes['js'] as $include) 				
            {
                if ( $incorpora )
                {
                    if ( strstr( $include,'replace') )
                    {
                        $e = explode('/',$include);
                        array_pop($e);
                        $a = implode('/', $e);
                        array_pop($e);
                        $b = implode('/',$e);
                        array_pop($e);
                        $c = implode('/',$e);
                        $file = file_get_contents(str_replace(['&replace','?replace',base_url()], '',$include));
                        $final_includes .= '<!-- '.$include.' --><script type="text/javascript">
                                            '.str_replace(['../../','../','./'], [$c.'/',$b.'/',$a.'/'], $file).'
                                            </script>';

                    }
                    else
                    {
                        $file = file_get_contents(str_replace(base_url(), '',$include));
                        $final_includes .= '<!-- '.$include.' --><script type="text/javascript">
                                            '.$file.'
                                            </script>';

                    }
                }
                else
                {
                    $final_includes .= '<script type="text/javascript" src="'.(strstr($include,'?') ? $include.'&' : $include.'?').'t='.time().'"></script>'.PHP_EOL;				
                }

            }				
        }
        
        return $final_includes;		
        
    }	
    /**
     * Monta breadscumbs 
     * @param type $title
     * @param string $url
     * @param type $active
     * @param type $prepend_base_url
     * @return \Layout
     */
    public function set_breadscrumbs($title, $url, $active = 0, $prepend_base_url = TRUE) 		
    {				
        if ($prepend_base_url)				
        {						
            $url = base_url().$url;				
        }				
        $this->breadscrumbs[] = (object) array('title'=>$title, 'url'=>$url, 'active'=>$active);
        return $this;		
    }	
    /**
     * monta e retorna o breadscrumbs do sistema
     * @return type string
     */
    private function get_breadscrumbs() 		
    {
        if ( isset( $this->breadscrumbs ) && count( $this->breadscrumbs ) > 0 )
        {
            $retorno = '<ul class="page-breadcrumb breadcrumb"><li>Onde estou: </li>';
            foreach ( $this->breadscrumbs as $breads )
            {
                if ( $breads->active == 1 )
                {
                    $retorno .= '<li class="active">'.$breads->title.'</li><span class="fa fa-circle"></span>';
                }
                else
                {
                    $retorno .= '<li class=""><a href="'.$breads->url.'">'.$breads->title.'</a></li><span class="fa fa-circle"></span>';
                }
            }
            $retorno .= '</ul>';
        }
        else
        {
            $retorno = '';
        }
        return $retorno;
    }	
    
    public function set_transparente()
    {
        $this->fundo_transparente = TRUE;
        return $this;
    }

    private function get_transparente()
    {
        return $this->fundo_transparente;
    }
//
//    public function mata_this()
//    {
//        unset($this);
//        $this->CI =& get_instance();		
//    }
    
    public function view($view_name, $params = array(), $layout = 'layout/layout', $retorno_html = FALSE)		
    {	
        if ( HOTSITE_MANAGER )
        {
            $htmlMin = new HtmlMin();
             $htmlMin->doOptimizeViaHtmlDomParser();
            $htmlMin->doOptimizeAttributes();   
            $htmlMin->doRemoveOmittedQuotes(FALSE); 
        }
        $view_content = $this->CI->load->view($view_name, $params, TRUE);	
        if ( $this->CI->input->is_ajax_request() && ! $retorno_html )
        {	
            if ( HOTSITE_MANAGER )    
            {
                print $htmlMin->minify($view_content);				
            }
            else
            {
                print $view_content;				
            }
        }				
        else				
        {	
            $parametros = array(											
                'conteudo'              => $view_content,
                'titulo'		=> $this->get_titulo(),
                'keywords' 		=> $this->get_keywords(),
                'description'           => $this->get_description(),
                'function' 		=> $this->get_function(),
                'classe'		=> $this->get_classe(),
                'usuario'               => $this->get_usuario(),
                'breadscrumbs'          => $this->get_breadscrumbs(),
                'menu'                  => $this->get_menu(),
                'menu_lateral_direito'  => $this->get_menu_lateral_direito(),
                'menu_lateral_esquerdo' => $this->get_menu_lateral_esquerdo(),
                'meta'                  => $this->get_meta(),
                'header'                => $this->get_header(),
                'favicon'               => $this->get_favicon(),
                'usuario_origem'        => $this->get_usuario_origem(),
                'conteudo_transparente' => $this->get_transparente(),
                );
//            if ( HOTSITE_MANAGER )
//            {
//                $parametros['includes'] = '[*include*]';
//                $parametros['includes_separado'] = '[*include_separado*]';
//            }
//            else
//            {
                $parametros['includes'] = $this->get_includes();
//                $parametros['includes_separado'] = $this->get_includes_separado(TRUE);
                
//            }
            if( $retorno_html )
            {
                $r =  $this->CI->load->view($layout, $parametros, $retorno_html);	
                if ( HOTSITE_MANAGER )
                {
                    return $htmlMin->minify($r);
                }
                else
                {
                    return $r;
                }
            }
            else 
            {
                if (HOTSITE_MANAGER)
                {
                    $r = $this->CI->load->view($layout, $parametros, TRUE);
                    $r = $htmlMin->minify($r);

//                    $includes = $this->get_includes();
//                    $includes_separado = $this->get_includes_separado(TRUE);
//                    $c = str_replace('[*include*]', $includes, $r);
//                    $c = str_replace('[*include_separado*]', $includes_separado, $c);

                    echo $r;
                    
                }
                else
                {
                    $this->CI->load->view($layout, $parametros);
                }
                
//                echo $htmlMin->minify($r);
            }
        }		
    }
    
}
                            