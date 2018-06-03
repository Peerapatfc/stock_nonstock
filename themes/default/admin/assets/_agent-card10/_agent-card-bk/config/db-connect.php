<?php
 
 date_default_timezone_set("Asia/Bangkok");
 class connectMySqlDB {
     
    public $objConnect;
    public $result;
    public $recode = array();
    public $type      = 'mysqli';
    public $server    = 'localhost';
    public $username  = 'topagent_smith';
    public $password  = 'sKfUnbyD';
    public $dbname    = 'topagent_smith';
    public $port      = 3306;
    public $charset   = 'UTF8';
     
    public function __construct() {
        $this->objConnect = mysqli_connect($this->server , $this->username, $this->password, $this->dbname);            
        mysqli_query($this->objConnect, 'SET NAMES UTF8');
        mysqli_query($this->objConnect, 'SET CHARACTER SET UTF8');
        mysqli_query($this->objConnect, 'SET character_set_results = UTF8');
        
        if(mysqli_connect_errno()){
         printf("Connect failed: %s\n", mysqli_connect_error());
           exit();
        } 
        else{
          // print "database  connect ok";
        }
    }

    public function query($strSQL = ''){
        if(!empty($strSQL)){
            $this->result = mysqli_query($this->objConnect, $strSQL);
            return $this;
        }else{
            return false;
        }
    }


  /* ==========================================================
     * SELECT data reccord 
  ============================================================ */
  
    // one reccord
    public function findOne(){
        if(!empty($this->result)){
            $this->recode = mysqli_fetch_object($this->result);
            return $this->recode;
        }else{
            return false;
        }
    }
   
    // list reccord
    public function findAll(){
        if(!empty($this->result)){
            $record = array();
            while ($row = mysqli_fetch_array( $this->result , MYSQL_ASSOC)) {
                $record[] = (object) $row;
            }
            return $record;
        }else{
            return false;
        }
    }

   // ### Close connect to database 
    function closeDB(){
     return mysqli_close($this->objConnect);     
    }   

} // End connectMySqlDB 
