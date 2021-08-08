<?php
require __DIR__."/DBCreator.php";
require __DIR__."/PDOClass.php";
class authDetail
{
    private $db;
    private $dbMage;
    public function __construct(){
        $this->db = new PDOClass();
        $this->dbMage = new DBCreator($this->db);
    }

    public function CheckLogin($table,$username,$uid){
        $db = $this->db;
        $sql = "SELECT * FROM $table WHERE id = '$uid' AND username = '$username'";

        $db->Disconnect();
        $db->database = "ADMIN";
         if($db->Connect()){
            if($db->CheckRow($sql)>0){
                return true;
            }else{
                return false;
            }
         }else{
             return false;
         }
    }
    public function hasUsername($table,$username,$password){
        $db = $this->db;
        $sql = "SELECT * FROM $table WHERE username = '$username' AND password = '$password'";

        $db->Disconnect();
        $db->database = "ADMIN";
        if($db->Connect()){
            $res = $db->login($username,$password,$table);
            if($res != false){
                return true;
            }else{
                return false;
            }
        }
    }




}
