<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de anexos
 * @version 1.0
 * @access public
 * @package tarefas
 */
class Anexos extends MY_Controller 
{
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void 
         */
	public function __construct()
	{
		parent::__construct(FALSE);
	}
	
        /**
         * seta a classe images
         * @version 1.0
         * @access public
         */
	public function index()
	{
		$this->images();
	}
	
        /**
         * adiciona a classe images
         * @version 1.0
         * @access public
         */
	public function adicionar ()
	{
		$this->images();
	}
	
        /**
         * criar as images para anexo,
         * chama o imges_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $familia
         * @param string $id
         * @param string $id_pai
         * @param string $sub
         * @version 1.0
         * @access public
         */
	public function images( $familia = NULL, $id = NULL, $id_pai = NULL, $sub = NULL )
	{
                if ( isset($familia) && isset($id) )
                {
                    
                    $model = ( $familia == 'padrao' ) ? 'pagina_model' : $familia.'_model';
                    $data['id_image_tipo'] = $this->images_model->get_id_tipo_image('image_tipo.tipo = "'.$familia.'" ');
                    $data['imagens'] = $this->images_model->get_select_tipo_image($familia);
                    $data['link_voltar'] = base_url().$familia.'/editar/'.$id;
                    if(isset($id_pai) && $id_pai)
                    {
                        $data['link_voltar'] .= '/'.$id_pai;
                        if(isset($sub) && $sub)
                        {
                            $data['link_voltar']  = str_replace('editar', 'editar_nivel_2', $data['link_voltar']);
                            $data['link_voltar'] .= '/'.$sub;
                        }
                    }
                    $data['familia'] = $familia;
                    $data['id_pai'] = $id;
                    $this->load->model($model);
                    $data['itens'] = $this->$model->get_item($id);
                    
                }
                else
                {
                        echo '<script type="text/javascript">alert("setor inválido, tente novamente");</script>';
                        redirect($familia.'/listar/');
                }
                $class = strtolower(__CLASS__);
                $data['data_url'] = base_url().$class.'/upload_image';//$this->upload_image();
		$this->layout
                            ->set_function( __FUNCTION__ )
                            ->set_include('js/anexos.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)
                            ->set_include('css/jquery.fileupload.css', TRUE)
                            ->set_usuario($this->set_usuario())
                        ->set_menu($this->get_menu($class, __FUNCTION__))
                            ->view('add_anexos',$data);
	}
        
        /**
         * 
         * @version 1.0
         * @access public
         */
        public function get_images_por_tipo()
        {
            $data = $this->_post();
            $images = $this->images_model->get_arquivo_por_tipo($data['id_image_tipo'], $data['id_pai'] );
            $itens = $images['itens'];
            echo json_encode($itens);
        }
        
        public function upload_image()
        {
            $dados = $this->_post();
            $dir = $this->images_model->get_id_tipo_image('id = '.$dados['id_image_tipo'].' AND image_tipo.tipo = "'.$dados['familia'].'"');
            if($dados['familia'] == 'noticias')
            {
                $diretorio = str_replace('[ano]', date('Y'), $dir->pasta);
                $diretorio = str_replace('[mes]', date('m'), $diretorio);
            }
            else
            {
                $diretorio = $dir->pasta ;
            }
            $data['arquivo'] = $this->upload( $dados['files'] , $diretorio, $dados['id_pai'], TRUE); 
            if( isset ( $data['arquivo'] ) )
            {
                $img_dados = array(
                                    'arquivo' => $data['arquivo']['name'], 
                                    'data' => date('Y-m-d H:i:s')
                                    );
                $id = $this->images_model->adicionar_arquivo( $img_dados );
                $return['return'] = 'success-'.$id.'-'.$data['arquivo']['pasta'].'-'.$data['arquivo']['name'];
            }
            echo json_encode($return['return']);
        }
        
	public function adicionar_image ()
	{
            $data = $this->_post();
            unset($data['files']);
            $id = $this->images_model->adicionar_pai($data);
            echo json_encode($id);
	}
	
        public function deleta_image()
        {
            $quantidade = 0;
            $data = $this->_post();
            $image = $this->images_model->get_arquivo_por_id($data['id_image_arquivo']);
            $arquivo  = '/'.$image->arquivo;
            $arquivo  = str_replace('admin2_0/', 'images/'.$data['familia'].'/', $arquivo);
            $verifica = $this->ftp_verifica(array('ftp' => 'guiasjp', 'arquivo' => $arquivo));
            //echo $arquivo;
            if ( $verifica )
            {
                $this->ftp_delete(array('ftp' => 'guiasjp', 'arquivo' => $arquivo));
                $quantidade = $this->images_model->excluir_arquivo('image_arquivo.id = '.$data['id_image_arquivo'] );
            }
            if ($quantidade>0)
            {
                    print $quantidade;
            }
            else 
            {
                    print 0;
            }
        }
        
	public function remover_image($resposta = 'print')
	{
            $data = $this->_post(FALSE);
            if ( $data['id'] )
            {
                $image = $this->images_model->get_item_por_id_($data['id']);
                $filtro_deleta = 'id = '.$data['id'];
            }
            else
            {
                $image = $this->images_model->get_item_por_id($data['id_image_arquivo']);
                $filtro_deleta = 'id_image_arquivo = '.$data['id_image_arquivo'] ;
            }
            //$arquivo  = CWD_IMAGE.'/'.$image->pasta;
            $arquivo  = '/'.$image->pasta;
            if(stristr($image->pasta, '[id]'))
            {
                $arquivo  = str_replace('[id]', $data['id_pai'], $arquivo);
            }
            $arquivo .= $image->arquivo;
            $verifica = $this->ftp_verifica(array('ftp' => 'guiasjp', 'arquivo' => $arquivo));
            //echo $arquivo;
            if ( $verifica )
            {
                $this->ftp_delete(array('ftp' => 'guiasjp', 'arquivo' => $arquivo));
            }
            $quantidade = $this->images_model->excluir_pai( $filtro_deleta );
            $image_arquivo = $this->images_model->get_id_image_arquivo($image->id_arquivo);
            if($image_arquivo <= 1)
            {
                $quantidade_arquivo = $this->images_model->excluir_arquivo('id = '.$image->id_arquivo );
            }
            if ( $resposta == 'print' )
            {
                print ( ($quantidade > 0) ? $quantidade.' itens foram apagados.' : 'Nenhum item apagado.') ;
            }
            else
            {
                echo json_encode( ($quantidade > 0) ? array('status' => TRUE) : array('status' => FALSE) );
            }
	}
        
        /**
         * @todo Verificar uso, para imagens de noticias do guiasjp e migrar com função de deletar image_arquivo
         */
        public function deletar_arquivo()
        {
            $dados = $this->input->post(NULL,TRUE);
            $retorno['status'] = FALSE;
            $retorno['mensagem'] = 'Erro de dados.';
            if($dados)
            {
                $this->load->model('images_model');
                $retorno['algo'] = $this->images_model->excluir_arquivo('id = '.$dados['id'] );
                $retorno['algo'] = $this->images_model->excluir_pai( 'id = '.$dados['id_pai'] );
                $caracteristicas['ftp'] = $dados['ftp'];
                $caracteristicas['arquivo'] = $dados['pasta'].$dados['arquivo'];
                $ok = $this->ftp_delete($caracteristicas);
                if($ok)
                {
                    $retorno['status'] = TRUE;
                    $retorno['mensagem'] = 'Arquivo removido com sucesso.';
                }
                else
                {
                    $retorno['status'] = FALSE;
                    $retorno['mensagem'] = 'Ocorreu algum erro, tente novamente mais tarde.';
                }
            }
            echo json_encode($retorno);
        }
        /**
         * $dados:
         * [id] => image_pai.id_image_arquivo
         * [id_empresa] => image_arquivo.id_empresa
         */
        public function deletar_arquivo_id_pai()
        {
            $dados = $this->input->post(NULL,TRUE);
            $retorno['status'] = FALSE;
            $retorno['mensagem'] = 'Erro de dados.';
            if($dados)
            {
                $this->load->model('images_model');
                $retorno['pai'] = $this->images_model->excluir_pai( 'id_image_arquivo = '.$dados['id'] );
                $retorno['arquivo'] = $this->images_model->excluir_arquivo('id = '.$dados['id'] );
                $ok = FALSE;
                if( $retorno['arquivo'] && $retorno['pai'] )
                {
                    $caracteristicas['ftp'] = $dados['ftp'];
                    $caracteristicas['arquivo'] = $dados['pasta'].$dados['arquivo'];
                    $ok = $this->ftp_delete($caracteristicas);
                }
                if($ok)
                {
                    $retorno['status'] = TRUE;
                    $retorno['mensagem'] = 'Arquivo removido com sucesso.';
                }
                else
                {
                    $retorno['status'] = FALSE;
                    $retorno['mensagem'] = 'Ocorreu algum erro, tente novamente mais tarde.';
                }
            }
            echo json_encode($retorno);
        }
	
    public function upload_temporario( $tipo = 'receita' )
    {
        $files = $_FILES;
        $post = $this->_post();
        $retorno = ( isset($post['resposta_type']) && $post['resposta_type'] == 'html' ) ? '' : array();
        $retorno = $this->_processa_images( $files[ $post['input'] ], $post );
        if ( isset($post['resposta_type']) )
        {
            switch ( $post['resposta_type'] )
            {
                case 'json':
                    echo json_encode($retorno);
                    break;
                case 'html':
                    echo $retorno;
                    break;
            }
            
        }
        else
        {
            echo $retorno;
        }
    }
    
    public function deleta_temporario()
    {
        $post = $this->_post();
        $arquivo = CWD_IMAGE.( isset($post['pasta']) ? $post['pasta'] : '/images/upload/').$post['arquivo'];
        $retorno['arquivo'] = $arquivo;
        if ( file_exists($arquivo) )
        {
            unlink($arquivo);
            $retorno['erro'] = FALSE;
            $retorno['id'] = $post['sequencia'];
            $retorno['mensagem'] = 'A imagem foi removida com sucesso';
        }
        else
        {
            $retorno['erro'] = TRUE;
            $retorno['mensagem'] = 'Esta imagem já foi removida, restaure sua pagina.';
        }
        echo json_encode($retorno);
    }
    
    private function _processa_images ( $images, $post )
    {
        $retorno = NULL;
        if ( isset($images['name']) )
        {
            if (is_array($images['name']) )
            {
                for( $a = 0; count( $images['name'] ) > $a; $a++ )
                {
                    $image = array(
                                    'tmp_name' => $images['tmp_name'][$a],
                                    'name' => $images['name'][$a],
                                    'type' => $images['type'][$a],
                                    'size' => $images['size'][$a],
                                    );
                    $retorno[$a] = $this->_processa_image( $image, $post, $a );
                    unset($image);
                }
            }
            else
            {
                $retorno = $this->_processa_image( $images, $post, 0 );
            }
        }
        return $retorno;
        
    }
    
    private function _processa_image ( $images, $post, $chave )
    {
        $retorno = NULL;
        if ( isset($images['name']) )
        {
            $type = explode('|',$post['type']);
            if ( strstr( $images['type'], '/' ) )
            {
                $e_type = explode( '/', $images['type'] );
                $type_compare = $e_type[1];
            }
            else
            {
                if ( ! empty($images['type']) )
                {
                    $type_compare = $images['type'];
                }
                else
                {
                    $type_compare = substr($images['name'], -4);
                    $type_compare = str_replace('.', '', $type_compare);
                }
            }
            if ( in_array($type_compare,$type)  )
            {
                if ( $images['size'] <= $post['limite_kb'] )
                {
                    if ( ! isset($post['mantem_nome']) )
                    {
                        $arquivo_nome = md5( $images['name'].time() ).'.'.$type_compare;
                    }
                    else
                    {
                        $arquivo_nome = $images['name'];
                    }
                    $arquivo_dir = CWD_IMAGE.'/'.( isset($post['pasta']) ? $post['pasta'] : 'images/upload/' );
                    if(!is_dir($arquivo_dir))
                    {
                        mkdir($arquivo_dir, 0777,true);
                    }
                    $arquivo_dir = $arquivo_dir.$arquivo_nome;
                    if ( move_uploaded_file( $images["tmp_name"], $arquivo_dir ) )
                    {
                        $retorno = array('chave' => $chave, 'erro' => FALSE, 'arquivo' => $arquivo_nome, 'mensagem' => 'A imagem '.$images['name'].' foi enviada com sucesso' , 'pasta' => $arquivo_dir, 'caminho' => base_url().$post['pasta'].'/'.$arquivo_nome);
                    }
                    else
                    {
                        $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'Problemas no Upload do arquivo.' );
                    }
                }
                else
                {
                    $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'O arquivo deve ter no maximo: '.$post['limite_kb'].' kb' );
                }
            }
            else
            {
                $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'O arquivo '.$images['name'].' deve utilizar os seguintes formatos: '.implode(', ',$type) );
            }
        }
        return $retorno;
        
    }
    
    
    public function upload_image_direto()
    {
        $post = $this->_post();
        $retorno = $this->_processa_images($_FILES['upload'], $post);
        $nome_arquivo = (isset($_FILES['upload']['name']) ? $_FILES['upload']['name'] : NULL);
        echo json_encode($retorno);
    }
        
    public function upload_image_com_resposta()
    {
        $this->load->model('image_tipo_model');
        $post = $this->_post();
        $tipo = $this->image_tipo_model->get_item($post['tipo']);
        $retorno = $this->_processa_images($_FILES['upload'], $post);
//        $post['pasta'] = strstr($post['pasta'], '[id]') ? str_replace('[id]',$post['id'],$tipo->pasta) : $post['pasta'];
//        $post['pasta'] = isset($post['pasta']) ? $post['pasta'] : str_replace('[id]',$post['id'],$tipo->pasta);
        $nome_arquivo = (isset($_FILES['upload']['name']) ? $_FILES['upload']['name'] : NULL);
//        var_dump($post,$retorno,$nome_arquivo);die();
        if ( ! $retorno['erro'] )
        {
            if(isset($post['ftp']) && $post['ftp'])
            {
                $caracteristicas['ftp'] = $post['ftp'];
                $caracteristicas['pasta'] = $post['pasta'];
                $caracteristicas['destino'] = $post['pasta'].$retorno['arquivo'];
                $retorno['caracteristicas'] = $caracteristicas;
                $up = $this->ftp_upload($caracteristicas,$retorno['arquivo']);
                if($up)
                {
                    unlink(CWD_IMAGE.'/'.$caracteristicas['destino']);
                }
            }
            $img_dados = array(
                                'arquivo' => $retorno['arquivo'], 
                                'data' => date('Y-m-d H:i:s')
                                );
            if(isset($post['id']))
            {
                $img_dados['id_empresa'] = $post['id'];
            }
            $id_arquivo = $this->images_model->adicionar_arquivo( $img_dados );
            if ( isset($id_arquivo) )
            {
                if ( $post['substitui'] ){
                    $data_e_pai = ['id_pai' => $post['id_pai']];
                    $id_pai = $this->images_model->excluir_pai( $data_e_pai );
                }
                $data_pai = array(
                                'id_image_tipo' => $post['tipo'],
                                'id_image_arquivo' => $id_arquivo,
                                'id_pai' => $post['id_pai'],
                                'titulo' => $nome_arquivo
                            );
                $id_pai = $this->images_model->adicionar_pai( $data_pai );
                if ( isset($id_pai) )
                {
                    $retorno['id_pai'] = $id_pai;
                    $retorno['pasta'] = $tipo->pasta;
                    $retorno['arquivo'] = $img_dados['arquivo'];
                }
                else
                {
                    $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'Problema ao salvar a relação de arquivo' );
                }
            }
            else
            {
                $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'Problemas ao salvar o arquivo no banco de dados.' );
            }
            
        }
        echo json_encode($retorno);
    }
        
    
    
    public function upload_via_modal( $tabela = FALSE, $classe = FALSE, $id = FALSE, $tipo = FALSE )
    {
        $dados = $this->_post();
        if ( isset($dados) && $dados )
        {
            /*
             *  {   ["id"]=>   string(5) "81881"   
             * ["tabela"]=>   string(8) "empresas"   
             * ["classe"]=>   string(18) "pagina_logo_grande"   
             * ["tipo"]=>   string(5) "campo"   
             * ["limite_kb"]=>   string(7) "1886080"   
             * ["type"]=>   string(12) "jpeg|png|gif"   
             * ["resposta_type"]=>   string(4) "json"   
             * ["input"]=>   string(6) "upload" 
             * } 
             */
            $file = isset($_FILES[$dados['input']]) ? $_FILES[$dados['input']] : NULL;
            if ( isset($file) )
            {
                //$dados['pasta'] = '/paginas/';
                $retorno = $this->_processa_images($file, $dados);
                if ( ! $retorno['erro'] )
                {
                    /**
                     *  {   
                     *      ["chave"]=>   int(0)   
                     *      ["erro"]=>   bool(false)   
                     *      ["arquivo"]=>   string(36) "a30cd2dad4119f03c8a6c962fd36abc8.png" 
                     * } array(8) 
                     * {   
                     */            
                    $retorno['classe'] = $dados['classe'];
                    if ( $dados['tabela'] == 'publicidade_campanhas')
                    {
                        $pasta = 'publicidade';
                        
                    }
                    else
                    {
                        $pasta = 'paginas';
                    }
                    $destino = CWD_IMAGE.'/'.$pasta.'/'.$retorno['arquivo'];
                    $temporaria = CWD_IMAGE.'/images/upload/'.$retorno['arquivo'];
                    //$moveu = ;
                    if ( copy($temporaria, $destino) )
                    {
                        unlink($temporaria);
                        $model = $dados['tabela'].'_model';
                        $filtro = array('id' => $dados['id']);
                        $data = array($dados['classe'] => $retorno['arquivo']);
                        $this->load->model($model);
                        $update = $this->{$model}->editar($data, $filtro);
                        if ( ! $update )
                        {
                            $retorno = array('chave' => 0, 'erro' => TRUE, 'mensagem' => 'O arquivo não pode ser salvo no banco de dados, verifique as inclusoes junto ao administrador de banco de dados, tente novamente: ');
                        }
                    }
                    else
                    {
                        $retorno = array('chave' => 0, 'erro' => TRUE, 'mensagem' => 'O arquivo não foi copiado, verifique as permissoes de pasta junto ao administrador de rede, tente novamente: destino: '.$destino.' - temp '.$temporaria);
                    }
                }
            }
            else
            {
                $retorno = array('chave' => 0, 'erro' => TRUE, 'mensagem' => 'O arquivo não foi enviado, tente novamente: ');
            }
            echo json_encode($retorno);
        }
        else
        {
            $class = strtolower(__CLASS__);
            $data['tabela'] = $tabela;
            $data['classe'] = $classe;
            $data['id'] = $id;
            $data['tipo'] = $tipo;
            $data['formatos'] = 'jpeg|png|gif';
            $this->layout
                        ->set_function( __FUNCTION__ )
                        ->set_include('js/upload_via_modal.js', TRUE)
                        ->set_include('js/upload2/funcs.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_usuario($this->set_usuario())
                        ->view('upload_via_modal',$data, 'layout/sem_menu');
        }
    }   
    
    public function editar($id_image = NULL,$model = NULL)
    {
        $id_empresa = (($this->session->userdata('login')) && isset($this->session->all_userdata()['id_empresa']) ? $this->session->all_userdata()['id_empresa'] : 0);
        
        if( ! strstr($model,'_model'))
        {
            $model = $model.'_model';
        }
        
        $this->load->model($model);
        
        $arquivo = $this->{$model}->get_itens('imoveis_images.id = '.$id_image);
        
        if(isset($arquivo[0]->arquivo))
        {
            if(! strstr($arquivo[0]->arquivo,'http'))
            {
                $arquivo[0]->arquivo = base_url().'sites/'.$arquivo[0]->id_empresa.'/images/imoveis/'.$arquivo[0]->id_imovel.'/'.$arquivo[0]->arquivo;
            }
            $class = strtolower(__CLASS__);
            $data['arquivo'] = $arquivo[0]->arquivo;
            $data['id_empresa'] = $id_empresa;
            $data['action'] = base_url(). strtolower(__CLASS__).'/crop';
            $this->layout
                        ->set_function( __FUNCTION__ )
                        ->set_usuario($this->set_usuario())
                        ->set_include('metronic/global/plugins/jcrop/css/jquery.Jcrop.min.css')
                        ->set_include('metronic/global/plugins/jcrop/js/jquery.Jcrop.min.js')
                        ->set_include('metronic/global/plugins/jcrop/js/jquery.color.js')
                        ->set_include('js/image_crop.js')
                        ->view('image_crop',$data,'layout/sem_menu');
            
        }
        else 
        {
            
        }
    }
    
    public function crop()
    {
        $dados = $this->input->post(NULL,TRUE);
        
        $src = str_replace(base_url(), CWD_IMAGE.'/', $dados['src']);
        
        $config = array(
            'image_library' => 'gd2',
            'source_image'  => $src,
            'create_thumb'  => FALSE,
            'maintain_ratio'=> FALSE,
            'new_image'     => $src,
            'width'         => (float)$dados['w'],
            'height'        => (float)$dados['h'],
            'x_axis'        => (float)$dados['x'],
            'y_axis'        => (float)$dados['y'],
            'quality'       => '100%'
        );
        $this->load->library('image_lib', $config);
        $this->image_lib->crop();
        echo json_encode(array('src' => str_replace(CWD_IMAGE.'/', base_url(), $src).'?t='.time()));
    }
    
    public function adicionar_arquivo($tipo = NULL,$id_pai = 0,$id = NULL)
    {
        if(isset($tipo))
        {
            $this->load->model(array('image_tipo_model','images_model'));
            $estilo = $this->image_tipo_model->get_item($tipo);
            if($estilo)
            {
                if(isset($estilo->pasta))
                {
                    $ftp = ( strstr($estilo->pasta,'powsites') ? 'pow' : 'guiasjp');
                }
                else
                {
                    $ftp = FALSE;
                }
                $data = $this->input->get('data',TRUE);
                $troca = $id;
                if($data)
                {
                    $data = mes_ano($data);
                    $troca = array($id,$data['mes'],$data['ano']);
                }
                $data['arquivos'] = $this->images_model->get_arquivo_por_tipo($tipo,$id_pai);
                $estilo->pasta = altera_mascara($troca,$estilo->pasta);
                $class = strtolower(__CLASS__);
                $function = strtolower(__FUNCTION__);
                $data['estilo'] = $estilo;
                $data['ftp'] = $ftp;
                $data['id_pai'] = $id_pai;
                $data['id'] = $id;
                $data['tipo'] = $tipo;
                $data['action'] = base_url(). $class.'/'.$function;
                $this->layout
                            ->set_function( __FUNCTION__ )
                            ->set_usuario($this->set_usuario())
                            ->set_include('js/dropzone.js', TRUE)
                            ->set_include('metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css', TRUE)
                            ->set_include('metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js', TRUE)
                            ->set_include('metronic/global/plugins/dropzone/basic.min.css', TRUE)
                            ->set_include('metronic/global/plugins/dropzone/dropzone.min.css', TRUE)
                            ->set_include('metronic/global/plugins/select2/js/select2.full.js', TRUE)
                            ->set_include('metronic/global/plugins/select2/css/select2.css', TRUE)
                            ->set_include('metronic/global/plugins/select2/css/select2-bootstrap.min.css', TRUE)
                            ->set_include('metronic/pages/scripts/components-select2.js', TRUE)
                            ->view('add_arquivo',$data,'layout/sem_menu');
            }
        }
    }

    private function _post( $nimage = TRUE )
    {
            $data = $this->input->post(NULL, TRUE);
            if ( (isset($_FILES['files']) && $_FILES['files'] ) )
            {
                $data['files'] = $_FILES['files'];
            }
            if ( (isset($_FILES['file']) && $_FILES['file'] ) )
            {
                $data['file'] = $_FILES['file'];
            }
            return $data;
    }
}
