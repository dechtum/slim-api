<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";



class product
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
            case 'update':
                $newIdRow = $this->set($json,'product_sales');
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
                    print_r($sql);
                    if($db->Iquery($sql)){
                        
                        $this->product_sales_item($json,$db->GetlastID());
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
    private function product_sales_item($json,$saleID){
        
        $db = $this->db;
        $dbMage =$this->dbMage;
        $re = json_decode($json['product_sales_item']);
        $cc = count($re);
        foreach ($re as $key => $value) {
            print_r($re);
            $arr =  array('product_sales_id' => $saleID, 
                          'product_id' => $value->product_id ,
                          'product_code' => $value->product_code,
                          'product_name' => $value->product_name,
                          'product_detail' => $value->product_detail,
                          'product_price' => $value->product_price,
                          'product_unit' => $value->product_unit,
                          'item_sum_total' => $value->item_sum_total,
                          'active' => $value->active
                        );

            $item = array('sql' => $arr);
            
            $sql = $dbMage->strInsert('product_sales_item',$item);
           
            if($db->Iquery($sql)){
                
            }else{
                return false;
            }
        }
               
    }
}
