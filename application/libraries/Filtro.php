<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 * formato padrao de chamada a classe
 * $config['valores'] = $_GET['b']
 * $config['itens'] = array( 'name' => 'nome', 'tipo' => 'text', 'classe' => 'classes', 'valor' => '', 'where' => array( 'tipo' => 'where', 'campo' => 'login', 'valor' => 'teste' ) )
 * $config['colunas'] = 1
 * $config['extras'] = onsubmit="aprova"
 * $config['url'] = http://www.site.com.br/classe/funcao
 * $config['botoes'] = botao
 */

class Filtro
{
	/**
	 * 
	 * Itens do campo de busca
	 * @var $itens
	 */
	private $itens;
	/**
	 * 
	 * Valores do campo de busca
	 * @var campos
	 */
	private $valores;
	
	private $extras;
        
	private $botoes;
	
	private $url;
	/**
	 * 
	 * Qtde colunas da montagem do menu
	 * @var colunas
	 */
	private $colunas = 1;
	
	/**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct($config = FALSE) 
	{
		if ( $config )
		{
			$this->inicia($config);
		}
	}
	
	private function _get_campo( $item = array(), $sem_b = FALSE )
	{
		$retorno = '';
                $name = $sem_b ? $item['name'] : 'b['.$item['name'].']';
		switch ( $item['tipo'] )
		{
			case 'hidden':
                                $retorno .= '<input class="'.( isset($item['classe']) ? $item['classe'] : '' ).'" name="'.$name.'" type="'.$item['tipo'].'" value="'.$item['valor'].'" >'.PHP_EOL;
                                break;
			case 'text':
			case 'email':
			case 'password':
			case 'number':
			case 'image':
			case 'url':
				$retorno .= '<input class="form-control ';
                                $retorno .= ( isset($item['classe']) ? $item['classe'] : '' ).'" '
                                        . 'name="'.$name.'" type="'.$item['tipo'].'" '
                                        . 'value="';
                                if ( array_key_exists($item['name'], $this->valores) )
                                {
                                    $retorno .= isset($item['acao']) ? $item['acao']($this->valores[$item['name'] ]) : $this->valores[$item['name'] ];
                                }
//                                $retorno .= '" '.(isset($item['extra']) ? $item['extra'] : '').' placeholder="'.$item['titulo'].'">'.PHP_EOL;
                                $retorno .= '" '.(isset($item['extra']) ? $item['extra'] : '');
                                if ( $item['tipo'] == 'number' )
                                {
                                    $retorno .= ' step="500" ';
                                }
                                $retorno .= ( isset($item['src']) ? ' src="'.$item['src'].'" ' : '' );
                                $retorno .= '>'.PHP_EOL;
				break;
                        case 'file':
                            $retorno .= '<div id="dropzone_teste" class="dropzone">';
                            $retorno .= '<div class="fallback">';
                            $retorno .= '<input class="form-control ';
                            $retorno .= ( isset($item['classe']) ? $item['classe'] : '' ).'" '
                                    . 'name="'.$name.'" type="'.$item['tipo'].'" '
                                    . 'value="';
                            if ( array_key_exists($item['name'], $this->valores) )
                            {
                                $retorno .= isset($item['acao']) ? $item['acao']($this->valores[$item['name'] ]) : $this->valores[$item['name'] ];
                            }
//                                $retorno .= '" '.(isset($item['extra']) ? $item['extra'] : '').' placeholder="'.$item['titulo'].'">'.PHP_EOL;
                            $retorno .= '" '.(isset($item['extra']) ? $item['extra'] : '');
                            $retorno .= '>'.PHP_EOL;
                            $retorno .= '</div>'.PHP_EOL;
                            $retorno .= '</div>'.PHP_EOL;
                            $retorno .= '<div class="espaco-arquivos"></div>';
				break;
			case 'textarea':
				$retorno .= '<textarea class="form-control ';
                                $retorno .= ( isset($item['classe']) ? $item['classe'] : '' ).'" '
                                        . 'name="'.$name.'" ';
                                $retorno .= ' '.(isset($item['extra']) ? $item['extra'] : '').'>';
                                if ( array_key_exists($item['name'], $this->valores) )
                                {
                                    $retorno .= isset($item['acao']) ? $item['acao']($this->valores[$item['name'] ]) : $this->valores[$item['name'] ];
                                }
                                $retorno .= '</textarea>'.PHP_EOL;
				break;
			case 'select':
				$retorno .= '<select style="width:100%;"  name="b['.$item['name'].']" class="'.( isset($item['classe']) ? $item['classe'] : '' ).' select2" '.(isset($item['extra']) ? $item['extra'] : '').'>'.PHP_EOL;
				$retorno .= '<option value="">'.(isset($item['placeholder']) ? $item['placeholder'] : 'selecione...').'</option>'.PHP_EOL;
				foreach ( $item['valor'] as $valor )
				{
					$retorno .= '<option value="'.$valor->id.'" '.( ( isset($this->valores[$item['name']]) && $this->valores[$item['name']] == $valor->id ) ? 'selected="selected"' : '' ).'>'.$valor->descricao.'</option>'.PHP_EOL;
				}
				$retorno .= '</select>'.PHP_EOL;
				break;
			case 'radio':
				foreach ( $item['valor'] as $valor )
				{
                                    $retorno .= '<label class="radio-inline"><input type="radio" name="b['.$item['name'].']" value="'.$valor->id.'"> '.$valor->descricao.' </label>';
				}
				break;
                        case 'clicavel':
                            $retorno .= $this->_get_modal($item['name'], isset($item['selecionado']) ? $item['selecionado'] : $item['titulo'], $item['valor']);
                            break;
                        case 'inativo' :
                            $retorno .= '';
                            break;
                        
		}
                $retorno .= (isset($item['html']) ? $item['html'] : '');
		return $retorno;
	}
        
        private function _get_modal( $item = NULL, $titulo = NULL, $valores = NULL  )
        {
            $retorno = '<button type="button" class="btn btn-default col-lg-12 '.$item.'" data-toggle="modal" data-target="#modal-'.$item.'" data-item="'.(isset($titulo->id) ? $titulo->id : 'i').'">'.(isset($titulo->descricao) ? $titulo->descricao : $titulo).'</button>';
            $retorno .= '<div class="modal fade" id="modal-'. $item .'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
            $retorno .= '<div class="modal-dialog modal-pesquisa">';
            $retorno .= '<div class="modal-content">';
            $retorno .= '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4>Escolha '. (isset($titulo->descricao) ? $titulo->descricao : $titulo) .':</h4></div>';
            $retorno .= '<div class="modal-body container ">';
            $retorno .= '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
            $retorno .= '<div class="escolhidos"><ul></ul></div><br>';
            $retorno .= '<a href="#" class="btn btn-success pesquisar-'.$item.'" id="pesquisar-modal">Pesquisar</a>';
            $retorno .= '</div>';
            $retorno .= '<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">';
            $retorno .= '<div class="itens">';
            $itens['valor'] = $valores;
            $itens['titulo'] = isset($titulo->descricao) ? $titulo->descricao : $titulo;
            $itens['link'] = $item;
            $retorno .= isset($itens) ? form_selecionavel($itens) : '';
            $retorno .= '</div><!-- .itens -->';
            $retorno .= '</div><!-- .col-lg-9 -->';
            $retorno .= '</div><!-- .modal-body -->';   
            $retorno .= '<div class="modal-footer">';
            $retorno .= '<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>';
            $retorno .= '</div><!-- .modal-footer -->';
            $retorno .= '</div>';
            $retorno .= '</div><!-- .modal-dialog -->';
            $retorno .= '</div><!-- .modal -->';
            return $retorno;
        }
	
        public function get_form_group($item, $material = FALSE, $sem_b = FALSE, $caracteristicas = array() )
        {
            $label = isset($item['label']) && ! empty($item['label']) ? TRUE : FALSE;
            if ( $item['tipo'] !== 'hidden' )
            {
                $retorno = '   <div class="form-group '.( $material ? 'form-md-line-input ' : '' ).'" >'.PHP_EOL;
                
            }
            else
            {
                $retorno = '';
            }
//            if ( $label )
//            {
//                $retorno .= '       <label for="'.$item['titulo'].'" '.($label ? '' : 'class="hide"').'>'.$item['label'].'</label>'.PHP_EOL;
//            }
            if ($material)
            {
                
                if ( isset($item['caracteristicas']->input_group) && ! empty($item['caracteristicas']->input_group) )
                {
                    $retorno .= '<div class="input-group">';
                }
                $retorno .=         $this->_get_campo($item, $sem_b).PHP_EOL;
                if ( isset($item['caracteristicas']->input_group) && ! empty($item['caracteristicas']->input_group) )
                {
                    $retorno .= '<span class="input-group-addon input-circle-right"><i class="fa '.$item['caracteristicas']->input_group.'"></i></span>'
                            . '</div>';
                }
                if ( $item['tipo'] !== 'hidden' && !empty($item['titulo']) )
                {
                    $retorno .= '       <label>'.(isset($item['titulo']) ? $item['titulo'] : '').'</label>'.PHP_EOL;
                }
            }
            else
            {
                if ( $item['tipo'] !== 'hidden' && !empty($item['titulo']) )
                {
                    $retorno .= '       <label>'.(isset($item['titulo']) ? $item['titulo'] : '').'</label>'.PHP_EOL;
                }
                if ( isset($item['caracteristicas']->input_group) && ! empty($item['caracteristicas']->input_group) )
                {
                    $retorno .= '<div class="input-group">';
                }
                $retorno .=         $this->_get_campo($item, $sem_b).PHP_EOL;
                if ( isset($item['caracteristicas']->input_group) && ! empty($item['caracteristicas']->input_group) )
                {
                    $retorno .= '<span class="input-group-addon input-circle-right"><i class="fa '.$item['caracteristicas']->input_group.'"></i></span>'
                            . '</div>';
                }
                
            }
            if ( $item['tipo'] !== 'hidden' )
            {
                $retorno .= '   </div>'.PHP_EOL;
            }
            return $retorno;
        }
        
	public function get_html()
	{
		$retorno = '';
		if ( $this->itens )
		{
                        $retorno .= '<div class="portlet box blue">';
                        $retorno .= '<div class="portlet-title">';
                        $retorno .= '<div class="caption">';
                        $retorno .= 'Filtro';
                        $retorno .= '</div>';
                        $retorno .= '<div class="tools">';
                        $retorno .= '<a href="javascript:;" class="expand" data-original-title="" title=""></a>';
                        $retorno .= '</div>';
                        $retorno .= '</div>';
                        $retorno .= '<div class="portlet-body" style="display:none">';
			//$retorno .= '<form action="'.$this->url.'" method="get" '.( ($this->extras) ? $this->extras : '' ).' role="form" class="form-inline">';
			$retorno .= '<form action="'.$this->url.'" method="get" '.( ($this->extras) ? $this->extras : '' ).' role="form" class="form">';
                        $retorno .= '   <h4>Pesquisar</h4>';
                        //$retorno .= '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        $retorno .= '   <div class="row">';
			$coluna = floor(12/($this->colunas));
                        
			foreach ( $this->itens as $item )
			{
                            if(isset($item['tipo']) && !empty($item['tipo']) )
                            {
				if ( $item['tipo'] != 'hidden' )
				{
					$retorno .= '<div class="col-lg-'.$coluna.' col-md-'.$coluna.' col-sm-'.$coluna.' col-xs-'.$coluna.'" >'.PHP_EOL;
					$retorno .= $this->get_form_group($item, TRUE);//'   <div class="form-group" >'.PHP_EOL;
//					$retorno .= '       <label for="'.$item['titulo'].'">'.$item['titulo'].'</label>'.PHP_EOL;
//					$retorno .=         $this->_get_campo($item).PHP_EOL;
//					$retorno .= '   </div>'.PHP_EOL;
					$retorno .= '</div>'.PHP_EOL;
				}
				else 
				{
					$retorno .= $this->_get_campo($item);
				}
                            }
			}
                        $retorno .= '</div><div class="row">';
                        $retorno .= '       <div class="col-lg-'.$coluna.' col-md-6 col-sm-6 col-xs-6">'.PHP_EOL;
                        $retorno .= '           <br>'.PHP_EOL;
                        $retorno .= '           <button type="submit" class="btn btn-primary btn-block">Buscar</button>'.PHP_EOL;
                        $retorno .= '       </div>'.PHP_EOL;
                        $retorno .= '       <div class="col-lg-'.$coluna.' col-md-6 col-sm-6 col-xs-6">'.PHP_EOL;
                        $retorno .= '           <br>'.PHP_EOL;
                        $retorno .= '           <a href="'.$this->url.'" class="btn btn-default btn-block">Limpar</a>'.PHP_EOL;
                        $retorno .= '       </div>'.PHP_EOL;
			$retorno .= '   </div>'.PHP_EOL;
			$retorno .= '   <div class="row">'.PHP_EOL;
			$retorno .= '       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'.PHP_EOL;
			//$retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        //$retorno .= '<button type="submit" class="btn btn-primary">Buscar</button><a href="'.$this->url.'" class="btn btn-default">Limpar</a>';
			$retorno .= ( ($this->botoes) ? str_replace('[filtro]', $this->get_url(), $this->botoes) : '' );
			$retorno .= '       </div>'.PHP_EOL;
			$retorno .= '   </div>'.PHP_EOL;
			$retorno .= '</form>'.PHP_EOL;
			$retorno .= '</div>'.PHP_EOL;//Portlet-body
			$retorno .= '</div>'.PHP_EOL;//portlet
                       
		}
		return $retorno; 
	}
	
	public function get_url($build_query = FALSE)
	{
		$retorno = '';
                if(isset($build_query) && $build_query)
                {
                    $retorno .= '?'.http_build_query($build_query);
                }
		elseif ( $this->valores )
		{
                        $a = 0;
                        foreach ( $this->valores as $chave => $valor )
                        {
                            $v = is_array($valor) ? implode(',',$valor) : urlencode($valor);
                                if ( $a == 0 )
                                {
                                        $retorno .= '?b['.$chave.']='.$v;
                                }
                                else 
                                {
                                        $retorno .= '&b['.$chave.']='.$v;
                                }
                                $a++;
                        }
		}
		return $retorno;
	}
        
	public function get_filtro()
	{
		$retorno = array();
		if ( $this->itens )
		{
                    foreach ( $this->itens as $item )
                    {
//                        var_dump($item['name'],$this->valores[ $item['name'] ]);
                        $filtra = FALSE;
                        if ( isset( $this->valores[ $item['name'] ] ) && ( is_array($this->valores[ $item['name'] ]) || ! empty( trim($this->valores[ $item['name'] ]) ) ) )
                        {
                            $filtra = TRUE;
                        }
                        elseif ( isset( $this->valores[ $item['name'] ] ) && ( (is_string($this->valores[ $item['name'] ]) ) && trim($this->valores[ $item['name'] ]) === "0" ) )
                        {
                            $filtra = TRUE;
                        }
//                        var_dump($filtra);
                        if ( $filtra )
                        {
                            if ( is_array( $item['where'] ) )
                            {
                                $retorno[] = array( 'tipo' => $item['where']['tipo'], 'campo' =>  $item['where']['campo'] , 'valor' => $this->valores[ $item['name'] ], 'unescape' => ( (isset($item['where']['unescape']) && $item['where']['unescape']) ? TRUE : NULL ) );
                            }
                            else 
                            {
                                if ( isset($item['where']) && $item['where'] )
                                {
                                    $retorno[] = $item['where'];
                                }
                            }
                        }
                    }
		}
		return $retorno;
	}
        
        public function get_valores()
        {
            return $this->valores;
        }
	
	public function inicia($config)
	{
		$this->itens 	= ( isset($config['itens']) ? $config['itens'] : FALSE );
		$this->valores 	= ( isset($config['valores']) ? $config['valores'] : array() );
		$this->colunas 	= ( isset($config['colunas']) ? $config['colunas'] : FALSE );
		$this->url 	= ( isset($config['url']) ? $config['url'] : FALSE );
		$this->extras	= ( isset($config['extras']) ? $config['extras'] : FALSE );
		$this->botoes 	= ( isset($config['botoes']) ? $config['botoes'] : FALSE );
		
		return $this;
	}
	
        public function get_titulo()
        {
            return $this->extras['titulo'];
        }
        
        public function get_description()
        {
            return $this->extras['description'];
        }
        
}	
