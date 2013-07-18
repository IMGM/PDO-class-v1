<?php
require("shield.php");
Interface Browse{
    public function __construct();
    public function __destruct();
    public function trace();
    
    public function fetchWithoutIndex($column, $table, $limit);
    public function fetchWhereIndex($column, $table, $indexColumn, $indexValue = "" );
    
    public function insert($table, $pair = array());
    public function insertAllAtParentTable($email, $username, $password);
    public function insertWithoutIndex($valueColumn, $value, $table);
    public function insertWhereIndex($valueColumn, $value, $table, $indexColumn, $indexValue = "" );
    

    

}
class Call implements Browse{
    // Database info
    const community_host = "localhost";
    const community_db = "DB";
    const db_username = "root";
    const db_password = "";
    private $conn = null;
    private $trace = "";
    private $message = "";
    
    
    // Account info
    private $username = "";
    private $email = "";
    private $uniqueId = "";
    private $LoginTime = "";
    
    
    // Parent table info
    private $parentTable = "profile";
    private $uniqueIdColumn = "UniqueId";
    private $emailColumn = "Email";
    private $userNameColumn = "UserName";
    private $userPasswordColumn = "Password";
    private $DateOfCreationUtcColumn = "DateUTC";
    
    

     
    public function insert($table, $pair = array()){
            try{      
                $Sql = "INSERT INTO $table ";
                if(array_key_exists(0, $pair) ? 1 :  0): goto SkipColumn;
                endif;  
                $Sql .= "( ";
                $Sql .= implode(", ", array_keys($pair));
                $Sql .= " )";

            SkipColumn:
            $Sql .= " VALUES (";
            $Sql .= implode(", ", array_fill("0", count($pair), " ?"));
            $Sql .= " )";
            $array = array_combine(array_keys(array_fill("1", count($pair), ":")), $pair);
            $ready = $this->conn->prepare($Sql);
            foreach($array as $key => $value)
            {
                $ready->bindValue($key, $value, PDO::PARAM_STR);
            }
            $ready->execute();
        }
        catch(Exception $e){
            $this->trace .= " • ". $e->getMessage();  
        }
    }
    

    
    public function fetchWithoutIndex($column, $table, $limit){
    
        $sql = sprintf("SELECT %s FROM %s LIMIT %s",
                    $column, $table, $limit);
        try{
            $ready = $this->conn->prepare($sql);
            $ready->execute(); 
            $row = $ready->fetchall();
            return $row;
        }
        catch(Exception $e){
            $this->trace .= " • ". $e->getMessage();  
        }
    }//end of method
    
    public function fetchWhereIndex($column, $table, $indexColumn, $indexValue = "" ){
        if($indexValue == "" ): $indexValue = $this->uniqueId;
        endif;
        $sql = sprintf("SELECT %s FROM %s WHERE %s = :indexValue",
                    $column, $table, $indexColumn);
        try{
            $ready = $this->conn->prepare($sql);
            $ready->bindParam(':indexValue', $indexValue);
            $ready->execute(); 
            $row = $ready->fetchall();
            return $row;
        }
        catch(Exception $e){
            $this->trace .= " • ". $e->getMessage();  
        }
    }//end of method
    

    
    public function insertWithoutIndex($valueColumn, $value, $table){
        $sql = sprintf("INSERT INTO %s ( %s) VALUES ( :value)", 
                        $table, $valueColumn);
        try{
            $ready = $this->conn->prepare($sql);
            $ready->bindParam(':value', $value, PDO::PARAM_STR);
            $ready->execute();
        }
        catch(Exception $e){
            $this->trace .= " • ". $e->getMessage();  
        }
    }//end of method

    
    public function insertWhereIndex($valueColumn, $value, $table, $indexColumn, $indexValue = "" ){
        if($indexValue == ""): $indexValue = $this->uniqueId;
        endif;
        $sql = sprintf("INSERT INTO %s ( %s , %s ,) VALUES ( :indexvalue , :value , )", 
                        $table, $indexColumn, $valueColumn);
        try{
            $ready = $this->conn->prepare($sql);
            $ready->bindParam(':indexvalue', $indexValue, PDO::PARAM_STR);
            $ready->bindParam(':value', $value, PDO::PARAM_STR);
            $ready->execute();
        }
        catch(Exception $e){
            $this->trace .= " • ". $e->getMessage();  
        }
    }//end of method
    
    
    
    
    public function __construct(){
        $connectionString = sprintf("mysql:host=%s; dbname=%s; charset=utf8", 
                                CommunItY::community_host, CommunItY::community_db);
        try {
            $this->conn = new PDO($connectionString, CommunItY::db_username, CommunItY::db_password);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);    
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDO::ERRMODE_SILENT (doesn't return any errors)
            
            $this->trace .= "¶ ";
            
        } //end of connection by PDO
        catch(PDOException $e){
            //die($e->getMessage()); //don't use $message outside of die() or don't use die() after or before $message; caution is only for die() because die() *ends the script *
            $this->trace .= " • ". $e->getMessage();
        }
    }//end of construct

   public function __destruct(){
 
        $this->conn = null; //close connection
        
   } //end of destruct
   
   public function trace(){
   //trace = error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
        return $this->trace;
        // "¶ † « › ¡ ¦ •
   }//end of method
  
}//end of class



class Pure{    
    public function email($email){
        if($filtered = filter_var( $email, FILTER_VALIDATE_EMAIL)):
        return 1;
        endif;
    }//end of method
    
    public function ip($ip){
        if($filtered = filter_var( $ip, FILTER_VALIDATE_IP)):
        return 1;
        endif;
    }//end of method
    
    public function int($int){
        if($filtered = filter_var( $int, FILTER_VALIDATE_INT)):
        return 1;
        endif;
    }//end of method
}//end of class

$connect = new Call();

//public function insert($table, $pair = array());
//public function insertAllAtParentTable($email, $username, $password);
//$connect->insertWhereIndex( "Question", "hg", "questions", "UserId", "1435647" );
//$connect->insertWithoutIndex("Email", "iamMM@Hrt", "profile");


//$connect->fetchWhereIndex("Password", "profile", "UniqueId", "");
//$connect->fetchWithoutIndex("Password", "profile");

//$connect->email("dff@sdff");
//$connect->ip("234.23.23.56");
//$connect->int("34");

//echo $connect->trace();


?>





















