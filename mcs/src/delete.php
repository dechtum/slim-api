<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";



class delete
{
    private $db;
    private $dbMage;
    private $detect;
    function __construct()
    {
        $this->db = new PDOClass();
        $this->dbMage = new NewDBCreator($this->db);
        $this->detect = new Mobile_Detect;
    }
    public function init($request,$json=''){
        switch ($request) {
            case 'delete':
                return $this->del($json,$json['action']);
                break;
            default:
                break;
        }
    }
    ///////////// Function Main

    public function del($json,$table=''){ //FOR DELETE
         $db = $this->db;
         $dbMage =$this->dbMage;
         $db->Disconnect();
         if($json['shopId']== "" || !isset( $json['shopId'])){
             $db->database = $json['registerId']."_MEMBER";
         }else if($json['shopId']!="" && isset( $json['shopId'])){
             $db->database =$json['registerId']."_".$json['shopId']."_SHOP";
         }
         if($db->Connect()){
            $sql=$dbMage->strDelete($table,$json);
            if($db->Iquery($sql)){
                return true;
            }else{
                return false;
            }
         }else{
             return false;
         }
     }

}
