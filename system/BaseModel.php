<?php
namespace system;
use system\Tool;
use system\Medoo;

class BaseModel{
    public static $redis = '';
    public static $db = '';
    public static $models = [];
    private function __construct(){
        if(empty(self::$redis)){
            self::$redis = new \Redis();
            self::$redis->connect(REDIS_HOST,REDIS_PORT);
            self::$redis->auth(REDIS_PASSWORD);
            self::$redis->select(REDIS_DB);
        }
        if(empty(self::$db)){
            $database = array( 
                'database_type' =>DATABASETYPE, 
                'server' => SERVER, 
                'database_name' => DATABASENAME, 
                'username' => USERNAME, 
                'password' => PASSWORD, 
                'port' => PORT,
                'charset' => CHARSET, 
                'collation' => COLLATION, 
            ); 
            self::$db = new Medoo($database);
        }
        if(method_exists($this,'init')){
            $this->init();
        }
    }
    public function use($key){
        switch ($key){
            case 'db':
                return self::$db;
            case 'redis':
                return self::$redis;
        }
    }
    static public function get(){
        $name = get_called_class();
        if( !isset( self::$models[$name] ) ){
            self::$models[$name] = new $name();
        }
        return self::$models[$name];
    }
    
}