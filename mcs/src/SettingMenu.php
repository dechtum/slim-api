<?php

// $_SESSION['user_id']
// $_SESSION['username']
// $_SESSION['id_application']
// $_SESSION['pormission']
// $_SESSION['id_position']
class SettingMenu
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
            case 'all':
                $sql = "SELECT * FROM tb_link_menu ORDER BY main_menu_index ASC";
                echo $this->get($sql);          
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
                return $db->SelectData($sql);               
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
