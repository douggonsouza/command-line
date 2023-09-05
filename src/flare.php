<?php

namespace douggonsouza\command_line;

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
     * Method assert_type
     *
     * @param $param $param [explicite description]
     * @param $type $type [explicite description]
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
    public static function assert_type($param, $type = "NULL")
    {
        $status = array();
        $return = gettype($param);

        // tipo de retorno
        if(!((string) $type === $return)){
            $status[] = sprintf("O tipo de retorno recebido é DIFERENTE (%s) do esperado (%s).", $return, $type);
        }
        $status[] = sprintf("O tipo de retorno recebido é IGUAL (%s) ao esperado (%s).", $return, $type);

        self::setStatus($status);

        // Status
        echo("\n".date('Y-m-d H:i:s', strtotime('now')). " Protocol: " . self::$protocol . " usage memory (Bytes): ". memory_get_peak_usage());
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> STATUS");
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> " . implode("; \n", $status)."\n");

        // Continue
        self::follow();

        return $param;
    }

    /**
     * Method assert_value
     *
     * @param $param $param [explicite description]
     * @param $condition $condition [explicite description]
     *
     * @return void
     */
    public static function assert_value($param, $value = NULL)
    {
        $status = array();

        // tipo de retorno
        if(!$param === $value){
            $status[] = "O valor de retorno recebido é DIFERENTE do esperado.";
        }
        $status[] = "O valor de retorno recebido é IGUAL ao esperado.";

        self::setStatus($status);

        // Status
        echo("\n".date('Y-m-d H:i:s', strtotime('now')). " Protocol: " . self::$protocol . " usage memory (Bytes): ". memory_get_peak_usage());
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> STATUS");
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> " . implode("; \n", $status)."\n");

        self::follow();

        return $param;
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
            self::setStatus("Não encontrada as CONDIÇÕES de avaliação.");
            $success = false;
        }

        if($success){
            foreach($condition as $key => $value){
                if(isset($value['type'])){
                    $sd = sprintf("O tipo da variável %s NÃO É IGUAL (%s) a condição passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
                    if($value['type'] === gettype($_ENV[$key])){
                        $sd = sprintf("O tipo da variável %s é IGUAL (%s) a condição passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
                    }
                    self::setStatus($sd);
                }

                if(isset($value['value'])){
                    $sd = sprintf("O valor da variável %s NÃO É IGUAL (%s) a condição passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
                    if($value['value'] === $_ENV[$key]){
                        $sd = sprintf("O valor da variável %s é IGUAL (%s) a condição passada (%s).", $key, $value['type'], gettype($_ENV[$key]));
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
