<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require __DIR__."/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";



class material
{
    private $db;
    private $dbMage;
    private $detect;
    private $kcode='';
    private $stock='';

    function __construct()
    {
        $this->db = new PDOClass();
        $this->dbMage = new NewDBCreator($this->db);
        $this->stock = new stock($this->db,$this->dbMage);
        $this->detect = new Mobile_Detect;
    }
    public function init($request,$json=''){
        switch ($request) {
            case 'update':
               
                return $this->set($json,$json['action']);
                
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
        $memid = $json['registerId'];
        $shopId = $json['shopId'];
        
        if($json['shopId']=="" || !isset( $json['shopId'])){
            $db->database ="{$memid}_MEMBER";
        }else if($json['shopId']!="" && isset( $json['shopId'])){
            $db->database ="{$memid}_{$shopId}_SHOP";
        }
        $response = [];
        foreach ($json['sql'] as $key => $value) {
            $this->genProductCode($json,$table);
            $matCode = $this->getCode();
            $arrSql = array('material_code' => $matCode,
                            'material_unit_id' => $value['material_unit_id'],
                            'material_group_id' => $value['material_group_id'],
                            'material_name' => $value['material_name'],
                            'material_detail' => $value['material_detail'],
                            'picture' => $value['picture'],
                            'active' => $value['active']
                            );
                            
            $arrayName = array( 'action' => $json['action'],
                                'id' => $json['id'],
                                'registerId' => $json['registerId'],
                                'shopId' => $json['shopId'],
                                'employee' => $json['employee'],
                                'sql' => $arrSql,
                            );
            
    
                    
            if($db->Connect()){
                $matname = $this->hasMaterial($arrayName,$table);
                
                if($matname==true){
                    // ถ้ามีรายการ mat ที่เคยบันทึกไว้
                    $sql = "SELECT * FROM $table WHERE material_name = '".$value['material_name']."' AND material_unit_id = '".$value['material_unit_id']."'  AND material_group_id = '".$value['material_group_id']."'";           
                    if($db->CheckRow($sql)>0){
                        $res = $db->Select($sql);
                        foreach ($res as $key => $valueS) {
                            $arrSqlStock = array('material_code' => $valueS['material_code'],
                            'material_unit_id' => $value['material_unit_id'],
                            'material_group_id' => $value['material_group_id'],
                            'material_name' => $value['material_name'],
                            'material_detail' => $value['material_detail'],
                            'material_number' => $value['material_number'],
                            'material_price' => $value['material_price'],
                            'picture' => $value['picture'],
                            'active' => $value['active']
                            );
                            
                            $arrayNameStock = array( 'action' => $json['action'],
                                                'id' => $json['id'],
                                                'registerId' => $json['registerId'],
                                                'shopId' => $json['shopId'],
                                                'employee' => $json['employee'],
                                                'sql' => $arrSqlStock,
                                            );
                            $this->stock->insert($arrayNameStock,"stock",$valueS['id']);
                            array_push($response,array('name' => $value['material_name'], 'value' =>true));
                        }
                    }else{
                        array_push($response,array('name' => $value['material_name'], 'value' =>false));
                        //return false; 
                    }   
                }else{                   
                    // ถ้ายังไม่มีรายการ mat ที่เคยบันทึกไว้
                    $sql = "SELECT * FROM $table";
                    if($db->CheckTable($sql)){
                        $sql = $dbMage->strInsert($table,$arrayName);
                        if($db->Iquery($sql)){
                            $arrSqlStock = array('material_code' => $matCode,
                                'material_unit_id' => $value['material_unit_id'],
                                'material_group_id' => $value['material_group_id'],
                                'material_name' => $value['material_name'],
                                'material_detail' => $value['material_detail'],
                                'material_number' => $value['material_number'],
                                'material_price' => $value['material_price'],
                                'picture' => $value['picture'],
                                'active' => $value['active']
                                );
                            
                            $arrayNameStock = array( 'action' => $json['action'],
                                                'id' => $json['id'],
                                                'registerId' => $json['registerId'],
                                                'shopId' => $json['shopId'],
                                                'employee' => $json['employee'],
                                                'sql' => $arrSqlStock,
                                            );
                            $this->stock->insert($arrayNameStock,"stock",$db->GetlastID());
                            array_push($response,array('name' => $value['material_name'], 'value' =>true));
                   
                        }else{
                            array_push($response,array('name' => $value['material_name'], 'value' =>false));
                            //return false;
                        }
                        
                    }else{
                        array_push($response,array('name' => $value['material_name'], 'value' =>false));
                        //return false;
                    }
                }                
            }else{
                return false;
            } 
        }
        
        return $response;  
        
    }
    private function hasMaterial($json,$table){
        $db = $this->db;
        $dbMage =$this->dbMage;
        $material_name = $json['sql']['material_name'];
        $material_unit_id = $json['sql']['material_unit_id'];
        $material_group_id = $json['sql']['material_group_id'];
        $sql = "SELECT * FROM $table WHERE material_name ='".$material_name."' AND material_unit_id = '".$material_unit_id."' AND material_group_id = '".$material_group_id."'";
      
        if($db->CheckRow($sql)>0){
            return true;
        }else{
            return false;
        }
    }
    
    private function material_gen_qr($json,$table,$material_id,$stockId){        
        $db = $this->db;
        $dbMage =$this->dbMage;
        $db->Disconnect();
        if(!isset( $json['shopId'])){
            $db->database ="{$json['registerId']}_MEMBER";
        }else if(isset( $json['shopId'])){
            $db->database ="{$json['registerId']}_{$json['shopId']}_SHOP";
        }
        $arrSql = array('material_id' => $material_id,
                        'stock_id' => $stockId,
                        'active' => '1'
                         );
        $arrayName = array( 'action' => $json['action'],
                            'id' => $json['id'],
                            'registerId' => $json['registerId'],
                            'shopId' => $json['shopId'],
                            'sql' => $arrSql,
                         );
                       
        if($db->Connect()){
            $sql = "SELECT * FROM $table WHERE material_id = '".$material_id."'";
            if($db->CheckRow($sql)>0){
                $sql = $dbMage->strUpdateWhere($table,'material_id',$material_id,$arrayName);
              
                if($db->Iquery($sql)){
                    return true;
                }else{
                    return false;
                }
            }else{
                $sql = "SELECT * FROM $table";
                if($db->CheckTable($sql)){
                    $sql = $dbMage->strInsert($table,$arrayName);
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
    private function genProductCode($json,$table=''){        
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
                $out = $db->Select($sql);   
                foreach ($out as $key => $value) {
                    if($value['material_code'] == "" || is_null($value['material_code'])){
                        $this->setCode("MAT",($db->CheckRow($sql)+1));
                    }else{
                        $this->setCode("",$value['material_code'],false);
                    }                    
                }                
            }else{                
                $sql = "SELECT * FROM $table";               
                $this->setCode("MAT",($db->CheckRow($sql)+1));
            }
        }else{
            return false;
        }
    }

    private function getCode(){
        return $this->kcode;
    }
    private function setCode($keys,$code,$m = true){
        if($m==true){
            if(intval($code)<10){
                $this->kcode = $keys."00000".$code;
            }else if(intval($code)<100){
                $this->kcode = $keys."0000".$code;
            }else if(intval($code)<1000){
                $this->kcode = $keys."000".$code;
            }else if(intval($code)<10000){
                $this->kcode = $keys."00".$code;
            }else if(intval($code)<100000){
                $this->kcode = $keys."0".$code;
            }else{
                $this->kcode = $keys.$code;
            }
        }else{
            $this->kcode = $code;
        }
        
    }
}
