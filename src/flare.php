<?php

/*

// ADICIONAR AO INI.INC.PHP

// walking
require(__DIR__ . '/flare.inc.php');
$flare = new flare();

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
        class_alias(get_class($this), 'testing');
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
            $env = array();
            if (!empty($_ENV) && $propertys === true) {
                $env = $_ENV;
            }
            $global = array();
            if (!empty($GLOBALS) && $propertys === true) {
                $global = $GLOBALS;
            }
            echo("\n".date('Y-m-d H:i:s', strtotime('now')). " Protocol: " . self::$protocol . " label: " . $label . self::$index . " usage memory (Bytes): ". memory_get_peak_usage() ."\n");
            if (!empty($sd)) {
                print_r(self::backtrace());
                        
                if ($propertys) {
                    echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>>>> propertys:\n");
                    var_dump(array_merge($global, $env));
                }
    
                self::$index++;
                print_r($sd);
            }
            self::follow();
        }
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
     * Method unitTest
     *
     * @param $class $class [explicite description]
     * @param $function $function [explicite description]
     * @param array $cenario [explicite description]
     * @param $typeReturn $typeReturn [explicite description]
     *
     * @return void
     * 
     * 
     * TYPES:
     * 
     * string
     * inteiro
     * float
     * booleano
     * array
     * objeto
     * resource
     * NULL
     * unknown type
     * resource (closed)
     * exception
     * 
     */
    
    /**
     * Method pod
     *
     * @param $closure $closure [explicite description]
     * @param $typeReturn $typeReturn [explicite description]
     *
     * @return void
     * 
     * $typeReturn:
     * 
     * string
     * inteiro
     * float
     * booleano
     * array
     * objeto
     * resource
     * NULL
     * unknown type
     * resource (closed)
     * exception
     * 
     */
    public static function pod($closure, $typeReturn = "NULL")
    {
        $return = gettype($closure);
        // tipo de retorno
        if(!((string) $typeReturn === $return)){
            self::setStatus(sprintf("O tipo de retorno recebido é DIFERENTE (%s) do esperado (%s).", $return, $typeReturn));
        }
        self::setStatus(sprintf("O tipo de retorno recebido é IGUAL (%s) ao esperado (%s).", $return, $typeReturn));
        echo("\n".date('Y-m-d H:i:s', strtotime('now'))." >>> " . implode("; \n", self::getStatus())."\n");
        self::follow();
        return $closure;
    }

        // public function callRegisteredShutdown()
        // {
        //     foreach ($this->callbacks as $arguments) {
        //         $callback = array_shift($arguments);
        //         call_user_func_array($callback, $arguments);
        //     }
        // }

        // public function registerShutdownEvent()
        // {
        //     $callback = func_get_args();

        //     if (empty($callback)) {
        //         trigger_error('No callback passed to '.__FUNCTION__.' method', E_USER_ERROR);
        //         return false;
        //     }
        //     if (!is_callable($callback[0])) {
        //         trigger_error('Invalid callback passed to the '.__FUNCTION__.' method', E_USER_ERROR);
        //         return false;
        //     }
        //     $this->callbacks[] = $callback;
        //     return true;
        // }

        // test methods:
        // public function dynamicTest()
        // {
        //     echo '_REQUEST array is '.count($_REQUEST).' elements long.<br />';
        // }

        // public static function staticTest()
        // {
        //     echo '_SERVER array is '.count($_SERVER).' elements long.<br />';
        // }

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
