<?php 
    
    class stock
    {
        private $db = '';
        private $dbMage  = '';

        function __construct($db,$dbMage){
            $this->db = $db;
            $this->dbMage = $dbMage;
        }
        public function insert($json,$table,$material_id){        
            $db = $this->db;
            $dbMage =$this->dbMage;
            $db->Disconnect();
            if(!isset( $json['shopId'])){
                $db->database ="{$json['registerId']}_MEMBER";
            }else if(isset( $json['shopId'])){
                $db->database ="{$json['registerId']}_{$json['shopId']}_SHOP";
            }      
          
            $arrSql = array('material_id' => $material_id,
                            'stock_number' => $json['sql']['material_number'],
                            'stcok_unit_price' => (floatval($json['sql']['material_price'])*floatval($json['sql']['material_number'])),
                            'stock_update_status' => 'IN',
                            'stock_update_by' => $json['employee'],
                            'active' => '1'
                             );
                             
            $arrayName = array( 'action' => $json['action'],
                                'id' => $json['id'],
                                'registerId' => $json['registerId'],
                                'shopId' => $json['shopId'],
                                'sql' => $arrSql,
                             );
                       
                                                                                                    
            if($db->Connect()){
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
            }else{
                return false;
            }
        }
        public function update($json,$table,$material_id){        
            $db = $this->db;
            $dbMage =$this->dbMage;
            $db->Disconnect();
            if(!isset( $json['shopId'])){
                $db->database ="{$json['registerId']}_MEMBER";
            }else if(isset( $json['shopId'])){
                $db->database ="{$json['registerId']}_{$json['shopId']}_SHOP";
            }      
             
            $arrSql = array('material_id' => $material_id,
                            'stock_number' => $json['sql']['material_number'],
                            'stcok_unit_price' => (floatval($json['sql']['material_price'])*floatval($json['sql']['material_number'])),
                            'stock_update_status' => 'IN',
                            'stock_update_by' => $json['employee'],
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
                        $sql = "SELECT * FROM stock WHERE material_id = '".$material_id."'";                    
                        if($db->CheckRow($sql)>0){
                            $out = $db->Select($sql);
                            foreach ($out as $key => $value) {                        
                                return $value['id'];
                            }
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
    
?>
