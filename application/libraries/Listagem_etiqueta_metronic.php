<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 */

class Listagem_etiqueta_metronic
{
	private $cabecalho;
	private $itens;
	private $chave = NULL;
	private $extras = FALSE;
        private $operacoes = FALSE;
        private $titulo = NULL;
        private $qtde_linha = 1;
        private $ordenacao = TRUE;
	/**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct($config = FALSE) 
	{
		if ( $config )
		{
			$this->inicia($config);
                        $this->load->helper('url');
		}
	}
	
	public function get_html()
	{
            $itens = $this->_set_itens();
            $retorno = '';
            if( isset($this->extras['grafico']) )
            {
                $retorno .= '<div class="grafico"><div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>Estatísticas </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        <a href="" class="fullscreen" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body portlet-collapsed">
                                    <strong>Confira em breve as estatísticas dos seus imóveis.</strong>
                                </div>
                            </div></div>';
            }
            $retorno .= '<div class="portlet box blue">';
            $retorno .= '<div class="portlet-title">';
            if ( isset($this->titulo) && ! empty($this->titulo) )
            {
                $retorno .= '<div class="caption"><h4>'.$this->titulo.'</h4></div>';
            }
            else
            {
                $retorno .= '<div class="portlet-title"><div class="caption">&nbsp;</div>';
            }
            $retorno .= '<div class="tools">';
            if( isset($this->extras['btn']) )
            {
                $retorno .= $this->extras['btn'];
            }
            if( isset($this->extras['total_itens']) )
            {
                $retorno .= '<h4>Total de itens: '.number_format($this->extras['total_itens']).'</h4><input type="hidden" class="total_itens" value="'.$this->extras['total_itens'].'"> ';
            }
            if ( isset($this->cabecalho) && ! empty($this->cabecalho) && $this->ordenacao )
            {
                $retorno .= '<div class="dropdown dropdown-extended"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
                $retorno .= 'Ordenação <span class="caret"></span>';
                $retorno .= '</button>';
                $retorno .= '<ul class="dropdown-menu" role="menu">';
                $retorno .= $itens['ordenacao'];
                $retorno .= '</ul></div>';
            }
            $retorno .= '</div>';

            $retorno .= '</div>';
            $retorno .= '<div class="portlet-body">';
            
            if(isset($this->extras['cabecalho']) && $this->extras['cabecalho'])
            {
                
                $cabecalho = $this->extras['cabecalho'];
                $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $retorno .= '   <div class="row alert alert-info">';
                $retorno .= '       <div class="col-lg-6">';
                $retorno .= '           <h4>'.$this->extras['opcao'].' : '.$cabecalho->titulo.'</h4>';
                $retorno .= '           <input type="hidden" value="'.$cabecalho->id.'" class="valor_pai">';
                $retorno .= '       </div>';
                $retorno .= '   </div>';
                $retorno .= '</div>';
            }
            
            $retorno .= $itens['lista'];
            
            $retorno .= '</div>';//portlet-body
            $retorno .= '</div>';//portlet
            

            /*
            $retorno .= '<table border="0" class="table table-striped tb_carlos">';
            $retorno .= $this->_set_cabecalho();
            $retorno .= $this->_set_itens();
            $retorno .= '</table>';
             * 
             */
            return $retorno;
	}
	
        private function _set_item( $item, $c, $chave )
        {
            $retorno['valor'] = '';
            
            $ch = $c->chave;
            $retorno['valor'] .= '<div class="'.$c->classe.' " data-item="'.$item->{$chave}.'">';
            $retorno['valor'] .= !empty($c->titulo) ? $c->titulo.': ' : '';
            $valor_ = $item->{$ch};
            if ( isset($c->acao) && $c->acao )
            {
                $complemento = (isset($c->complemento) && $c->complemento) ? $item->{$c->complemento} : NULL;
                if ( is_array($c->acao) )
                {
                    $acao = $c->acao['acao'];
                    $c->acao['valor'] = $valor_;
                    $valor_ = $acao($c->acao, $complemento);
                    $retorno['valor'] .= $valor_;
                }
                else
                {
                    $acao = $c->acao;
                    $valor_ = $acao($valor_, $complemento);
                    $retorno['valor'] .= '<strong>'.$valor_.'</strong>';
                }
            }
            else
            {
                $retorno['valor'] .= '<strong>'.$valor_.'</strong>';
            }
            $retorno['valor'] .= '</div>';
            $retorno['ordem'] = isset($c->link) ? '<li><a href="'.$c->link.'" class="row"><span class="pull-left">'.$c->titulo.'</span><span class="pull-right"><span class=" '.$c->class.'"></span></span></a></li>' : '';
            return $retorno;
        }
        
	private function _set_itens()
	{
            $retorno['lista'] = '';
            $retorno['ordenacao'] = '';
            if ( isset( $this->itens ) && count( $this->itens ) > 0 )
            {
                $colunas = 12 / $this->qtde_por_linha;
                //var_dump( $this->cabecalho );
                //var_dump( $this->itens );die();
                $retorno['lista'] .= '<div class="row">';
                for ( $i = 0; count($this->itens) > $i; $i++ )
                {
                    $item = $this->itens[$i];
                    $chave = $this->chave;
                    
                    $retorno['lista'] .= '<div class="col-lg-'.$colunas.' col-sm-'.$colunas.' col-md-'.$colunas.' col-xs-12">';
                    $retorno['lista'] .= '<div class="portlet light bordered elemento-'.str_replace('/','-',$item->{$chave}).' listagem-imoveis row">';
                    foreach ( $this->cabecalho as $numero => $c )
                    {
                        if ( is_object($c) )
                        {
                            $item_c = $this->_set_item($item, $c, $chave);
                            $retorno['lista'] .= $item_c['valor'];
                            if ( ! $i )
                            {
                                $retorno['ordenacao'] .=   $item_c['ordem'];
                            }
                        }
                        else
                        {
                            if ( isset($c['titulo']) )
                            {
                                $retorno['lista'] .= '<div class="portlet-title '.(isset($c['operacoes']) ? 'tabbable-line': '' ).'">';
                                $retorno['lista'] .= '<div class="caption">'
                                                    . '<span class="uppercase caption-subject bold font-yellow-lemon">'.$c['titulo'].'</span>'
                                                    . '<span class="caption-helper"> '.$item->id.'</span>'
                                                    . '</div>';
                                if( isset($c['operacoes']) )
                                {
                                    $retorno['lista'] .= '<ul class="nav nav-tabs">';
                                    foreach ($c['operacoes'] as $tab => $operacoes)
                                    {
                                        $retorno['lista'] .= '<li class="'.($tab == 0 ? 'active' : '').'">';
                                        $retorno['lista'] .= '<a href="#portlet_tab'.$tab.'_'.$i.'" data-toggle="tab" aria-expanded="false">';
                                        $retorno['lista'] .= $operacoes->titulo;
                                        $retorno['lista'] .= '</a>';
                                        $retorno['lista'] .= '</li>';
                                        
                                    }
                                    $retorno['lista'] .= '</ul>';
                                }
                                $retorno['lista'] .= '</div>';
                                
                            }
                            if(isset($c['operacoes']))
                            {
                                $retorno['lista'] .= '<div class="portlet-body">';
                                $retorno['lista'] .= '<div class="tab-content">';
                                foreach ($c['operacoes'] as $tab => $operacoes)
                                {
                                    $retorno['lista'] .= '<div class="tab-pane '.($tab===0 ? 'active' : 'fade').'" id="portlet_tab'.$tab.'_'.$i.'">';
                                    $retorno['lista'] .= '<div class="row">';
                                    foreach( $operacoes->itens as $c_item )
                                    {
                                        $item_c = $this->_set_item($item, $c_item, $chave);
                                        $retorno['lista'] .= $item_c['valor'];
                                        if ( ! $i )
                                        {
                                            $retorno['ordenacao'] .= $item_c['ordem'];
                                        }
                                    }
                                    $retorno['lista'] .= '</div>';//row ?
                                    $retorno['lista'] .= '</div>';//tab-pane
                                    
                                }
                                $retorno['lista'] .= '</div>';// tab-content
                                $retorno['lista'] .= '</div>';// portlet-body
                            }
                            else
                            {
                                $retorno['lista'] .= '<div class="portlet-body">';
                                $retorno['lista'] .= '<div class="'.$c['classe'].'">';
                                $retorno['lista'] .= '<div class="row">';
                                foreach( $c['itens'] as $c_item )
                                {
                                    $item_c = $this->_set_item($item, $c_item, $chave);
                                    $retorno['lista'] .= $item_c['valor'];
                                    if ( ! $i )
                                    {
                                        $retorno['ordenacao'] .= $item_c['ordem'];
                                    }
                                }
                                $retorno['lista'] .= '</div>';//row ?
                                $retorno['lista'] .= '</div>';//$classe
                                $retorno['lista'] .= '</div>';// portlet-body
                                
                            }
                        }
                    }
                    if(isset($this->operacoes) && $this->operacoes)
                    {
                        foreach($this->operacoes as $operacao)
                        {
                            $retorno['lista'] .= '<a class="'.$operacao->class.' '.  strtolower($operacao->titulo).'" data-item="'.$item->id.'" '.(isset($operacao->target) ? 'target="'.$operacao->target.'"' : '');
                            if (isset ($operacao->extra) ) 
                            {
                                if(strripos($operacao->extra, '[id]'))
                                {
                                    $id_extra = str_replace('[id]', $item->id, $operacao->extra);
                                }
                                else
                                {
                                    $id_extra = $operacao->extra;
                                }
                                
                                $retorno['lista'] .= $id_extra;
                            }
                            if(isset($operacao->link) && $operacao->link)
                            {
                                if(strripos($operacao->link, '[id]'))
                                {
                                    $id_link = str_replace('[id]', $item->id, $operacao->link);
                                }
                                else
                                {
                                    $id_link = $operacao->link;
                                }
                                $retorno['lista'] .= 'href="'.base_url().$id_link.'"';
                            }
                            $retorno['lista'] .= ' >'.$operacao->icone.' '.$operacao->titulo;
                            $retorno['lista'] .= '</a> ';
                        }
                    }
                    $retorno['lista'] .= '</div>';// portlet
                    $retorno['lista'] .= '</div>';// col
                    unset($item);
                }
                $retorno['lista'] .= '</div>';
            }
            else
            {
                $retorno['lista'] .= '<li class="mt-list-item done">Nenhum Item Encontrado</li>';
            }
            return $retorno;
	}
        
        
        private function _set_link_completo($c = NULL, $item = NULL, $chave = NULL)
        {
            $retorno = NULL;
            //$link = (isset($c->nav_menu) && empty($c->nav_menu)) ? base_url().'canais_conteudo/listar' : base_url().$c->classe_destino;
            if(isset($item->id) && $item->id)
            {
                if(strripos($c->classe_destino, '[id]'))
                {
                    $id_link = str_replace('[id]', $item->id, $c->classe_destino);
                }
                $link = base_url().$id_link;
                $retorno = '<a href="'.$link.'">'.$chave.'</a>';
                //$retorno = (strripos($c->classe_destino, '?')) ? '<a href="'.$link.$item->id.'">'.$chave.'</a>' : '<a href="'.$link.'/'.$item->id.'">'.urldecode($chave).'</a>';
            }
            else
            {
                $retorno = urldecode($chave);
            }
            return $retorno;
        }
        
	private function _set_cabecalho()
	{
		$retorno = '<tr>';
		if ( isset( $this->chave ) )
		{
			$retorno .= '<th><input type="checkbox" value="0" id="sel_todos" /></th>';
		}
		
		foreach ( $this->cabecalho as $c )
		{
			$retorno .= '<th class="'.( (isset($c->class)) ? $c->class : '' ).'">';
			if ( isset($c->link) )
			{
				$retorno .= '<a href="'.$c->link.'">';
				$retorno .= $c->titulo;
				$retorno .= '</a>'; 
			}
			else
			{
				$retorno .= $c->titulo;
			}
			
		}
                $retorno .= '</th>';
                if(isset($this->operacoes) && $this->operacoes)
                {
                    $retorno .= '<th colspan="'.((count($this->operacoes) > 1) ? count($this->operacoes) : '0').'" >Operação</th>';
                }
		$retorno .= '</tr>';
		return $retorno;
	}
	
	public function inicia( $config )
	{
		if ( isset( $config['cabecalho'] ) )
		{
			$this->cabecalho = $config['cabecalho'];
		}
		if ( isset( $config['itens']) )
		{
			$this->itens = $config['itens'];
		}
		if ( isset( $config['chave']) )
		{
			$this->chave = $config['chave'];
		}
		if ( isset( $config['extras']) )
		{
			$this->extras = $config['extras'];
		}
                if ( isset( $config['operacoes']) )
		{
			$this->operacoes = $config['operacoes'];
		}
                if ( isset( $config['titulo']) )
		{
			$this->titulo = $config['titulo'];
		}
                if ( isset( $config['qtde_por_linha']) )
		{
			$this->qtde_por_linha = $config['qtde_por_linha'];
		}
                if ( isset( $config['ordenacao']) )
		{
			$this->ordenacao = $config['ordenacao'];
		}
                
		return $this;
	}
	
}