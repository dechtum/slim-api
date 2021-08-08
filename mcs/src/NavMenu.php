<?php

// $_SESSION['user_id']
// $_SESSION['username']
// $_SESSION['id_application']
// $_SESSION['pormission']
// $_SESSION['id_position']
class NavMenu
{    
    private $db;
    private $dbMage;
    function __construct($db,$dbMage)
    {
        $this->db = $db; 
        $this->dbMage = $dbMage;
        
    }
    public function init($request,$json=''){
        switch ($request[3]) {      
            case 'profile':
                $sql = "SELECT * FROM login WHERE user_id = '".$_SESSION['user_id']."'";
                
                $res =  $this->get($sql); 
                $arr = [];
                foreach ($res as $key => $value) {
                    $obj = new stdClass();
                    $obj->img = "https://1.2.175.220/robust/html/ltr/profile/picUser/{$value['user_id']}/pic1.png";
                    $obj->name = $value['name'];
                    array_push($arr,$obj);
                }
                echo json_encode($arr);
                break;                          
            default:
                 
                break;
        }
     }

     //Function 

     public function get($sql=''){ //FOR SELECT
         $db = $this->db;
         $db->Disconnect();
         if($db->Connect()){            
            if($db->CheckRow($sql)>0){                
                return $db->Select($sql);               
            }else{
                return false;
            }
         }else{
             return false;
         }
     }
 
     public function set($json,$table=''){  // FOR  INSERT UPDATE
         $db = $this->db;
         $dbMage =$this->dbMage;
         $db->Disconnect();
         if($db->Connect()){
             $sql = "SELECT * FROM $table WHERE id = '".$json->id."'";
             if($db->CheckRow($sql)>0){
                 $sql = $dbMage->strUpdate("$table",$json->id,$json);
                 if($db->Iquery($sql)){
                     $sql = "SELECT id FROM $table ORDER BY id DESC LIMIT 0,1";
                     $res = $db->Select($sql);
                     foreach ($res as $key => $value){
                     echo $value['id'];
                     }                       
                 }else{
                     echo false;
                 }
             }else{
                 $sql = "SELECT * FROM $table";
                 if($db->CheckTable($sql)==false){
                     $sql = $dbMage->createTB($table,$json);
                     $db->Iquery($sql);
                     $sql = $dbMage->strInsert($table,$json);
                     if($db->Iquery($sql)){                        
                         $sql = "SELECT id FROM $table ORDER BY id DESC LIMIT 0,1";
                         $res = $db->Select($sql);
                         foreach ($res as $key => $value) {
                         echo $value['id'];
                         } 
                     }else{
                         echo false;
                     }
                 }else{
                     $sql = $dbMage->strInsert($table,$json);
                     
                     if($db->Iquery($sql)){
                         $sql = "SELECT id FROM $table ORDER BY id DESC LIMIT 0,1";
                         $res = $db->Select($sql);
                         foreach ($res as $key => $value) {
                             echo $value['id'];
                         }  
                     }else{
                         echo false;
                     }
                 }
             }
 
         }else{
             echo false;
         }
     }
}
