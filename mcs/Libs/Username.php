<?php
class Username
{
    private $db='';
    function __construct($db){
        $this->db = $db;
    }
    public function update($idapplication,$username,$password){
        $db = $this->db;
        $db->Disconnect();
        if($db->Connect()){            
            if($password!=''){                
                $sql = "DELETE FROM login WHERE login.id_application = '0'";
                if($db->CheckRow($sql)>1){                     
                    $sql = "DELETE FROM login WHERE login.id_application = '".$idapplication."'";
                    $db->Iquery($sql);                       
                }             
                $sql = "SELECT * FROM login WHERE login.id_application = '".$idapplication."'";
                if($db->CheckRow($sql)>0){
                    $res = json_decode($db->SelectData($sql));                       
                    foreach ($res as $key => $value) {  
                        $password = password_hash(trim($password), PASSWORD_DEFAULT); 
                        $sql = "UPDATE login SET username = '$username', password='$password' WHERE login.id_application = '".$idapplication."'";
                        if($db->Iquery($sql)){
                            echo true;                               
                        }else{
                            echo false;                            
                        }
                    }
                }else{
                    if(strlen($password)<10){
                        $password = password_hash(trim($password), PASSWORD_DEFAULT);    
                        $sql = "INSERT INTO login (username,password,name,id_application) VALUES ('$username','$password','$name','$idapplication')";
                        if($db->Iquery($sql)){
                            echo true;
                        }else{
                            echo false;
                        }  
                    }                        
                }                
            } else{
                echo false;
            } 
        }else{
            echo false;
        }
    }
}
