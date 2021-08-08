<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";



class queue
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
            case 'get':
                $newIdRow = $this->get($json,"product_sales");
                return $newIdRow;
                break;
            default:
                break;
        }
    }
    ///////////// Function Main

    public function del($sql){ //FOR DELETE
         $db = $this->db;
         $db->Disconnect();
         if($db->Connect()){
            if($db->Iquery($sql)){
                return true;
            }else{
                return false;
            }
         }else{
             return false;
         }
     }
   
    public function get($json,$table=''){ //
        $db = $this->db;
        $dbMage =$this->dbMage;
        $db->Disconnect();
        $memid = $json['registerId'];
        $shopId = $json['shopId'];
        if($json['shopId']=="" || !isset( $json['shopId'])){
            $db->database ="{$memid}_MEMBER";
        }else if($json['shopId']!="" && isset( $json['shopId'])){
            $db->database ="{$memid}_{$shopId}_SHOP";
        }
         if($db->Connect()){
            $sql = "SELECT * FROM $table WHERE DATE(reg_date) ";
            print_r( $sql );
            if($db->CheckRow($sql)>0){
                $db->Iquery($sql);
                return $db->GetlastID();
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
        $memid = $json['registerId'];
        $shopId = $json['shopId'];
        if($json['shopId']=="" || !isset( $json['shopId'])){
            $db->database ="{$memid}_MEMBER";
        }else if($json['shopId']!="" && isset( $json['shopId'])){
            $db->database ="{$memid}_{$shopId}_SHOP";
        }

        if($db->Connect()){
            $sql = "SELECT * FROM $table WHERE id = '".$json['id']."'";
            if($db->CheckRow($sql)>0){
                $sql = $dbMage->strUpdate($table,$json['id'],$json);
                if($db->Iquery($sql)){
                    return true;
                }else{
                    return false;
                }
            }else{
                $sql = "SELECT * FROM $table";
                if($db->CheckTable($sql)){
                    $sql = $dbMage->strInsert($table,$json);
                    if($db->Iquery($sql)){
                        return $db->GetlastID();
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }
}
