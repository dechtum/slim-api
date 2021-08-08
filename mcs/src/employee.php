<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";




class employee
{
    private $db;
    private $dbMage;
    private $detect;
    private $auth;

    function __construct()
    {
        $this->db = new PDOClass();
        $this->dbMage = new NewDBCreator($this->db);
        $this->detect = new Mobile_Detect;
        $this->auth = new authDetail();
    }
    public function init($request,$json=''){
        switch ($request) {
            case 'update':
                $newIdRow = $this->set($json,$json['action']);
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
     public function getAll($sql=''){ //FOR SELECT

        $db = $this->db;
        $db->Disconnect();
        if($db->Connect()){
           if($db->CheckRow($sql)>0){
               $out =  $db->Select($sql);
               $arr = [];
               $tmpDate = "";
               $counter = 1;
               foreach ($out as $key => $value) {
                    $d = explode(",",$value['dt']);
                    $s = explode(",",$value['st']);
                    $imgs = explode(",",$value['img']);
                    $idarr = [];
                    $conIN = "";
                    $conOUT = "";
                    $imgsIn = "";
                    $imgsOut = "";
                    foreach ($d as $k => $v) {
                        $obj = new stdClass();
                        $obj->id = ($counter++);
                        $obj->idpk = $value['id'];
                        $obj->id_application = $value['id_application'];
                        $obj->cmp_id = $value['cmp_id'];
                        $obj->cmp_name = $value['cmp_name'];
                        $obj->img_chkIn = $value['img'];
                        $obj->date_times = $value['date_time'];
                        $da = strtotime($v);
                        $ubtime = explode("T",$v);
                        $btime = explode(".",$ubtime[1]);
                        $obj->st = $value['st'];
                        $obj->latitude = $value['latitude'];
                        $obj->longitude = $value['longitude'];
                        $obj->active = $value['active'];
                       // $obj->status = $value['status'];
                        $obj->create_date = $value['create_date'];
                        $date_input = date('H:i:s',$da);


                        if($s[$k]=="0"){
                            $imgsIn = $imgs[$k];
                            $obj->pk = $idarr;
                            $conIN=$date_input;
                            $obj->status ='0';
                        }else if($s[$k]=="1"){
                            $imgsOut = $imgs[$k];
                            $obj->img_in = $imgsIn;
                            $obj->img_out = $imgsOut;
                            $obj->pk = $idarr;
                            $conOUT=$date_input;
                            $obj->status = '1';
                            $obj->date_time = $conIN;
                            $obj->date_time2 = $conOUT;
                            array_push($arr,$obj);
                        }

                        if($k == count($d)-1){
                            if($s[$k]=="0" && $s[$k]!="1"){
                                $imgsOut = "";
                                $obj->img_in = $imgsIn;
                                $obj->img_out = $imgsOut;
                                $conIN=$date_input;
                                $obj->pk = $idarr;
                                $obj->date_time = $conIN;
                                $obj->status = '0';
                                array_push($arr,$obj);
                            }
                        }
                    }
               }
               return json_encode($arr);
           }else{
               return false;
           }
        }else{
            return false;
        }
    }
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
        
        if(!isset( $json['shopId'])){
            $db->database ="{$json['registerId']}_MEMBER";
        }else if(isset( $json['shopId'])){
            $db->database ="{$json['registerId']}_{$json['shopId']}_SHOP";
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
                   
                    if($this->hasNameSurname($table,$json)==false){
                        $res = $db->registor($json['sql'],$table);
                        if(is_int($res)){
                            return $res;
                        }else{
                            return -2;
                        }                        
                    }else{
                        return -1;
                    }                   
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    private function hasNameSurname($table,$json){
        $db = $this->db;
        $dbMage =$this->dbMage;
        $sql = "SELECT * FROM $table WHERE name = '".$json['sql']['name']."' AND surname = '".$json['sql']['surname']."'";
       
        if($db->CheckRow($sql)>0){
            return true;
        }else{
            return false;
        }
        
    }
    
}
