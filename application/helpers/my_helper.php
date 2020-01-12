<?php
function exporta_excel($data, $name)
{
	function cleanData(&$str) 
	{ 
		$str = preg_replace("/\t/", "\\t", $str); 
		$str = preg_replace("/\r?\n/", "\\n", $str);
		$str = html_entity_decode($str,ENT_QUOTES,'UTF-8');
		$str = utf8_decode($str);
		$str = strip_tags($str);
		
		if(strstr($str, '"')) 
		{
			$str = '"' . str_replace('"', '""', $str) . '"';
		} 
		
	}	
	header("Content-Disposition: attachment; filename=".$name); 
	header("Content-Type: application/vnd.ms-excel;charset=utf-8");

	$flag = false; 
	
	foreach($data['itens'] as $row) 
	{
		$row_alt = (array)$row;
		if(!$flag) 
		{
			$cabecalho =  implode("\t", array_keys($row_alt)) . "\r\n";
			echo $cabecalho; 
			$flag = true;
		} 
		array_walk($row_alt, 'cleanData'); 
		$texto = implode("\t", array_values($row_alt)) . "\r\n";
		echo $texto;		 
	} 
	exit;
}
if ( ! function_exists('form_select'))
{
    
    function form_select($item, $selecionado = '')
    {
            $campo  = '<select '
                    . (isset($item['controller']) ? 'data-controller="'.$item['controller'] : '').'" '
                    . 'name="'.$item['nome'].'" '
                    . ''.(isset($item['extra']) ? $item['extra'] : '').' '
                    . ''.(!empty($item['disabled']) ? 'disabled="disabled"' : '').' '
                    . 'class="form-control '.( isset($item['class']) ? $item['class'] : '').''
                    . '"'
                    . 'data-tabela="'.(isset($item['tabela']) ? $item['tabela'] : 'empresas').'" '
                    . '>'.PHP_EOL;
            $campo .= '<option value=""></option>'.PHP_EOL;
            if(isset($item['valor']) && $item['valor'])
            {
                foreach ($item['valor'] as $opcao)
                {
                    $campo .= '<option value="'.$opcao->id.'"'.(($opcao->id == $selecionado) ? ' selected="selected" ' : '').' title="'.$opcao->descricao.'">';
                    $campo .= $opcao->descricao;
                    $campo .= '</option>'.PHP_EOL;
                }
            }
            $campo .= '</select>'.PHP_EOL;
            return $campo;
    }
}

if ( ! function_exists('form_checkbox_'))
{
	function form_checkbox_($item, $selecionado = array(), $tipo = 1)
	{
            $campo  = '';
            switch($tipo)
            {
                case 1 :
                    $campo .= '<div class="row">';
                    $campo .= '<div class="input-group col-lg-3" >'.PHP_EOL; 
                    $campo .= '<div class="input-group-addon" >'.PHP_EOL; 
                    $campo .= '<input type="checkbox" id="sel_todos" />';
                    $campo .= '</div>';
                    $campo .= '<input type="text" class="form-control" value="Marcar Todos">'.PHP_EOL; 
                    $campo .= '</div>';
                    foreach ($item['valor'] as $opcao)
                    {
                            $campo .= '<div class="input-group '.$item['extra'].'">';
                            $campo .= '<div class="input-group-addon" >'.PHP_EOL; 
                            $campo .= '<input class="groups" name="'.$item['nome'].'['.$opcao->id.']" id="'.$item['nome'].'" '.((array_key_exists($opcao->id,$selecionado))? 'checked="checked"' : '').' type="checkbox" value="'.$opcao->id.'">';
                            $campo .= '</div>';
                            $campo .= '<input type="text" class="form-control" value=" '.$opcao->descricao.' ">'.PHP_EOL; 
                            $campo .= '</div>';
                    }
                    $campo .= '</div>';
                    break;
                case 2 :
                    $campo .= '<div class="md-checkbox-list">';
                    foreach ($item['valor'] as $chave => $opcao)
                    {
                        $id = (isset($item['grupo']) && $item['grupo'] ? $item['grupo'] : 'opcao').'-'.$chave;
                        $campo .= '<div class="md-checkbox '.(isset($item['class']) ? $item['class'] : '').'">';
                        $campo .= '<input type="checkbox" class="md-check" name="'.$item['nome'].'[]" id="'.$id.'" '.((array_key_exists($opcao->id, $selecionado))? 'checked="checked"' : '').' value="'.$opcao->id.'" '.( isset($item['extra']) ? $item['extra'] : '' ).'/>';
                        $campo .= '<label for="'.$id.'">';
                        $campo .= '<span></span>';
                        $campo .= '<span class="check"></span>';
                        $campo .= '<span class="box"></span>';
                        $campo .= $opcao->descricao;
                        $campo .= '</label>';
                        $campo .= '</div>';
                    }
                    $campo .= '</div>';
                    break;
                case 3 :
                    $campo .= '<div class="row">';
                    $campo .= '<div class="input-group" >'.PHP_EOL; 
                    $campo .= '<div class="input-group-addon" >'.PHP_EOL; 
                    $campo .= '<input type="checkbox" id="sel_todos" />';
                    $campo .= '</div>';
                    $campo .= '<label class="form-control">Marcar Todos</label>'.PHP_EOL; 
                    $campo .= '</div>';
                    foreach ($item['valor'] as $opcao)
                    {
                            $campo .= '<div class="input-group '.(isset($item['extra']) ? $item['extra'] : '').'">';
                            $campo .= '<div class="input-group-addon" >'.PHP_EOL; 
                            $campo .= '<input class="groups" name="'.$item['nome'].'['.$opcao->id.']" id="'.$item['nome'].'" '.((array_key_exists($opcao->id,$selecionado))? 'checked="checked"' : '').' type="checkbox" value="'.$opcao->id.'">';
                            $campo .= '</div>';
                            $campo .= '<label class="form-control"> '.$opcao->descricao.' </label>'.PHP_EOL; 
                            $campo .= '</div>';
                    }
                    $campo .= '</div>';
                    break;
                case 4 :
                    $campo .= '<div class="md-checkbox-list">';
                    foreach ($item['valor'] as $chave => $opcao)
                    {
                        $id = $item['nome'];
                        $campo .= '<div class=" '.(isset($item['class']) ? $item['class'] : '').'">';
                        $campo .= '<input type="checkbox" class="md-check" name="'.$item['nome'].'[]" id="'.$id.'" '.((array_key_exists($opcao->id, $selecionado))? 'checked="checked"' : '').' value="'.$opcao->id.'" '.( isset($item['extra']) ? $item['extra'] : '' ).'/>';
                        $campo .= '<label for="'.$id.'[]">';
                        $campo .= '<span></span>';
                        $campo .= '<span class="check"></span>';
                        $campo .= '<span class="box"></span>';
                        $campo .= $opcao->descricao;
                        $campo .= '</label>';
                        $campo .= '</div>';
                    }
                    $campo .= '</div>';
                    break;
            }
            return $campo;
	}
}
function form_selecionavel($item, $selecionado = NULL, $link = TRUE, $valor = TRUE)
{
    $retorno = '<div class="list-group">';
    foreach( $item['valor'] as $data )
    {
        if ( isset($data->qtde) && $data->qtde > 0 )
        {
            $retorno .= '<a tabindex="1"';
            $retorno .= ( ( $link && isset($data->link) ) ? 'href="' . $data->link . '"' : ''  );
            $retorno .= ' class="list-group-item '.$item['link'].' item-pesquisa col-lg-4 col-sm-6 col-md-6 col-xs-12 '.( isset($selecionado) && ($data->id == $selecionado ) ? 'active' : '' ).'" data-item="' . $data->id . '" >';
            $retorno .= '<p class="list-group-item-text">' . $data->descricao . '</p> ';
            $retorno .= (isset($data->qtde) && $valor) ? '<span class="badge pull-right">' . $data->qtde . '</span>' : '';
            $retorno .= '</a>';
        }
    }
    $retorno .= '</div>';
    return $retorno;
}

/**
 * monta campo input editavel automaticamente
 * @param array $data
 * [tipo] - text, textarea
 * [classe]
 * [sequencia]
 * [class]
 * [valor]
 * [disabled]
 * [titulo]
 * [nao_salva]
 * [complemento] 
 * [helper_text] 
 * [controller] 
 * [tabela] 
 * [historico_group]
 * @return string $retorno
 */
function set_campo_editavel ( $data )
{
    $retorno = '';
    $retorno .= '<div class="form-group form-md-line-input '.(isset($data['tipo']) && $data['tipo'] == 'textarea' ? '': 'form-md-floating-label ').$data['classe'].'">';
    if (!isset($data['historico_group']) )
    {
        $retorno .= '       <button title="desfazer alteraçao" type="button" class="btn btn-danger btn-md glyphicon glyphicon-retweet hide historico '.$data['classe'].'" data-campo="'.$data['classe'].'" data-group="0"></button>';
    }
    
    //$retorno .= '   <div class="input-group">';
    $antes = '';
    $depois = '';
    $classe_ = '';
    switch( $data['tipo'] )
    {
        case 'hidden':
        case 'text':
        case 'number':
        case 'password':
            if ( isset($data['prefixo']) )
            {
                $antes .= '<div class="input-group '.(isset($data['lado_prefixo']) ? $data['lado_prefixo'] : 'left-addon') .'">';
                $antes .= '<span class="input-group-addon">'.$data['prefixo'].'</span>';
                if(!isset($data['historico_group']))
                {
                    $antes .= '<div class="input-group-control">';
                    $depois .= '</div>';
                    $depois .= '</div>';
                }
            }
            
            if(isset($data['historico_group']) && $data['historico_group'])
            {
                if( isset($data['prefixo']) )
                {
                    $depois .= '<span class="input-group-btn btn-left">';
                        $depois .= '<button title="Desfazer alteração" type="button" class="btn btn-danger glyphicon glyphicon-retweet disabled historico '.$data['classe'].'" data-campo="'.$data['classe'].'" data-group="1"></button>';
                    $depois .= '</span>';
                    $depois .= '</div>';
                }
                else
                {
                    $antes .= '<div class="input-group">';
                    $antes .= '<span class="input-group-btn btn-left"><button title="Desfazer alteração" type="button" class="btn btn-danger glyphicon glyphicon-retweet disabled historico '.$data['classe'].'" data-campo="'.$data['classe'].'" data-group="1"></button></span>';
                    $antes .= '<div class="input-group-control">';
                    $depois .= '</div>';
                    $depois .= '</div>';
                }
                
            }
            $retorno .= $antes;
            $retorno .= '<input '
                            . 'name="'.$data['classe'].'" '
                            .( isset($data['disabled']) ? 'disabled="disabled"' : '' ).' '
                            . 'type="'.$data['tipo'].'" '
                            . 'class="form-control campo-'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).' '.(isset($data['class']) ? $data['class'] : '').'" ';
            $retorno .= 'data-sequencia="'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).'" ';
            $retorno .= isset($data['nao_salva']) ? 'data-nao-salva="1" ' : '';
            $retorno .= 'data-controller="'.(isset($data['controller']) ? $data['controller'] : 'empresas').'" ';
            $retorno .= 'data-tabela="'.(isset($data['tabela']) ? $data['tabela'] : 'empresas').'" ';
            $retorno .= isset($data['extra']) ? $data['extra'] : '';
            $retorno .= 'id="'.$data['classe'].'" '
                            . 'value="'.$data['valor'].'">';
            $retorno .= '   <label for="'.$data['classe'].'">'.$data['titulo'];
            $retorno .= '   </label>';
            $retorno .= $depois;
            break;
        case 'textarea':
            $retorno .= '<span class="pull-right text-info">qtde caracteres: <span class="contador_'.$data['classe'].'"></span></span>
                            <textarea 
                                name="'.$data['classe'].'" '
                                . ' class="form-control contavel campo-'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).( isset($data['class']) ? ' '.$data['class'] : '' ).'" '
                                . 'data-sequencia="'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).'" '
                                . ''
                . 'data-controller="'.(isset($data['controller']) ? $data['controller'] : 'empresas').'" '
                . 'data-tabela="'.(isset($data['tabela']) ? $data['tabela'] : 'empresas').'" '
                . 'id="'.$data['classe'].'" '
                . (isset($data['nao_salva']) ? 'data-nao-salva="1" ' : '')
                                .(isset( $data['extra'] ) ? $data['extra'] : '').'>'
                                    . $data['valor'].'</textarea>'
                . '<script type="javascript">$(function(){contador.por_classe("#'.$data['classe'].'", ".contador_'.$data['classe'].'");});</script>';
            $retorno .= '   <label for="'.$data['classe'].'">'.$data['titulo'];
            $retorno .= '   </label>';
            break;
    }
    //$retorno .= '   </div>';
    $retorno .= isset($data['complemento']) ? $data['complemento'] : '';
    $retorno .= '<p class="'.$data['classe'].' help-block">'.( isset($data['helper_text']) ? $data['helper_text'] : '' ).'</p>';
    $retorno .= '</div>';
    return $retorno;
}

/**
 * monta botao editavel automaticamente
 * @param array $data
 * [classe]
 * [valor]
 * [texto][on]
 * [texto][off]
 * [datas][inicio]
 * [datas][fim]
 * [complemento] string/array com os campos que ccomplementao e usam o mesma base de classe
 * @return string $retorno
 */
function set_botao_editavel ( $data )
{
    $on = 'success';
    $off = 'danger';
    if ( isset($data['reverse']) )
    {
        $on = 'danger';
        $off = 'success';
    }
    $item = ( isset($data['valor']) && $data['valor'] ) ? $data['valor'] : FALSE;
    $retorno = '<div class="form-group">';
    $expande = 0;
    $abre = '';
    $fecha = '';
    if ( isset($data['datas']) || isset($data['complemento']) )
    {
        $expande = 1;
        $abre .= '<div class="alert alert-'.( $item ? $on : $off ).' expansivo-'.$data['classe'].'">';
        $abre .= '<div class="row">';
        $abre .= '<div class="col-lg-12 col-md-12 col-sm-12 cool-xs-12">';
        
        $fecha .= '</div>';
        $fecha .= '</div>';
        $fecha .= '<div class="'.( $item ? 'show' : 'hide' ).' expansivo">';
        if ( isset($data['datas']) )
        {
            $fecha .= '<div class="row">';
            $fecha .= '<div class="col-lg-6 col-md-6 col-sm-6 cool-xs-6">';
            $inicio = array(
                            'tipo' => 'text',
                            'controller' => isset($data['controller']) ? $data['controller'] : 'empresas',
                            'classe' => isset($data['datas']['inicio']['classe']) ? $data['datas']['inicio']['classe'] : $data['classe'].'_inicio',
                            'class' => 'data',
                            'valor' => $data['datas']['inicio']['valor'],
                            'titulo' => $data['texto']['on'].' Inicio',
                            );
            $fecha .= set_campo_editavel($inicio);
            
            $fecha .= '</div>';
            $fecha .= '<div class="col-lg-6 col-md-6 col-sm-6 cool-xs-6">';
            $fim = array(
                            'tipo' => 'text',
                            'controller' => isset($data['controller']) ? $data['controller'] : 'empresas',
                            'classe' => isset($data['datas']['fim']['classe']) ? $data['datas']['fim']['classe'] : $data['classe'].'_termino',
                            'class' => 'data',
                            'valor' => $data['datas']['fim']['valor'],
                            'titulo' => $data['texto']['on'].' Termino',
                        );
            $fecha .= set_campo_editavel($fim);
            $fecha .= '';
            $fecha .= '</div>';
            $fecha .= '</div>';
            
        }
        if ( isset($data['complemento']) )
        {
            $fecha .= $data['complemento'];
            
        }
        $fecha .= '</div>';
        $fecha .= '</div>';
    }
    $retorno .= $abre;
    $retorno .= '<button '
                        . 'class="form-control btn-acao '.$data['classe'].' btn btn-'.( $item ? $on : $off).'" '
                        . 'data-reverse="'.( isset($data['reverse']) ? 1 : 0 ).'" '
                        . 'data-item="'.( $item ? 1 : 0 ).'" '
                        . 'data-controller = '.( isset($data['controller']) ? $data['controller'] : 'empresas' ).' '
                        . 'data-campo="'.$data['classe'].'" '
                        . 'data-expande="'.$expande.'" '
                        . (isset($data['nao_salva']) ? 'data-nao-salva="1"' : '')
                        . 'data-marcado="'.$data['texto']['on'].'" '
                        . 'data-desmarcado="'.$data['texto']['off'].'" '
                        . (isset($data['extra']) ? $data['extra'] : '')
                        . 'type="button" >'
                        . ( $item ? $data['texto']['on'] : $data['texto']['off'] ).'
                </button>
                <div class="help-block '.$data['classe'].'"></div>';
    $retorno .= $fecha;
    $retorno .= '</div>';
    return $retorno;
}

/**
 * monta switch editavel automaticamente
 * @param array $data
 * [classe]
 * [valor]
 * [texto][on]
 * [texto][off]
 * [datas][inicio]
 * [datas][fim]
 * [complemento] string/array com os campos que complementao e usam o mesma base de classe
 * @return string $retorno @type string
 */
function set_switch_editavel ( $data )
{
    $on = isset($data['btn']['on']) ? $data['btn']['on'] : 'success';
    $off = isset($data['btn']['off']) ? $data['btn']['off'] :'danger';
    if ( isset($data['reverse']) )
    {
        $on = isset($data['btn']['off']) ? $data['btn']['off'] : 'success';
        $off = isset($data['btn']['on']) ? $data['btn']['on'] :'danger';
    }
    $item = ( isset($data['valor']) && $data['valor'] ) ? $data['valor'] : FALSE;
    if(!isset($data['texto']['on']))
    {
        $data['texto']['on'] = 'Sim';
    }
    if(!isset($data['texto']['off']))
    {
        $data['texto']['off'] = 'Não';
    }
    $label = (isset($data['texto']['label']) ? $data['texto']['label'] : FALSE );
    $retorno = '<div class="form-group">';
    $expande = 0;
    $abre = '';
    $fecha = '';
    if ( isset($data['datas']) || isset($data['complemento']) )
    {
        $expande = 1;
        $abre .= '<div class="panel panel-'.( $item ? $on : $off ).' expansivo-'.$data['classe'].'">';
        $abre .= '<div class="panel-heading">{campo}</div>';
        $abre .= '<div class="panel-body expansivo" style="display:'.( $item ? 'block' : 'none' ).'">';
        $abre .= '<div class="row">';
        $abre .= '<div class="col-lg-12 col-md-12 col-sm-12 cool-xs-12">';
        
        $fecha .= '</div>';
        $fecha .= '</div>';
        if ( isset($data['datas']) )
        {
            $fecha .= '<div class="row">';
            $fecha .= '<div class="col-lg-6 col-md-6 col-sm-6 cool-xs-6">';
            $inicio = array(
                            'tipo' => 'text',
                            'controller' => isset($data['controller']) ? $data['controller'] : 'sintomas',
                            'tabela' => isset($data['tabela']) ? $data['tabela'] : 'sintomas',
                            'classe' => isset($data['datas']['inicio']['classe']) ? $data['datas']['inicio']['classe'] : $data['classe'].'_inicio',
                            'class' => 'data',
                            'valor' => $data['datas']['inicio']['valor'],
                            'titulo' => ($label ? $data['texto']['label'] : $data['texto']['texto']).' Inicio',
                            );
            $fecha .= set_campo_editavel($inicio);
            
            $fecha .= '</div>';
            $fecha .= '<div class="col-lg-6 col-md-6 col-sm-6 cool-xs-6">';
            $fim = array(
                            'tipo' => 'text',
                            'controller' => isset($data['controller']) ? $data['controller'] : 'sintomas',
                            'tabela' => isset($data['tabela']) ? $data['tabela'] : 'sintomas',
                            'classe' => isset($data['datas']['fim']['classe']) ? $data['datas']['fim']['classe'] : $data['classe'].'_termino',
                            'class' => 'data',
                            'valor' => $data['datas']['fim']['valor'],
                            'titulo' => ($label ? $label : $data['texto']['texto']).' Termino',
                        );
            $fecha .= set_campo_editavel($fim);
            $fecha .= '';
            $fecha .= '</div>';
            $fecha .= '</div>';
            
        }
        if ( isset($data['complemento']) )
        {
            $fecha .= $data['complemento'];
            
        }
        $fecha .= '</div>';
        $fecha .= '</div>';
    }
    else
    {
        $abre = '{campo}';
    }
    $retorno .= $abre;
    $campo = ''
                        . ($label ? '' : '<label for="'.$data['id'].'">'.$data['texto']['texto'].'</label> ')
                        . '<input '
                        . 'class="form-control switch-acao '.$data['classe'].' btn '.( $item ? $on : $off).'" '
                        . 'id="'.$data['id'].'"'
                        . 'data-reverse="'.( isset($data['reverse']) ? 1 : 0 ).'" '
                        . 'data-onColor="'.$on.'" '
                        . 'data-offColor="'.$off.'" '
                        . 'data-item="'.( $item ? 1 : 0 ).'" '
                        . 'data-controller = '.( isset($data['controller']) ? $data['controller'] : 'sintomas' ).' '
                        . 'data-tabela = '.( isset($data['tabela']) ? $data['tabela'] : 'sintomas' ).' '
                        . 'data-nao-salva = "1"'
                        . 'data-salva = "'.( isset($data['nao_salva']) ? $data['nao_salva'] : '0' ).'" '
                        . 'data-campo="'.$data['id'].'" '
                        . 'data-sequencia="'.$data['sequencia'].'" '
                        . 'data-expande="'.$expande.'" '
                        . 'data-marcado="'.$data['texto']['on'].'" '
                        . 'data-desmarcado="'.$data['texto']['off'].'" '
                        . 'data-label="'.$label.'" '
                        . 'type="checkbox" >';
//                        . '<div class="help-block '.$data['classe'].'"></div>';
    $retorno .= $fecha;
    $retorno = str_replace('{campo}', $campo, $retorno);
    $retorno .= '</div>';
    return $retorno;
}

function set_image_editavel ( $data )
{
    $retorno = '';
    $retorno .= '<div class="form-group '.$data['classe'].'">';
    $retorno .= '<div class="alert alert-success">';
    $retorno .= '<label for="'.$data['classe'].'">'.$data['titulo'].'</label>';
    $retorno .= '<div class="espaco-image">';
    if ( isset($data['image'] ) && ! empty($data['image']) ) 
    {
        $retorno .= '<center><img src="'.$data['image'].'" class="image img-responsive" data-item="'.$data['classe'].'" ></center>';
        $retorno .= '<button type="button" class="btn btn-danger form-control deleta-image" data-item="'.$data['classe'].'" data-controller="'.(isset($data['controller']) ? $data['controller'] : 'empresas').'" '.(isset($data['extras']) ? $data['extras'] : '').'>Deletar</button>';
    }
    else
    {
        $retorno .= '<button type="button" class="btn btn-success image form-control" data-item="'.$data['classe'].'" data-titulo="'.$data['titulo'].'" data-controller="'.( isset($data['controller']) ? $data['controller'] : 'empresas').'" '.(isset($data['extras']) ? $data['extras'] : '').'>Upload '.$data['titulo'].'</button>';
    }
    $retorno .= '</div>';
    $retorno .= isset( $data['complemento'] ) ? $data['complemento'] : '';
    $retorno .= '</div>';
    $retorno .= '</div>';
    
    return $retorno;
}

function converte_data_mysql($data)
{
    $data_explode = explode(' ', $data);
    $data_i = explode('/',$data_explode[0]);
    $data = $data_i[2].'-'.str_pad($data_i[1], 2, '0', STR_PAD_LEFT).'-'.$data_i[0].' '.( isset($data_explode[1]) ? $data_explode[1] : '00:00' );
    return $data;
}

function reverte_data_mysql($data)
{
    if( ! empty( $data ))
    { 
	$data_explode = explode(' ', $data);
	$data_i = explode('-',$data_explode[0]);
	$data = $data_i[2].'/'.str_pad($data_i[1], 2, '0', STR_PAD_LEFT).'/'.$data_i[0].' '.( isset($data_explode[1]) ? $data_explode[1] : '' );
    }
    return $data;
}

function reverte_data_unixtime($time)
{
    if( ! empty( $time ) )
    { 
        $time = date('d/m/Y H:i', $time);
    }
    return $time;
}

function converte_data_unixtime($data)
{
        list($data, $hora) = explode(' ', $data);
    if (strstr($data, '-') )
    {
	$data = explode('-', $data);
        $d = array($data[2],$data[1],$data[0]);
        
    }
    else
    {
	$data = explode('/', $data);
        $d = $data;
    }
	$hora = explode(':', $hora);
	$data = mktime((isset($hora[0]) ? $hora[0] : 0), (isset($hora[1]) ? $hora[1] : 0),(isset($hora[2]) ? $hora[2] : 0), $d[1], $d[0],$d[2]);
        
	return $data;
}

function set_time_to_data_pt_br($data = '')
{
    $retorno = '';
    if ( !empty($data) )
    {
	$retorno = strstr($data,'/') ? $data : date('d-m-Y H:i', $data);
    }
    return $retorno;
}

function mes_ano($data)
{
    $retorno = array('mes' => NULL,'ano' => NULL);
    if(isset($data) && $data)
    {
        
        if(isset(explode(' ',$data)[1]))
        {
            list($data, $hora) = explode(' ', $data);
        }
	$data = explode('/', $data);
	$retorno['mes'] = $data[1];
	$retorno['ano'] = $data[2];
    }
	return $retorno;
}

function tira_acento ( $palavra , $link = FALSE )
{		
    //$palavra = strtolower($palavra);
    //$array_a = array('  ', '   ', 'Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','/','-','´','!', "'",'"','º','(',')','=','%','�',',','$', ' ','<','>','?','!','+++','++','+-+',':','...', '_','-','1','2','3','4','5','6','7','8','9','0 ','1 ','2 ','3 ','4 ','5 ','6 ','7 ','8 ','9 ','0 ');
    //$array_b = array('+' , '+'  , 'a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','', '', '', '', '+','+','+','+','+','+','+','+','+','S', '+' ,'+','+','+','+', ''  ,''  , '', '','', '','','','','','','','','','','',' ','','','','','','','','','','');
    //$retorno = str_replace($array_a, $array_b, $palavra);
    //return $retorno;
    
    //$palavra = strtolower($palavra);
        $array_a = array('  ', '   ', 'Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','/','-','´','!', "'",'"','º','(',')','=','%','�',',','$', ' ','<','>','?','!','+++','++','+-+',':','...', '_','–','.','#','0',',');
    if ( $link )
    {
        $array_b = array('_' , '_'  , 'a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','', '', '', '',  '', '', '', '', '', '', '', '', '', '' ,'_', '' ,'' , '', '', ''  , ''  ,''  , '', ''  , '_','' ,'' , '','' ,'_');
    }
    else
    {
        $array_b = array('+' , '+'  , 'a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','', '', '', '',  '', '', '', '', '', '', '', '', '', '',  '+', '','', '', '', ''  , ''  ,'',   '', ''   , '+','' ,'', '' ,'','+' );
    }
    $retorno = str_replace($array_a, $array_b, $palavra);
    $sim = 0;
    for( $a = 0; $a <= 9; $a++ )
    {
        $e = stripos($retorno, strval($a) );
        if ( ( $e && $e < 2 ) || $e == 0 )
        {
            $sim = 1;
            $retorno = str_replace(strval($a), '', $retorno);
        }
    }
    
    if ( $sim )
    {
        for( $a = 0; $a <= 9; $a++ )
        {
            $e = stripos($retorno, strval($a) );
            if ( ( $e && $e < 2 ) || $e == 0 )
            {
                $retorno = str_replace(strval($a).'+', '', $retorno);
            }
        }
    }
    if ( substr($retorno, 0, 1) == '+' )
    {
        $retorno = substr($retorno, 1);
    }
    return strtolower($retorno);
} 

/*
function calcular_intervalo_tempo($data_inicio = '', $hora_inicio = '', $data_fim = NULL, $hora_fim = NULL)
{
    $retorno = NULL;
        
    $dt_ini = getMicroTime($data_inicio);
    $hr_ini = DateTime::createFromFormat('H:i:s', $hora_inicio);
    
    if(isset($data_fim) && $data_fim)
    {
        $dt_fim = getMicroTime($data_fim);
        $hr_fim = DateTime::createFromFormat('H:i:s', $hora_fim);
    }
    else
    {
        $dt_fim = getMicroTime(date('d-m-Y'));
        $hr_fim = DateTime::createFromFormat('H:i:s', date('H:i:s'));
    }
    
    $diferenca = $dt_fim - $dt_ini;
    $dias = (int)floor( $diferenca / (60 * 60 * 24));
    
    $intervalo = $hr_ini->diff($hr_fim);
    $retorno = array('dias' => $dias, 'horas' => $intervalo->format('%H:%I:%S'));
        
    return $retorno;
}
*/

function getMicroTime($data)
{
    $partes = explode('-', $data);
    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function get_horario_comercial()
{
    $hr_ini = '08:00';
    $periodo[] = $hr_ini;
    $hora;
    for($i = 1; $i < 19; $i++)
    {
        if(empty($hora))
        {
            $periodo[$i] = date('H:i', strtotime('+30 minute', strtotime($hr_ini)));
        }
        else
        {
            $periodo[$i] = date('H:i', strtotime('+30 minute', strtotime($hora[$i-1])));
        }
        $hora = $periodo;
    }
    return $periodo;
}

function get_tipo_negocio()
{
    $array = array(
        (object)array('id' => 'venda', 'descricao' => 'Venda'),
        (object)array('id' => 'locacao', 'descricao' => 'Locação'),
        (object)array('id' => 'locacao_dia', 'descricao' => 'Locação Dia'),
    );
    return $array;
}

/*
 * Cria diretorios que não existem recursivamente.
 * Ex.: images/teste/empresa/83002/  
 * Vai percorrer cada diretorio e se não existir criá-lo, utilizada em conjunto com
 * função de fazer upload.
 * 
 * @author: Breno Henrique Moreno Nunes
 * @param: string $diretorio Ex.: images/teste/empresa/83002/ 
 */
function criar_diretorios($diretorio = NULL){
    
    if (!is_dir($diretorio) )
    {
        $temp = str_replace('\\', '/', $diretorio);
        $temp = explode('/', $temp);
        $path = $temp[0];
        $qtde = count($temp);
        $i = 0;
        while($i < $qtde)
        {
            if(!is_dir($path)) {  mkdir($path, 0777,true); }
            $i++;
            if($i < $qtde){ $path .= '/'.$temp[$i]; }
        }
    }
}

function get_select_frequencia()
{
    $array = array(
                    (object)array('id' => 'unico', 'descricao' => 'Unico'),
                    (object)array('id' => 'diaria', 'descricao' => 'Diária'),
                    (object)array('id' => 'semanal', 'descricao' => 'Semanal'),
                    (object)array('id' => 'quinzenal', 'descricao' => 'Quinzenal'),
                    (object)array('id' => 'mensal', 'descricao' => 'Mensal'),
                    );
    return $array;
}

function get_select_impacto()
{
    $array = array(
                    (object)array('id' => '9', 'descricao' => 'Muito Grande'),
                    (object)array('id' => '7', 'descricao' => 'Grande'),
                    (object)array('id' => '5', 'descricao' => 'Médio'),
                    (object)array('id' => '3', 'descricao' => 'Pequeno'),
                    (object)array('id' => '1', 'descricao' => 'Muito Pequeno'),
                    );
    return $array;
}

function get_tempo( $array = FALSE )
{
    $retorno = 0;
    if ( $array && count($array) > 0 )
    {
        $tempo = 0;
        foreach( $array as $data )
        {
            $time_fim = retorna_time( $data->data_fim );
            $time_inicio = retorna_time( $data->data_inicio );
            $soma = $tempo + ($time_fim - $time_inicio);
            $tempo = $soma;
        }
        $retorno = ($tempo / 3600);
    }
    return $retorno;
    
}

function retorna_time( $data )
{
    $tempo = explode(' ',$data);
    $dia = explode('-', $tempo[0]);
    $hora = explode(':', $tempo[1]);
    $time = mktime($hora[0], $hora[1], $hora[2], $dia[1], $dia[2], $dia[0]);
    return $time;
}

function verifica_image( $image = NULL )
{
    if ( isset($image) )
    {
        return TRUE;
        /*
        $header_image = get_headers( $image, 1 );
        //var_dump($header_image);
        if ( strstr( $header_image[0], 'OK' ) )
        {
            $retorno = TRUE;
        }
        else
        {
            $retorno = FALSE;
        }
         * 
         */
    }
    else
    {
        $retorno = FALSE;
    }
    return $retorno;
}

function set_dayweek( $dia = 0 )
{
    $array = array('Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado');
    return $array[$dia];
}

function set_number_format( $number = 0, $casas = 0 )
{
    return number_format($number, $casas, ',', '.');
}

function set_aprovado( $valor = FALSE )
{
    $retorno = 'Não Aprovado';
    if ( $valor )
    {
        $retorno = 'Aprovado';
    }
    return $retorno;
}

function set_btn_contrato($inscricao)
{
    $retorno = '<a href="http://www.portaisimobiliarios.com.br/contrato/contratoKK.php?inscricao='.$inscricao.'&base=empresas_auxiliar" class="btn btn-primary" target="_blank">Contrato Imóveis</a>';
    $retorno .= '<a href="http://www.powinternet.com/contrato_powsites/contrato_reimpresso.php?inscricao='.$inscricao.'&base=empresas_auxiliar" class="btn btn-default" target="_blank">Contrato pow site</a>';
    $retorno .= '<a href="http://www.guiasjp.com/contrato.php?inscricao='.$inscricao.'&base=empresas_auxiliar" class="btn btn-primary" target="_blank">Contrato guiasjp</a>';
    return $retorno;
}

function set_valores_produto($valor,$complemento)
{
    // 0 = venda
    // 1 = locacao
    // 2 = locacao dia

    $valores = explode('|', $valor);
    $retorno = '';
//    $retorno = '<div class="row">';

    if ($valores[0] >= 0.1)
    {
        $retorno .= '<div class="col-sm-12">Preço: R$<span class="valor_produto_'.$complemento.'_venda">' . number_format($valores[0], 2, ',', '.') . '</span></div>';
    }

    if ($valores[1] >= 0.1)
    {
        $retorno .= '<div class="col-sm-12">Preço de: R$<span class="valor_produto_'.$complemento.'_locacao">' . number_format($valores[1], 2, ',', '.') . '</span></div>';
//        $retorno .='</div>';
    }

    if ($valores[2] >= 0.1)
    {
        $retorno .= '<div class="col-sm-12">Preço por: R$<span class="valor_produto_'.$complemento.'_locacao_dia">' . number_format($valores[2], 2, ',', '.') . '</span></div>';
//        $retorno .='</div>';
    }
    return $retorno;
}
function set_valores_imovel($valor,$complemento)
{
    // 0 = venda
    // 1 = locacao
    // 2 = locacao dia

    $valores = explode('-', $valor);
    $retorno = '';
//    $retorno = '<div class="row">';

    if ($valores[0] >= 0.1)
    {
        $retorno .= '<div class="col-sm-4">Venda: R$<span class="valor_imovel_'.$complemento.'_venda">' . number_format($valores[0], 2, ',', '.') . '</span></div>';
    }

    if ($valores[1] >= 0.1)
    {
        $retorno .= '<div class="col-sm-4">Locação: R$<span class="valor_imovel_'.$complemento.'_locacao">' . number_format($valores[1], 2, ',', '.') . '</span></div>';
//        $retorno .='</div>';
    }

    if ($valores[2] >= 0.1)
    {
        $retorno .= '<div class="col-sm-4">Locação dia: R$<span class="valor_imovel_'.$complemento.'_locacao_dia">' . number_format($valores[2], 2, ',', '.') . '</span></div>';
//        $retorno .='</div>';
    }
    return $retorno;
}

function set_valores_imovel_input($valor,$complemento)
{
    // 0 = venda
    // 1 = locacao
    // 2 = locacao dia

    $valores = explode('-', $valor);
    $retorno = '';
    if ($valores[0] >= 0.1)
    {
        $retorno .= '<div class="form-group">'
                        . '<label>Valor de venda do imóvel : </label>'
                        . '<div class="input-group">'
                            . '<span class="input-group-addon">R$</span>'
                            . '<input type="text" class="form-control reais valores preco" name="preco_venda" data-item="'.$complemento.'" placeholder="Preço do imóvel" value="' . number_format($valores[0], 2, ',', '.') . '">'
                            . '<input type="hidden" class="hide imovel_'.$complemento.'" value="'.$complemento.'">'
                        . '</div>'
                    . '</div>';
    }

    if ($valores[1] >= 0.1)
    {
        $retorno .= '<div class="form-group">'
                        . '<label>Valor de locação do imóvel : </label>'
                        . '<div class="input-group">'
                            . '<span class="input-group-addon">R$</span>'
                            . '<input type="text" class="form-control valores preco" name="preco_locacao" data-item="'.$complemento.'" placeholder="Preço do imóvel" value="' .  number_format($valores[1], 2, ',', '.') . '">'
                            . '<input type="hidden" class="hide imovel_'.$complemento.'" value="'.$complemento.'">'
                        . '</div>'
                    . '</div>';
    }

    if ($valores[2] >= 0.1)
    {
        $retorno .= '<div class="form-group">'
                        . '<label>Valor de locação por dia do imóvel: </label>'
                        . '<div class="input-group">'
                            . '<span class="input-group-addon">R$</span>'
                            . '<input type="text" class="form-control valores preco" name="preco_locacao_dia" data-item="'.$complemento.'" placeholder="Preço do imóvel" value="' . number_format($valores[2], 2, ',', '.') . '">'
                            . '<input type="hidden" class="hide imovel_'.$complemento.'" value="'.$complemento.'">'
                        . '</div>'
                    . '</div>';
    }

    return $retorno;
}

function set_checkbox_imovel($tipo,$complemento){
    $retorno = '';
    $campo = '';
    $ex = explode('-',$tipo);
    $retorno .= '<div class="mt-checkbox-list">';
    foreach ($ex as $tipo => $status)
    {
        $retorno .= '<label class="mt-checkbox">';
        $retorno .= 'Imóvel ';
        switch ($tipo)
        {
            case 0:
                $campo = 'invisivel';
                $retorno .= 'Invisivel';
                break;
            case 1:
                $campo = 'reservaimovel';
                $retorno .= 'Reservado';
                break;
            case 2:
                $campo = 'vendido';
                $retorno .= 'Vendido';
                break;
            case 3:
                $campo = 'locado';
                $retorno .= 'Locado';
                break;
        }
        $retorno .= '<input type="checkbox" '.($status == 1 ? 'checked="checked"' : '').' name="'.$campo.'" value="1" class="" data-item="'.$complemento.'">';
        $retorno .= '<span></span>';
        $retorno .= '</label>';
        $retorno .= '<input type="hidden" class="hide imovel_'.$complemento.'" value="'.$complemento.'">';
    }
    $retorno .= '</div>';
    
    return $retorno;
}


function set_foto_imovel($valor, $id_empresa = NULL)
{
    if(is_array($valor))
    {
        if(isset($valor['itens']))
        {
            $valor = $valor['itens'][0]->arquivo;
        }
        elseif(isset($valor[0]->arquivo))
        {
            $valor = $valor[0]->arquivo;
        }
        else
        {
            $valor = base_url().'images/usuarios/sem-imagem.png';
        }
    }
    if ( ! strstr($valor,'http') )
    {
        $valor = str_replace('codEmpresa',$id_empresa, URL_IMAGE_MUDOU).$valor;
    }
    $retorno = '<img src="' . $valor . '" class="img-responsive">';
    return $retorno;
}
function set_foto_produto($valor, $id_empresa = NULL)
{
    if(is_array($valor))
    {
        if(isset($valor['itens']))
        {
            $valor = $valor['itens'][0]->arquivo;
        }
        elseif(isset($valor[0]->arquivo))
        {
            $valor = $valor[0]->arquivo;
        }
        else
        {
            $valor = 'http://www.placehold.it/640x360/EFEFEF/AAAAAA&amp;text=sem+imagem';
        }
    }
    elseif(!$valor)
    {
        $valor = 'http://www.placehold.it/640x360/EFEFEF/AAAAAA&amp;text=sem+imagem';
    }
    if ( ! strstr($valor,'http') )
    {
        $valor = str_replace('codEmpresa',$id_empresa, 'http://www.pow.com.br/powsites/codEmpresa/olc/').$valor;
    }
    $retorno = '<img src="' . $valor . '" class="img-responsive img-thumbnail">';
    return $retorno;
}
function set_foto_banner($valor, $id_empresa = NULL)
{
    if(is_array($valor))
    {
        if(isset($valor['itens']))
        {
            $valor = $valor['itens'][0]->arquivo;
        }
        elseif(isset($valor[0]->arquivo))
        {
            $valor = $valor[0]->arquivo;
        }
        else
        {
            $valor = 'http://www.placehold.it/640x360/EFEFEF/AAAAAA&amp;text=sem+imagem';
        }
    }
    elseif(!$valor)
    {
        $valor = 'http://www.placehold.it/640x360/EFEFEF/AAAAAA&amp;text=sem+imagem';
    }
    if ( ! strstr($valor,'http') )
    {
        $valor = str_replace('codEmpresa',$id_empresa, 'http://www.pow.com.br/powsites/codEmpresa/ban/').$valor;
    }
    $retorno = '<img src="' . $valor . '" class="img-responsive img-thumbnail">';
    return $retorno;
}

/**
 * @deprecated 01/08/2016 trocado por com_curl logo abaixo
 * @param type $local
 * @param type $arquivo
 * @param type $propriedades
 * @param type $width
 * @param type $height
 * @param type $crop
 * @return boolean
 */
function gera_image( $local, $arquivo, $propriedades, $width, $height, $crop = FALSE )
{
    $altura = $propriedades[0];
    if ( $height == 'auto' )
    {
        $height = ( $propriedades[1] / ( $propriedades[0] / $width ) );
    }
    if ( $propriedades[0] > $width || $propriedades[1] > $height )
    {
        $altera = TRUE;
    }
    else
    {
        $altera = FALSE;
    }
    //var_dump($height, $propriedades, $width); die();
    if ( $altera )
    {
        if ( $propriedades[0] > $propriedades[1] )
        {
            $proporcao = ceil($width / $propriedades[0] );
        }
        else
        {
            $proporcao = ceil($height / $propriedades[1] );
        }
        
        $width_src = $propriedades[0];
        $height_src = $propriedades[1];
        $width_fator = $crop ? ( ( $propriedades[0] - $width ) / 2 ) : 0;
        $height_fator = $crop ? ( ($propriedades[1] - $height) / 2 ) : 0;
        if ( ! $altera )
        {
            $width = $propriedades[0];
            $height = $propriedades[1];
        }
        $image_destino = imagecreatetruecolor($width,$height);
        switch( $propriedades['mime']){
                case 'image/gif':
                        $image = imagecreatefromgif($local);
                        break;
                case 'image/png':
                        $image = imagecreatefrompng($local);
                        break;
                case 'image/jpeg':
                default:
                        $image = imagecreatefromjpeg($local);
                        break;
        }
        if ( ! $crop )
        {
            $width_src = $width;
            $height_src = $height;
            $width_fator = 0;
            $height_fator = 0;
        }

        //var_dump($height, $width, $width_src, $height_src, $propriedades, $proporcao);
        //die();
        imagecopyresampled($image_destino,$image,0,0,0,0,$width,$height,$propriedades[0],$propriedades[1]);
        $arq = fopen($arquivo,'w');
        fclose($arq);
        switch($propriedades['mime']){
                case 'image/gif':
                        imagegif($image_destino, $arquivo);
                        break;
                case 'image/png':
                        imagepng($image_destino, $arquivo);
                        break;
                case 'image/jpeg':
                default:
                        imagejpeg($image_destino, $arquivo,100);
                        break;
        }
    }
    else
    {
        copy($local, $arquivo);
    }
    if ( file_exists($arquivo) && filesize($arquivo) > 2000  )
    {
        $retorno = TRUE;
    }
    else
    {
        $retorno = FALSE;
    }
    return $retorno;
}

function gera_image_com_curl( $curl, $arquivo, $original, $width, $height, $crop = FALSE )
{
    if ( ! file_exists($original) )
    {
        $temp_file = fopen($original,'x');
        fwrite($temp_file, $curl['item']);
        fclose($temp_file);
    }
    $propriedades = getimagesize($original);
    
    $altura = $propriedades[0];
    if ( $height == 'auto' )
    {
        $height = ( $propriedades[1] / ( $propriedades[0] / $width ) );
    }
    if ( $propriedades[0] > $width || $propriedades[1] > $height )
    {
        $altera = TRUE;
    }
    else
    {
        $altera = FALSE;
    }
    //var_dump($height, $propriedades, $width); die();
    if ( $altera )
    {
        if ( $propriedades[0] > $propriedades[1] )
        {
            $proporcao = ceil($width / $propriedades[0] );
        }
        else
        {
            $proporcao = ceil($height / $propriedades[1] );
        }
        
        $width_src = $propriedades[0];
        $height_src = $propriedades[1];
        $width_fator = $crop ? ( ( $propriedades[0] - $width ) / 2 ) : 0;
        $height_fator = $crop ? ( ($propriedades[1] - $height) / 2 ) : 0;
        if ( ! $altera )
        {
            $width = $propriedades[0];
            $height = $propriedades[1];
        }
        $image_destino = imagecreatetruecolor($width,$height);
        switch( $propriedades['mime']){
                case 'image/gif':
                        $image = imagecreatefromgif($original);
                        break;
                case 'image/png':
                        $image = imagecreatefrompng($original);
                        break;
                case 'image/jpeg':
                default:
                        $image = imagecreatefromjpeg($original);
                        break;
        }
        if ( ! $crop )
        {
            $width_src = $width;
            $height_src = $height;
            $width_fator = 0;
            $height_fator = 0;
        }

        //var_dump($height, $width, $width_src, $height_src, $propriedades, $proporcao);
        //die();
        imagecopyresampled($image_destino,$image,0,0,0,0,$width,$height,$propriedades[0],$propriedades[1]);
        $arq = fopen($arquivo,'w');
        fclose($arq);
        switch($propriedades['mime']){
                case 'image/gif':
                        imagegif($image_destino, $arquivo);
                        break;
                case 'image/png':
                        imagepng($image_destino, $arquivo);
                        break;
                case 'image/jpeg':
                default:
                        imagejpeg($image_destino, $arquivo,50);
                        break;
        }
    }
    else
    {
        copy($original, $arquivo);
    }
    if ( file_exists($arquivo) && filesize($arquivo) > 2000  )
    {
        $retorno = TRUE;
    }
    else
    {
        $retorno = FALSE;
    }
    return $retorno;
}

function set_arquivo_destaque($image){
    $arquivo = str_replace('{{id_empresa}}', $image->id_empresa, URL_IMAGE_GEROU_FORMATO);
    $arquivo = str_replace('{{id_imovel}}', $image->id_imovel, $arquivo);
    $arquivo = str_replace('{{id_imovel}}', $image->id_imovel, $arquivo);
    $arquivo = str_replace('{{id_image}}', $image->id, $arquivo);
    $arquivo = str_replace('{{extensao}}', $image->extensao, $arquivo);
    return URL_IMAGE_GEROU.$arquivo;
    
}


/**
 * retorna a imagem para exibição
 * @param int $id -> id do imóvel
 * @param string $arquivo -> arquivo a ser modificado
 * @param int $id_empresa -> codigo da empresa
 * @param bolean $mudou -> se a empresa já mudou de repositorio
 * @param string $fs -> 32bits md5 do arquivo
 * @param int $sequencia -> sequencia do arquivo - default 1
 * @param string $tipo -> tipo do arquivo, tm, t5, t3
 * @return string -> endereço completo do arquivo.
 */
function set_arquivo_image( $id, $arquivo, $id_empresa, $mudou = FALSE, $fs = '', $sequencia = 1, $tipo = 'TM', $gera = FALSE)
{
//    var_dump($id, $arquivo, $id_empresa);die();
    $gera = FALSE;
    $endereco_base = '';
    if ( LOCALHOST )
    {
        $endereco_base = str_replace(['admin2_0','portais_novo','portais_3'], 'images/portais', base_url());
    }
    else
    {
        $endereco_base = URL_IMAGE_GEROU;
    }
    // 120 x 90 -> TM -> só faz da foto 1
    // 60 x 45 -> t3 -> faz todas
    // 300 -> T5_codimovel_numerodafoto -> faz todas
    // md5_file da original
    $array_tamanho = array(
                            'TM' => array('width' => '240', 'height' => '180', 'crop' => FALSE),
                            'T3' => array('width' => '120', 'height' => '90', 'crop' => FALSE),
                            'destaque' => array('width' => '380', 'height' => '270', 'crop' => FALSE),
                            'destaque_home' => array('width' => '208', 'height' => '160', 'crop' => FALSE),
                            'T5' => array('width' => '650', 'height' => 'auto', 'crop' => FALSE),
                            '650F' => array('width' => '900', 'height' => 'auto', 'crop' => FALSE),
                            '1150F' => array('width' => '1150', 'height' => 'auto', 'crop' => FALSE),
                            );
    $nao_tem_sequencia = ( $tipo == 'TM' ) ? TRUE : FALSE;
    $pasta_local = str_replace(array('codEmpresa', 'admin2_0/'), array($id_empresa, ''), URL_INTEGRACAO_LOCAL);
    var_dump($pasta_local);
//    var_dump($pasta_local);
    $nome_arquivo = $tipo.'_'.$id.( $nao_tem_sequencia ? '' : '_'.$sequencia );
    $nome_arquivo .= '.'.str_replace('.','',substr($arquivo, -4)); 
    $nome_original = $id.( $nao_tem_sequencia ? '' : '_'.$sequencia );
    $existe = $pasta_local.$nome_arquivo;
    $existe_original = $pasta_local.$nome_original;
    $retorno = array('status' => TRUE, 'arquivo' => $arquivo, 'code' => 200);
    if ( ! is_dir($pasta_local) ){
        mkdir( $pasta_local, 0777, TRUE );
    }
    if ( file_exists($existe) && filesize($existe) > 2000 ){
        $retorno['arquivo'] = str_replace('codEmpresa', $id_empresa, URL_INTEGRACAO_BASE).$nome_arquivo;
    }
    else
    {
        if ( strstr( $arquivo, 'http' ) )
        {
            if ( strstr($tipo,'destaque') )
            {
                $curl = $gera ? curl_executavel($arquivo) : array('info' => array('http_code' => 200));
		if ( $curl['info']['http_code'] == 200 )
                {
                    $gerou = FALSE;
                    if ( $gera )
                    {
                        $tamanho = $array_tamanho[$tipo];
                        $gerou = gera_image_com_curl($curl, $pasta_local.$nome_arquivo, $pasta_local.$nome_original, $tamanho['width'], $tamanho['height'], $tamanho['crop']);
                    }
                    
                    if ( $gerou )
                    {
                        $a = str_replace('codEmpresa', $id_empresa, URL_INTEGRACAO_BASE).$nome_arquivo;
                        $retorno['arquivo'] = $a;
                    }
                    else
                    {
                        $a = $arquivo;
                        $retorno['arquivo'] = $a;
                    }
                }
                else
                {
                    $retorno['code'] = $curl['info']['http_code'];
                    if ( $curl['info']['http_code'] == 404 )
                    {
                        $retorno['status'] = FALSE;
                    }
                    $erro = 'Arquivo inacessivel: '.$arquivo.', id_empresa: '.$id_empresa.', id_imovel: '.$id.', em: '.date('Y-d-m').', status: '.$curl['info']['http_code'].', ip destino: '.$curl['info']['primary_ip'];
                    armazena_relatorio('images', $erro);
                }
            }
            else
            {
                $a = $arquivo;
                $retorno['arquivo'] = $a;
            }

        }
        else
        {
            if ( $tipo == '650F' && $mudou == 1 )
            {
                $a = ( ( $mudou == 1 ) ? str_replace('codEmpresa', $id_empresa, URL_IMAGE_MUDOU) : URL_IMAGE_NAO_MUDOU);
                $a .= $tipo.'_'.$id.( $nao_tem_sequencia ? '' : '_'.$sequencia ).'.';
                $a .= str_replace('.','',substr($arquivo, -4));
                $retorno['arquivo'] = $a;
            }
            elseif ( $tipo == 'destaque' )
            {
                $a = str_replace('codEmpresa', $id_empresa, URL_IMAGE_MUDOU);
                $a .= $arquivo;
                //$curl = curl_executavel($a);
                $curl = $gera ? curl_executavel($a) : array('info' => array('http_code' => 200));
                if ( $curl['info']['http_code'] == 200 )
                {
                    $gerou = FALSE;
                    if ( $gera )
                    {
                        $tamanho = $array_tamanho[$tipo];
                        
                        $gerou = gera_image_com_curl($curl, $pasta_local.$nome_arquivo, $pasta_local.$nome_original, $tamanho['width'], $tamanho['height'], $tamanho['crop']);
                    }
                    if ( $gerou )
                    {
//                        $a_ = str_replace('codEmpresa', $id_empresa, URL_INTEGRACAO_LOCAL).$nome_arquivo;
                        $retorno['arquivo'] = str_replace('codEmpresa', $id_empresa, URL_INTEGRACAO_BASE).$nome_arquivo;
                        var_dump($arquivo);

                    }
                    else
                    {
                       	$retorno['arquivo'] = $a;
                    }
                }
                else
                {
                    $retorno['code'] = $curl['info']['http_code'];
                    if ( $curl['info']['http_code'] == 404 )
                    {
                        $retorno['status'] = FALSE;
                    }
                    $erro = 'Arquivo inacessivel: '.$a.', id_empresa: '.$id_empresa.', id_imovel: '.$id.', em: '.date('Y-d-m').', status: '.$curl['info']['http_code'].', ip destino: '.$curl['info']['primary_ip'];
                    armazena_relatorio('images', $erro);
                    $retorno['arquivo'] = $a;
                }
            }
            else
            {
                $a = URL_IMAGE_MUDOU;
                $a .= $arquivo;
                $retorno['arquivo'] = $a;
            }
        }
    }
    if ( LOCALHOST )
    {
        $retorno['arquivo'] = str_replace(['localhost/portais_3','201.22.56.213/portais_3'], 'images.powempresas.com/', $retorno['arquivo']);
    }
    return $retorno;
}

function armazena_relatorio( $tipo, $erro )
{
    switch( $tipo )
    {
        case 'images':
            $arquivo = 'erro_images';
            break;
    }
    $arquivo_debug = URL_RELATORIOS.$arquivo;
    $arq = fopen($arquivo_debug,'a');
    fwrite($arq, PHP_EOL.$erro);
    fclose($arq);
}

function curl_executavel($endereco) 
{
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 100);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $endereco);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $retorno['item'] = curl_exec($ch);
    $retorno['erro'] = curl_errno( $ch );
    $retorno['erromsg']  = curl_error( $ch );
    $retorno['info']  = curl_getinfo( $ch );
    return $retorno;
}
function get_arquivos($pasta = NULL)
{
    $arquivos = array();
    if($pasta)
    {
//        $pasta = (strstr($pasta,'/') ? $pasta : $pasta.'/');
        $arquivos_pasta = scandir($pasta);
        $i = 0;
        if(count($arquivos_pasta) > 0)
        {
            foreach ($arquivos_pasta as $arquivo)
            {
                if(!strstr($arquivo,'.'))
                {
                    $tamanho = converte_tamanho(filesize($pasta.$arquivo));
                    $arquivos[] = array(
                                        'nome'    => $arquivo,
                                        'tamanho' => $tamanho
                                    );
                }
            }
        }
    }
    return $arquivos;
}
function converte_tamanho($bytes)
{
    $bytes = floatval($bytes);
    $tamanhos = array(
                    array(
                        "unidade" => "TB",
                        "valor" => pow(1024, 4)
                    ),
                    array(
                        "unidade" => "GB",
                        "valor" => pow(1024, 3)
                    ),
                    array(
                        "unidade" => "MB",
                        "valor" => pow(1024, 2)
                    ),
                    array(
                        "unidade" => "KB",
                        "valor" => 1024
                    ),
                    array(
                        "unidade" => "B",
                        "valor" => 1
                    ),
                );

    foreach($tamanhos as $tamanho)
    {
        if($bytes >= $tamanho["valor"])
        {
            $resultado = $bytes / $tamanho["valor"];
            $resultado = str_replace(".", "," , strval(round($resultado, 2)))." ".$tamanho["unidade"];
            break;
        }
    }
    return $resultado;
}

function set_imovel($item)
{
    $retorno = '';
    $retorno .= '<div class="thumbnail" style="width:220px;height:auto;">';
    $retorno .= str_replace('>',' style="width:200px;height:auto;">',set_foto_imovel($item->image,$item->id_empresa));
    $retorno .= '<div class="caption">';
    $retorno .= '<p>'.$item->id.', '.$item->referencia.', '.$item->titulo_tipo.', '.$item->cidade.', '.$item->bairro.'</p>';
    $retorno .= '</div>';
    $retorno .= '</div>';
    return $retorno;
}

/**
 * 
 * @param string $texto - Texto para procurar a mascara
 * @param array $procura - Mascara a ser alterada
 * @param array $troca - Valor para colocar na mascara
 */
function altera_mascara($troca,$texto,$procura = '[id]')
{
    if(is_array($troca) && $procura === '[id]')
    {
        $procura = array('[id]','[mes]','[ano]');
    }
    return str_replace($procura, $troca, $texto);
}

function get_thumbnail($video)
{
    $existe_watch = strstr($video, 'v=' );
    if ( $existe_watch )
    {
        $v = explode('v=', $video);
        $existe_feature = strstr($v[1], 'feature');
        if ( $existe_feature )
        {
            $vi = explode('&', $v[1]);
            $retorno = $vi[0];
        }
        else
        {
            $retorno = $v[1];
        }
    }
    else
    {
        if (strstr($item->video, '.be/') )
        {
            $v = explode('.be/', $video);
            $retorno = $v[1];
        }
        else
        {
            $retorno = '';
        }
    }
    
    return "https://i.ytimg.com/vi/".$retorno."/hqdefault.jpg";
}

function set_embed_video( $video )
{
    $existe_watch = strstr($video, 'v=' );
    if ( $existe_watch )
    {
        $v = explode('v=', $video);
        $existe_feature = strstr($v[1], 'feature');
        if ( $existe_feature )
        {
            $vi = explode('&', $v[1]);
            $retorno = $vi[0];
        }
        else
        {
            $retorno = $v[1];
        }
    }
    else
    {
        if (strstr($video, '.be/') )
        {
            $v = explode('.be/', $video);
            $retorno = $v[1];
        }
        else
        {
            $retorno = '';
        }
    }
    return '//www.youtube.com/embed/'.(strstr($retorno,'?') ? $retorno.'&rel=0' : $retorno.'?rel=0');
}
/**
 * 
 * @param date $data1 - data do evento
 * @param date $data2 - data inicial, default date('Y-m-d')
 * Função para mostrar qual a diferença entre duas datas, seja em [anos,meses,dias,horas,minutos,segundos]
 * Exemplo
 * $data1 = [data_de_um_evento]
 * $data2 = [data_de_hoje]
 * return [quanto falta para a $data1]
 */
function get_intervalo($data1,$data2 = NULL)
{
    $retorno = '<b>';
    if($data2 === NULL)
    {
        $data_atual = strtotime(date('Y-m-d H:i:s'));
    }
    else
    {
        $data_atual = strtotime($data2);
    }
    $data = strtotime($data1);
    
    $diferenca = abs($data_atual - $data );
    /**
     * Teste
     */
    $dias = 0;
    $horas = 0;
    $minutos = 0;
    $segundos = 0;
    
    $segundos_final = $segundos;
    $minutos_final = $minutos;
    $horas_final = $horas;
    if($diferenca > 60)
    {
        $j = 0;
        for($i = 0;$i != $diferenca;$i++)
        {
            if($j === 59)
            {
                $minutos++;
                $j=-1;
            }
            $j++;
            $segundos = $j;
        }
        $j = 0;
        $minutos_final = $minutos;
        for($i = 0;$i != $minutos;$i++)
        {
            if($j === 59)
            {
                $horas++;
                $j=-1;
            }
            $j++;
            $minutos_final = $j;
        }
        $j = 0;
        $horas_final = $horas;
        for($i = 0;$i != $horas;$i++)
        {
            if($j === 23)
            {
                $dias++;
                $j=-1;
                $horas_final = 0;
            }
            $j++;
            $horas_final = $j;
        }
    }
    else
    {
        $segundos = $diferenca;
    }
    $retorno .= ($horas_final > 0) ? $horas_final.'h ' : '';
    $retorno .= ($minutos_final > 0) ? $minutos_final.'m ' : '';
    $retorno .= ($segundos > 0) ? $segundos.'s ': '';
    if($dias > 0)
    {
        $retorno = reverte_data_mysql($data1);
    }
    else
    {
        $retorno .= '</b><small> atrás</small>';
    }
    return $retorno;
}

function get_select_qtde()
{
    $retorno = array(
                    (object)array('id' => 1, 'descricao' => '1'),
                    (object)array('id' => 2, 'descricao' => '2'),
                    (object)array('id' => 3, 'descricao' => '3'),
                    (object)array('id' => 4, 'descricao' => '4 ou mais'),
    );
    return $retorno;
}

function string_convert( $valor )
{
    $encoding = mb_detect_encoding($valor.'x','auto');
    $retorno = $valor;
    if($encoding != 'UTF-8')
    {
        //var_dump(utf8_encode((string)$valor));
        $retorno = iconv("UTF-8", $encoding."//TRANSLIT", (string)$valor);//mb_convert_encoding($valor, "UTF-8", "auto");
        //var_dump($valor, $retorno);
        //echo $retorno;
        //echo $valor;
//        var_dump($encoding.' - '.$valor.' - '.$retorno);    
    }
    return $retorno;
}

/**
 * Converte qualquer imagem em base64
 * @author Nicolas Woitchik
 */
function converte_imagem($imagem,$valida = FALSE)
{
    if($valida)
    {
        $tipo = pathinfo($imagem, PATHINFO_EXTENSION);
        $data = file_get_contents($imagem);
        $base64 = 'data:image/' . $tipo . ';base64,' . base64_encode($data);
        return $base64;
    }
    return $imagem;
}

function compara_data($a, $b)
    {
        $al = ($a['data']);
        $bl = ($b['data']);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }
function descricao_curta($texto)
{
    return substr($texto, 0,100);
}

/**
 * http://blog.clares.com.br/php-mascara-cnpj-cpf-data-e-qualquer-outra-coisa/
 * @param type $val -  valor em numeros
 * @param type $mask - marcara com ##.###.###./####-##
 * @return type string
 */
function mask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++)
    {
        if($mask[$i] == '#')
        {
            if(isset($val[$k]))
            {
                $maskared .= $val[$k++];
            }
        }
        else
        {
            if(isset($mask[$i]))
            {
                $maskared .= $mask[$i];
            }
        }
    }
    return $maskared;
}

function retorna_dia_util($data)
{
    $date = new DateTime($data);
    $verifica_fim_semana = $date->format('w');
    if ( $verifica_fim_semana == 0 )
    {
        $data_referencia = $date->add(new DateInterval("P1D"))->format('Y-m-d');
    }
    elseif( $verifica_fim_semana == 6 )
    {
        $data_referencia = $date->add(new DateInterval("P2D"))->format('Y-m-d');
    }
    else
    {
        $data_referencia = $date->format('Y-m-d');
    }
    return $data_referencia;
}
