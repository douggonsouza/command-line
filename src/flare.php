<?php

/*

LINHA DE COMANDO

    ADICIONAR AO INI.INC.PHP

        // walking
        require(realpath(__DIR__ . '/flare.php'));
        $flare = new flare();

    EXEMPLO DE USO PARA SCRIPTS

        \cmdl::point(array('test'), 'AAA');


TESTES

    CRIAR PASTA DE TESTES

        src/_tst/

*/

final class flare
{
    protected static $protocol;
    protected static $index = 1;
    protected static $status = array();
    // private $callbacks = array();
        
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        class_alias(get_class($this), 'cmdl');
        self::$protocol = rand(100000, 100000000);
        // register_shutdown_function(array($this, 'callRegisteredShutdown'));
    }

    public static function point(array $output = array(), $label = '_', $condition = true, $propertys = false)
    {
        if (is_bool($condition) && $condition === true && is_array($output)) {
            $sd = array();
            if (!empty($output)) {
                $sd = $output;
            }

            echo("\n".date('Y-m-d H:i:s', strtotime('now')). " Protocol: " . self::$protocol . " label: " . $label . self::$index . " usage memory (Bytes): ". memory_get_peak_usage() ."\n");

            if (!empty($sd)) {
                print_r(self::backtrace());
                echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>>>> outputs:\n");
                print_r($sd);

                if ($propertys) {
                    echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>>>> propertys:\n");
                    print_r(array_merge(self::globals($propertys), self::envs($propertys)));
                }
            }

            self::$index++;
            self::follow();
        }
    }
        
    /**
     * Method envs
     *
     * @param $propertys $propertys [explicite description]
     *
     * @return void
     */
    private static function envs($propertys = false)
    {
        $env = array();
        
        if ($propertys === false) {
            return $env;
        }

        foreach($_ENV as $key => $value){
            if(is_object($value)){
                $env[$key] = get_class($value);
                continue;
            }
            if(is_array($value)){
                $env[$key] = 'array['.count($value).']';
                continue;
            }
            $env[$key] = $value;
        }
        
        return $env;
    }
        
    /**
     * Method globals
     *
     * @param $propertys $propertys [explicite description]
     *
     * @return void
     */
    private static function globals($propertys = false)
    {
        $global = array();
        
        if ($propertys === false) {
            return $global;
        }

        foreach($GLOBALS as $key => $value){
            if(is_object($value)){
                $env[$key] = get_class($value);
                continue;
            }
            if(is_array($value)){
                $env[$key] = 'array['.count($value).']';
                continue;
            }
            $global[$key] = $value;
        }
        
        return $global;
    }
    
    /**
     * Method follow
     *
     * @return void
     */
    protected static function follow()
    {
        $follow = readline(utf8_encode("\nTo continue, do you wish type y or yes: "));
        if (!in_array(strtolower($follow), array('Y','y','Yes','yes'))) {
            die("\n".date('Y-m-d H:i:s', strtotime('now'))." <<< END.\n");
        }
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> CONTINUE\n");
    }
    
    /**
     * Method backtrace
     *
     * @param $simple $simple [explicite description]
     *
     * @return void
     */
    protected static function backtrace($simple = true)
    {
        $trace = '';
        $debugBacktrace = debug_backtrace();

        if ($simple) {
            foreach ($debugBacktrace as $item) {
                $trace = ">>> "  . $debugBacktrace[2]['file'] . ', ' . $debugBacktrace[2]['line'] . ', ' . $debugBacktrace[2]['function'] . ";\n";
            }
            return $trace;
        }

        foreach ($debugBacktrace as $item) {
            $trace .= ">>> "  . $item['file'] . ', ' . $item['line'] . ', ' . $item['function'] . ";\n";
        }
        return $trace;
    }
    
    /**
     * Method assertParam
     *
     * @param $param $param [explicite description]
     * @param $type  $type  [explicite description]
     * @param $value $value [explicite description]
     *
     * @return void
     * 
     * $type:   string
     *          integer
     *          float
     *          boolean
     *          array
     *          object
     *          resource
     *          NULL
     *          unknown type
     *          resource (closed)
     *          exception
     * 
     */    
    public static function assertParam($param, $type = "NULL", $value = NULL)
    {
        $status = array();
        $getType = gettype($param);

        // tipo de retorno
        if(!((string) $type === $getType)){
            self::setStatus(sprintf(
                "ERROR. TIPO de retorno recebido DIFERENTE (%s) do esperado (%s).", 
                $getType, 
                $type
            ));
        }
        else{
            self::setStatus(sprintf(
                "OK. TIPO de retorno recebido IGUAL (%s) ao esperado (%s).", 
                $getType, 
                $type
            )); 
        }

        // tipo de retorno
        if(!($param === $value)){
            self::setStatus(sprintf(
                "ERROR. VALOR de retorno recebido (%s) DIFERENTE do esperado (%s).", 
                $param, 
                $value
            ));
        }
        else{
            self::setStatus(sprintf(
                "OK. VALOR de retorno recebido (%s) IGUAL ao esperado (%s).", 
                $param, 
                $value
            ));
        }

        // Status
        echo("\n".date('Y-m-d H:i:s', strtotime('now')). " >>> Protocol: " . self::$protocol . " usage memory (Bytes): ". memory_get_peak_usage());
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>>");
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> [ASSERT PARAM]");
        foreach(self::getStatus() as $item){
            echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> " . $item);
        }
        echo("\n");

        self::follow();

        self::$status = array();

        return $param;
    }
        
    /**
     * Method assertFlux
     *
     * @param array $flux [explicite description]
     *
     * @return void
     */
    public static function assertFlux(array $fluxs = array())
    {
        $flux = $existing = null;
        $arrayFlux = $arrayExisting = array_reverse(debug_backtrace());
        $fluxExisting = array();

        echo("\n".date('Y-m-d H:i:s', strtotime('now')). " >>> Protocol: " . self::$protocol . " usage memory (Bytes): ". memory_get_peak_usage());
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>>");
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> [FLUX]");
        foreach($arrayFlux as $key => $row){
            echo(
                "\n".date('Y-m-d H:i:s', strtotime('now')). " >>> " . $row['class']. $row['type'] . $row['function'] . '():' . $row['line']
            );            
        }

        foreach($fluxs as $row){
            foreach($arrayExisting as $index => $trace){
                $function = $trace['class']. $trace['type'] . $trace['function'];
                if($function === $row){
                    $fluxExisting[] = array($row, $index);
                    $existing = $row;
                    break;
                }
            }

            if(!is_null($existing)){
                self::setStatus(sprintf(
                    "OK. A chamada %s EXISTE no fluxo.", 
                    $row
                ));
            }
            else{
                self::setStatus(sprintf(
                    "ERROR. A chamada %s NÃO existe no fluxo.", 
                    $row
                ));
            }           
        }

        if(count($fluxExisting) == count($fluxs)){
            $indexAnterior = 0;
            $success = true;
            foreach($fluxExisting as $row){
                if((int) $indexAnterior >= (int) $row[1]){
                    self::setStatus("ERROR. O FLUXO NÃO está conforme a ordem sequêncial.");
                    $success = false;
                    break; 
                }
                $indexAnterior = $row[1];
            }

            if($success){
                self::setStatus("OK. O FLUXO está conforme a ordem sequêncial.");
            }
        }
        else{
            if(!is_null($existing)){
                self::setStatus("ERROR. O FLUXO NÃO está completo.");
            }
        }
        
        // Status
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>>");
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> [ASSERT FLUX]");
        foreach(self::getStatus() as $item){
            echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> " . $item);
        }
        echo("\n");

        self::follow();

        self::$status = array();

        return;
    }

    /**
     * Method assert_env
     *
     * @param $condition $condition [explicite description]
     *
     * @return void
     */
    public static function assert_env($condition = array())
    {
        $success = true;

        if(!isset($condition) || empty($condition)){
            self::setStatus("NÃ£o encontrada as CONDIÃ‡Ã•ES de avaliaÃ§Ã£o.");
            $success = false;
        }

        if($success){
            foreach($condition as $key => $value){
                if(isset($value['type'])){
                    $sd = sprintf("O tipo da variÃ¡vel %s NÃƒO Ã‰ IGUAL (%s) a condiÃ§Ã£o passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
                    if($value['type'] === gettype($_ENV[$key])){
                        $sd = sprintf("O tipo da variÃ¡vel %s Ã© IGUAL (%s) a condiÃ§Ã£o passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
                    }
                    self::setStatus($sd);
                }

                if(isset($value['value'])){
                    $sd = sprintf("O valor da variÃ¡vel %s NÃƒO Ã‰ IGUAL (%s) a condiÃ§Ã£o passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
                    if($value['value'] === $_ENV[$key]){
                        $sd = sprintf("O valor da variÃ¡vel %s Ã© IGUAL (%s) a condiÃ§Ã£o passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
                    }
                    self::setStatus($sd);
                }
            }
        }

        // Status
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> STATUS");
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> " . implode("; \n", self::getStatus())."\n");

        // Continue
        self::follow();

        return self;
    }

    /**
     * Get the value of status
     */ 
    public static function getStatus()
    {
        return self::$status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public static function setStatus($status)
    {
        if(isset($status) && !empty($status)){
            self::$status[] = $status;
        }
    }
}
