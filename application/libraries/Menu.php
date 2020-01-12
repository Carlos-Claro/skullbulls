<?php

class Menu 
{
    private $itens = array();
    //array( 'titulo', 'tipo', 'link' => array( 'href', 'base', 'title' ), 'class', 'itens' => array( 'titulo', 'link' => array( 'href', 'base', 'title' ), 'extra' )  );
    private $selecionado = array();
    //array( 'principal', 'secundario' )
    private $tipo = 'principal';
    //
    private $CI;
    
    private $id_menu = FALSE;
    
    private $id_empresa = FALSE;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model(array('hotsite_menu_link_model'));
    }

    public function inicia( $data = array() ) 
    {
        $this->itens = isset($data['itens']) ? $data['itens'] : array();
        $this->tipo = isset($data['tipo']) ? $data['tipo'] : 'principal';
        $this->selecionado = isset($data['selecionado']) ? $data['selecionado'] : array();
        $menu = $this->_set_menu();
        return $menu;
    }
    
    public function set( $data = array() )
    {
        $this->id_menu = $data['id_menu'];
        $this->id_empresa = $data['id_empresa'];
    }
    
    private function _set_menu ()
    {
        
        $menu = '<ul class="page-sidebar-menu page-sidebar-menu-closed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">'.PHP_EOL;
        $menu .= '<li>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler hidden-phone"></div>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
              </li>';
        foreach ( $this->itens as $item )
        {
            $drop = ( isset($item->itens) && count($item->itens) > 0 ) ? TRUE : FALSE; 
            $menu .= '<li class="nav-item ';
//            $menu .= $drop ? 'dropdown ' : '';
            $menu .= ( ( $this->selecionado['classe'] == $item->classe ) ) ? ' active ' : '';
            $menu .= '" >'.PHP_EOL;
            $menu .= '<a href="'. ( $item->classe == '#' ? '#' : $drop ? '#' : base_url().$item->classe ).'" ';
            $menu .= 'title="'.$item->titulo.'" ';
            $menu .= 'class="'.( isset($item->class) ? $item->class : '' ).' '.( $drop ? 'nav-toggle' : '' ).'" ';
//            $menu .= $drop ? ' data-toggle="dropdown" ' : '';
            $menu .= ' ><i id="'.$item->id.'" class="'.($item->icone != NULL ? $item->icone : 'icon-docs').'"></i><span class="title">'.$item->titulo.'</span>'.($drop ? '<b class="arrow"></b>' : '');
            $menu .= '</a>';
            if ( $drop )
            {
                $menu .= '<ul class="sub-menu" style="display:none">';
                foreach ( $item->itens as $itens )
                {
                    $menu .= '<li class=" nav-item start';
                    if ( ( $this->selecionado['classe'] == $item->classe ) && ( $this->selecionado['function'] == $itens->classe ) )
                    {
                        $menu .= 'active';
                    }
                    $menu .= '">'.PHP_EOL;
                    $menu .= '<a href="'. base_url().($item->classe == '#' ? '' : $item->classe.'/').$itens->classe .'">';
                    $menu .= $itens->titulo;
                    $menu .= '</a>'.PHP_EOL;
                    $menu .= '</li>'.PHP_EOL;
                }
                $menu .= '</ul>'.PHP_EOL;
            }
            $menu .= '</li>'.PHP_EOL;
                    
        }
        $menu .= '</ul>'.PHP_EOL;
        return $menu;
    }

    
    public function montar_menu_hotsite($data = array())
    {
        //var_dump($data);die();
        $pesquisa = $data['menu'];
        $retorno = '';
        if( isset($pesquisa) && $pesquisa )
        {
            $retorno  = '<div class="nav_link_menu_horizontal_automatico_'.$data['id_menu'].'" data-item="'.$data['id_menu'].'">';
            $retorno .= '<ul class="nav nav-pills">';
            foreach($pesquisa as $item)
            {
                $retorno .= '<li class="dropdown-li lis_menu li_link_menu_horizontal_automatico_'.$data['id_menu'].'" >';
                $retorno .= '<a class="a_menu a_link_menu_horizontal_automatico_'.$data['id_menu'].'" '.( ( (isset($item->subcategoria) && $item->subcategoria) || ( isset($item->especiais) ) ) ? '" data-toggle="dropdown" href="#" ' : ' href="'.( strstr($item->link,'http://') ? $item->link : base_url().$item->link ).'" ').'>';
                $retorno .= $item->titulo_link;
                $retorno .= '</a>';
                
                if ( isset($item->especiais) )
                {
                    $retorno .= '<ul class="dropdown-menu drops_'.$data['id_menu'].'" role="menu">';
                    foreach( $item->especiais as $chave_tipos => $tipos )
                    {
                        if ( $tipos )
                        {
                            foreach( $tipos as $chave => $valor )
                            {
                                if ( isset($valor->id) )
                                {
                                    $retorno .= '<li class="lis_menu dropdown_li_link_menu_horizontal_automatico_'.$data['id_menu'].'">';
                                    $retorno .= '<a class="a_menu dropdown_a_link_menu_horizontal_automatico_'.$data['id_menu'].'" href="';
                                    if ( isset($data['url_amigavel']) && $data['url_amigavel'] )
                                    {
                                        if($chave_tipos == 'por_cidade')
                                        {
                                            $link_montado = base_url().$item->link.'/'.( isset( $valor->id_linha ) ? '' : $valor->link ) . ( isset($valor->id_linha) ? '?&id_linha='.$valor->id_linha : '' ).( $chave_tipos == 'por_cidade' ? '' :  '?&quero='.$chave_tipos );
                                        }
                                        else
                                        {
                                            $link_montado = base_url().$item->link.'/'.( isset( $valor->id_linha ) ? '' : $chave_tipos ) . ( isset($valor->id_linha) ? '?&id_linha='.$valor->id_linha : '' ).( $chave_tipos == 'por_cidade' ? '' :  '/'.$valor->link);
                                        }
                                        $ultimo_caracter = substr($link_montado, -1, 1);
                                        $retorno .=  ( $ultimo_caracter == '?' ) ? substr($link_montado, 0, -1) : $link_montado;
                                    }
                                    else
                                    {
                                        if ( $chave_tipos == 'por_cidade' )
                                        {
                                            $retorno .= base_url().'hotsite.php?id='.$item->id_empresa.'&servico=menu_tipos&id_cidade='.$valor->id;
                                        }
                                        else
                                        {
                                            $retorno .= base_url().'hotsite.php?id='.$item->id_empresa.'&servico=menu_tipos&id_linha='.( isset($valor->id_linha) ? $valor->id_linha : '' ).'&quero='.$chave_tipos.'&id_tipo='.( isset($valor->id_linha) ? '' : $valor->id );
                                        }
                                    }
                                    $retorno .= '">';
                                    $retorno .= isset($valor->linha_descricao) ? $valor->linha_descricao : $valor->descricao;
                                    $retorno .= '</a>';
                                    $retorno .= '</li>';
                                }
                            }
                        }
                        
                    }
                    $retorno .= '</ul>';
                    
                }
                if(isset($item->subcategoria) && $item->subcategoria)
                {
                    $retorno .= '<ul class="dropdown-menu drops_'.$data['id_menu'].'" role="menu">';
                    foreach($item->subcategoria as $subcategoria)
                    {
                        $retorno .= '<li class="lis_menu dropdown_li_link_menu_horizontal_automatico_'.$data['id_menu'].'">';
                        $retorno .= '<a class="a_menu dropdown_a_link_menu_horizontal_automatico_'.$data['id_menu'].'" href="'.$subcategoria->link.'">';
                        $retorno .= $subcategoria->titulo;
                        $retorno .= '</a>';
                        $retorno .= '</li>';
                    }  
                    $retorno .= '</ul>';
                }
                $retorno .= '</li>'; '</ul>';
            }
            $retorno .= '</ul>';
            $retorno .= '</div>';
        }
        $retorno .= $this->get_style_menu($data);
        $retorno .= $this->set_javascript();
        return $retorno;
    }
    
    public function get_style_menu($data = array())
    {
        
        $retorno  = '<style type="text/css">';
        $retorno .= '.dropdown-li:hover .dropdown-menu { display: block; }'.PHP_EOL;
        $retorno .= '.dropdown-li:hover .dropdown-menu .lis_menu:hover a{ background-image:none!important; }'.PHP_EOL;
        $retorno .= '.dropdown-menu{border:none!important; box-shadow:none;!important;}'.PHP_EOL;
        //$retorno .= '.a_menu .a_link_menu_horizontal_automatico_'.$data['id_menu'].':focus{  box-shadow: 0 0 0 0!important; border: none!important; outline: 0!important;}';
        $retorno .= ' li.open ul.dropdown-menu:hover li:hover a:hover{background-image:none!important;}'.PHP_EOL;
        //$retorno .= ' .dropdown_li_link_menu_horizontal_automatico_'.$data['posicao'].' a:hover{background-color:transparent!important;}';
        $retorno .= ' .dropdown_li_link_menu_horizontal_automatico_'.$data['id_menu'].' a:hover{background-color:transparent!important;}'.PHP_EOL;
        $retorno .= ' ul.dropdown-menu li a:hover{text-decoration:none;}'.PHP_EOL;
        //$retorno .= ' ul.dropdown-menu{';
        $retorno .= ' ul.drops_'.$data['id_menu'].'{'.PHP_EOL;
        $retorno .= '   list-style:none;'.PHP_EOL;
        $retorno .= '   background-color:'.PHP_EOL;
        if( $data['parametros']->cor_fundo_submenu_transparente )
        {
            $retorno .= 'transparent!important; '.PHP_EOL;
        }
        else if(!empty($data['parametros']->cor_fundo_submenu_hexa))
        {
            $retorno .= $data['parametros']->cor_fundo_submenu_hexa.'!important; '.PHP_EOL;
        }
        else
        {
            $retorno .=  $data['parametros']->cor_fundo_submenu.'!important;'.PHP_EOL;
        }
        $retorno .= ' }';
        $retorno .= ' .nav .open > a.a_link_menu_horizontal_automatico_'.$data['id_menu'].', '.PHP_EOL;
        $retorno .= ' .dropdown-menu a.a_link_menu_horizontal_automatico_'.$data['id_menu'].', '.PHP_EOL;
        $retorno .= ' .nav .open > a.dropdown_a_link_menu_horizontal_automatico_'.$data['id_menu'].','.PHP_EOL;
        $retorno .= ' .dropdown-menu, .dropdown-menu a.dropdown_a_link_menu_horizontal_automatico_'.$data['id_menu'].'{ '.PHP_EOL;
        $retorno .= '   border-radius: 0px!important;'.PHP_EOL;
        $retorno .= '   font-family: Arial!important;'.PHP_EOL;
        $retorno .= '   font-size:'.$data['parametros']->tamanho_font.'px!important;'.PHP_EOL;
        if(!empty($data['parametros']->cor_texto_hexa))
        {
            $retorno .= '    color:'.$data['parametros']->cor_texto_hexa.' !important;'.PHP_EOL;
        }
        else
        {
            $retorno .= '    color:'.$data['parametros']->cor_texto.' !important;'.PHP_EOL;
        }
        $retorno .= '}'.PHP_EOL;
        $retorno .= ' .nav .open > a, .nav .open > a:hover, .nav .open > a:focus{background-color:transparent!important;  box-shadow: 0 0 0 0!important; border: none!important; outline: 0!important;}'.PHP_EOL;
        $retorno .= ' .nav li > a, .nav li > a:hover, .nav li > a:focus{background-color:transparent!important;  box-shadow: 0 0 0 0!important; border: none!important; outline: 0!important;}'.PHP_EOL;
        //$retorno .= ' .nav nav-pills .li_link_menu_horizontal_automatico_'.$data['posicao'].' {margin:0px!important;}';
        $retorno .= ' .nav nav-pills .li_link_menu_horizontal_automatico_'.$data['id_menu'].' {margin:0px!important;}'.PHP_EOL;
        $retorno .= ' .nav{margin-bottom:0px!important;}'.PHP_EOL;
        //$retorno .= ' ul li{margin:0px!important;}';
        //$retorno .= '.nav_link_menu_horizontal_automatico_'.$data['posicao'].'{';
        $retorno .= ' .nav_link_menu_horizontal_automatico_'.$data['id_menu'].'{'.PHP_EOL;
        $retorno .= '   background-color:'.PHP_EOL;
        if($data['parametros']->cor_fundo_transparente == '1')
        {
            $retorno .= 'transparent!important; '.PHP_EOL;
        }
        else if(!empty($data['parametros']->cor_fundo_hexa))
        {
            $retorno .= $data['parametros']->cor_fundo_hexa.'!important; '.PHP_EOL;
        }
        else
        {
            $retorno .=  $data['parametros']->cor_fundo.'!important;'.PHP_EOL;
        }
        $margin   =     explode(" ",$data['parametros']->margin);
        $retorno .= '   margin-top:'.$margin[0].'px;'.PHP_EOL;
        $retorno .= '   margin-right:'.$margin[1].'px!important;'.PHP_EOL;
        $retorno .= '   margin-bottom:'.$margin[2].'px!important;'.PHP_EOL;
        $retorno .= '   margin-left:'.$margin[3].'px!important;'.PHP_EOL;
        $retorno .= '   float:'.(($data['parametros']->alinhamento_texto != 'justify') ? $data['parametros']->alinhamento_texto : 'none' ).' !important;'.PHP_EOL;
        $retorno .= '  }';
        $padding  =     explode(" ",$data['parametros']->padding);
        //$retorno .= ' .a_link_menu_horizontal_automatico_'.$data['posicao'].'{';
        $retorno .= ' .a_link_menu_horizontal_automatico_'.$data['id_menu'].'{'.PHP_EOL;
        $retorno .= '   padding-top:'.$padding[0].'px!important;'.PHP_EOL;
        $retorno .= '   padding-right:'.$padding[1].'px!important;'.PHP_EOL;
        $retorno .= '   padding-bottom:'.$padding[2].'px!important;'.PHP_EOL;
        $retorno .= '   padding-left:'.$padding[3].'px!important;'.PHP_EOL;
        $retorno .= '   border-radius: 0px!important;'.PHP_EOL;
        $retorno .= '   font-family: Arial!important;'.PHP_EOL;
        $retorno .= '   font-size:'.$data['parametros']->tamanho_font.'px!important;'.PHP_EOL;
        if(!empty($data['parametros']->cor_texto_hexa))
        {
            $retorno .= '    color:'.$data['parametros']->cor_texto_hexa.' !important;'.PHP_EOL;
        }
        else
        {
            $retorno .= '    color:'.$data['parametros']->cor_texto.' !important;'.PHP_EOL;
        }
        $retorno .= '  }';
        //$retorno .= ' .a_link_menu_horizontal_automatico_'.$data['posicao'].':hover{';
        $retorno .= ' .a_link_menu_horizontal_automatico_'.$data['id_menu'].':hover, '.PHP_EOL;
        //$retorno .= ' a.dropdown_a_link_menu_horizontal_automatico_'.$data['id_menu'].':hover{'.PHP_EOL;
        $retorno .= 'ul.nav li.dropdown-li a.a_link_menu_horizontal_automatico_'.$data['id_menu'].':hover{'.PHP_EOL;
        $retorno .= '    font-size: '.$data['parametros']->tamanho_font.'px!important;'.PHP_EOL;
        if(!empty($data['parametros']->cor_texto_hover_hexa))
        {
            $retorno .= '    color:'.$data['parametros']->cor_texto_hover_hexa.'!important;'.PHP_EOL;
        }
        else
        {
            $retorno .= '    color:'.$data['parametros']->cor_texto_hover.'!important;'.PHP_EOL;
        }
        $retorno .= '    background-color:'.PHP_EOL;
        if($data['parametros']->cor_fundo_hover_transparente == '1')
        {
            $retorno .= 'transparent!important;'.PHP_EOL;
        }
        else if(!empty($data['parametros']->cor_fundo_hover_hexa))
        {
            $retorno .= $data['parametros']->cor_fundo_hover_hexa.'!important;'.PHP_EOL;
        }
        else 
        {
            $retorno .= $data['parametros']->cor_fundo_hover.'!important;'.PHP_EOL;
        }
        $retorno .= '  }';
        //$retorno .= ' .li_link_menu_horizontal_automatico_'.$data['posicao'].' {';
        $retorno .= ' .li_link_menu_horizontal_automatico_'.$data['id_menu'].' {'.PHP_EOL;
        $retorno .= '    border-bottom: none!important;'.PHP_EOL;
        $retorno .= '    font-size: '.$data['parametros']->tamanho_font.'px!important;'.PHP_EOL;
        $retorno .= '    font-family: Arial !important;'.PHP_EOL;
        $retorno .= '  }'.PHP_EOL;
        $retorno .= ' ul li ul.drops_'.$data['id_menu'].'{'.PHP_EOL;
        $retorno .= ' padding:5px;'.PHP_EOL;
        $retorno .= '}'.PHP_EOL;
        $retorno .= ' ul li ul.drops_'.$data['id_menu'].' li a{'.PHP_EOL;
        $retorno .= ' margin:5px; padding:5px'.PHP_EOL;
        $retorno .= '}'.PHP_EOL;
        $retorno .= '</style>';
        return $retorno;
    }
    
    public function set_javascript()
    {
        $retorno  = '<script type="text/javascript">';
//        $retorno .= '$(".dropdown-menu").on({';
//        $retorno .= '  "shown.bs.dropdown": function() { this.closable = false; },';
//        $retorno .= '  "click":             function() { this.closable = true; },';
//        $retorno .= '  "hide.bs.dropdown":  function() { return this.closable; }';
//        $retorno .= '  });';
        //$retorno .= "$('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); });";
        $retorno .= '</script>';
        return $retorno;
    }
    
    private $tipos_dropdown = array(
                                    'imovel_venda'              => array('valor' => 'venda',            'join' => TRUE),
                                    'imovel_locacao'            => array('valor' => 'locacao',          'join' => TRUE),
                                    'imovel_locacao_temporada'  => array('valor' => 'locacao',          'join' => TRUE),
                                    'imovel_cidade'             => array('valor' => 'cidade',           'join' => array('nome' => 'cidades',      'where' => 'cidades.id      = imoveis.id_cidade','tipo' => 'INNER')),
                                    'imovel_tipo_apartamento'   => array('valor' => 'tipo_apartamento', 'join' => array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER')),
                                    'imovel_tipo_casa'          => array('valor' => 'tipo_casa',        'join' => array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER')),
                                    );
    
    private function get_tipos($item)
    {
        
//        var_dump($item);die();
        switch ($item->tipo)
        {
            case 'imovel_venda' :
            case 'imovel_locacao':
            case 'imovel_locacao_temporada':

                $this->CI->load->model(array('imoveis_tipos_model'));

                $tipo_dropdown = $this->tipos_dropdown[$item->tipo];
                $filtro = array(
                    array('tipo' => 'where', 'campo' => 'imoveis.'.$tipo_dropdown['valor'],'valor' => 1),
                    array('tipo' => 'where', 'campo' => 'imoveis.id_empresa','valor' => $this->id_empresa),
                );
                
                $links['itens'] = $this->CI->imoveis_tipos_model->get_select($filtro,'titulo','ASC',  $tipo_dropdown['join']);
                $links['qtde'] = count($links['itens']);
//                        var_dump($links);
                break; 

            case 'imovel_cidade':

                $this->CI->load->model(array('imoveis_tipos_model'));
//                var_dump($item);
                $tipo_dropdown = $this->tipos_dropdown[$item->tipo];
                $filtro = array(
                    array('tipo' => 'where','campo' => 'imoveis.id_empresa','valor' => $this->id_empresa),
                );
                $campos = 'cidades.nome as titulo, cidades.link as link';

                $links['itens'] = $this->CI->imoveis_tipos_model->get_select($filtro,'cidades.nome','ASC',  $tipo_dropdown['join'],'cidades.id',$campos);
                $links['qtde'] = count($links['itens']);
//                        var_dump($links);

                break;
            default:
                $filtro = array(
                    array('tipo' => 'where','campo' => 'hotsite_menu_link.ativo','valor' => 1),
                    array('tipo' => 'where','campo' => 'hotsite_menu_link.id_pai','valor' => $item->id)
                );
                    $links = $this->CI->hotsite_menu_link_model->get_itens($filtro,'ordem','ASC');
//                    var_dump($links);die();
                break;
        }
        return $links;
    }

    public function get_css()
    {
        $this->CI->load->model(array('hotsite_menu_model'));
        $filtro = array(
            array('tipo' => 'where', 'campo' => 'hotsite_menu.id', 'valor' => $this->id_menu),
            array('tipo' => 'where', 'campo' => 'hotsite_menu.id_empresa', 'valor' => $this->id_empresa)
        );
        $retorno = $this->CI->hotsite_menu_model->get_item($filtro);
        return (isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL);
    }

    
    public function get_menu_por_id($id_menu = NULL)
    {
        $this->id_menu = isset($this->id_menu) && $this->id_menu ? $this->id_menu : $id_menu;
        $filtro = array(
            array('tipo' => 'where','campo' => 'hotsite_menu_link.id_hotsite_menu','valor' => $this->id_menu),
            array('tipo' => 'where','campo' => 'hotsite_menu_link.ativo','valor' => 1),
            array('tipo' => 'where','campo' => 'hotsite_menu_link.id_pai','valor' => 0)
        );
        $itens = $this->CI->hotsite_menu_link_model->get_itens($filtro,'ordem','ASC');
        $filho = FALSE;
        $retorno = $this->get_menu($itens, $filho, TRUE);
        return $retorno;
    }
    
    /**
     * @param  $data NULL
     * @return string
     */
    public function get_menu( $itens = NULL, $filho = TRUE, $cabecalho = FALSE )
    {
        $ul = '';
        $li = '';
        $depois = '';
        $retorno = '';
        
        if( ! isset($itens) )
        {
            $itens = $this->get_menu_por_id();
//            exit;
        }
//        var_dump($itens);die();
        
        if(isset($itens['itens']) && $itens['qtde'] > 0)
        {
            $ul .= '<ul class="[ul]">'.PHP_EOL;
            foreach ($itens['itens'] as $item)
            {
                $dropdown_itens = $this->get_tipos($item);
//                var_dump($dropdown_itens);
                $li .= '<li class="[li]'.($dropdown_itens['qtde'] > 0 && $filho ? ' dropdown-submenu' : '').' item-menu-'.$item->id.'">'.PHP_EOL;
                $li .= '<a href="'.(strstr($item->link, 'http') ? $item->link : base_url().$item->link).'" '.($dropdown_itens['qtde'] > 0 ? 'data-toggle="dropdown"' : '').'>'.PHP_EOL;
                $li .= $item->titulo.PHP_EOL;
                $li .= ($dropdown_itens['qtde'] > 0 && ! $filho ? '<span class="caret"></span>' : '').PHP_EOL;
                $li .= '</a>'.PHP_EOL;
                
                $li .= $this->get_menu($dropdown_itens);
                $li .= '</li>'.PHP_EOL;
            }
            $depois = '</ul>'.PHP_EOL;
        }
        if ( $cabecalho )
        {
            $retorno = '<nav class="navbar">';
            $retorno .= '<div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed " data-toggle="collapse" data-target="#navbar-collapse-s" aria-expanded="false">
                          <span class="sr-only">Navegação reduzida</span>
                          <span class="icon-bar bg-green"></span>
                          <span class="icon-bar bg-green"></span>
                          <span class="icon-bar bg-green"></span>
                        </button>

                      </div><div class="collapse navbar-collapse" id="navbar-collapse-s">';
            
        }
        $retorno .= ($filho ? str_replace('[ul]', 'dropdown-menu submenu-ul-'.$this->id_menu, $ul) : str_replace('[ul]', 'nav nav-pillis nav-justified ul-'.$this->id_menu, $ul));
        $retorno .= str_replace('[li]', 'li-'.$this->id_menu, $li);
        $retorno .= $depois;
        if( ! $filho )
        {
            $retorno .= PHP_EOL.'<style type="text/css">'.PHP_EOL;
            $css = $this->get_css($this->id_menu,  $this->id_empresa);
            $retorno .= (isset($css->css) ? $css->css : '');
            $retorno .= PHP_EOL.'</style>';
        }
        if ( $cabecalho )
        {
            $retorno .= '</div></nav>';
        }
        return $retorno;
    }
    
    public function get_links($itens = NULL)
    {
        $is_filho = TRUE;
        $retorno = array();
        if( ! isset($itens) )
        {
            $filtro = array(
                array('tipo' => 'where','campo' => 'hotsite_menu_link.id_hotsite_menu','valor' => $this->id_menu),
                array('tipo' => 'where','campo' => 'hotsite_menu_link.ativo','valor' => 1),
                array('tipo' => 'where','campo' => 'hotsite_menu_link.id_pai','valor' => 0)
            );
            $itens = $this->CI->hotsite_menu_link_model->get_itens($filtro,'ordem','ASC');
            $is_filho = FALSE;
            $retorno['itens'] = new stdClass();
        }
        if(isset($itens['itens']) && $itens['qtde'] > 0)
        {
            foreach ($itens['itens'] as $chave => $item)
            {
                if( ! isset($retorno['itens']))
                {
                    $retorno['itens'] = new stdClass();
                }
                if( ! (strstr($item->tipo,'imovel_') || strstr($item->tipo,'produto_')) )
                {
                    $filhos = $this->get_tipos($item);
                    $dropdown = $this->get_links($filhos);
                }
                $retorno['itens']->{$chave} = $item;
                
                $retorno['itens']->{$chave}->itens = (isset($dropdown['itens']) ? $dropdown['itens'] : NULL);
            }
        }
        
        return $retorno;
    }
}