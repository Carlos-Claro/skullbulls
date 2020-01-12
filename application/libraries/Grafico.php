<?php
class Grafico
{
    private $itens = array();
    
    private $config = array();
    
    private $CI = array();
    
    private $includes = array();
    
    public function __construct()
    {
        $this->CI =& get_instance();			
    }
    
    public function inicia($data)
    {
        if(isset($data['itens']))
        {
            $this->itens = $data['itens'];
        }
        if(isset($data['config']))
        {
            $this->config = $data['config'];
        }
        return $this;
    }
    
    public function get_includes(Layout $layout,$base_url)
    {
        foreach ($this->includes as $include)
        {
            $layout->set_include($include,$base_url);
        }
        return $layout;
    }
    
    private function _set_include($include)
    {
        $this->includes[] = $include;
        return $this;
    }
}
