<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";



class select
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
            case 'select':
                return $this->get($json,$json['action']);
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
    public function get($json,$table=''){ //FOR SELECT
        $db = $this->db;
        $dbMage =$this->dbMage;
        $db->Disconnect();
        $memid = $json['registerId'];
        $shopId = $json['shopId'];
        if($json['shopId']== "" || !isset( $json['shopId'])){
            $db->database = $json['registerId']."_MEMBER";
        }else if($json['shopId']!="" && isset( $json['shopId'])){
            $db->database =$json['registerId']."_".$json['shopId']."_SHOP";
        }

         if($db->Connect()){
            $sql = "SELECT * FROM $table";
            if($db->CheckRow($sql)>0){
                return $db->Select($dbMage->strSelect($table,$json));
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
