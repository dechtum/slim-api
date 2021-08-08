<?php 
require_once __DIR__.'/../../Libs/Date.php';
class LeaveLimit extends Date
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function numleave($appid)
    {
        $sql = "SELECT leave_request.*,YEAR(leave_request) as years,tb_leave_setting.leave_set_worklift,tb_leave_setting.leave_typeId as setting_id,tb_leave_setting.num_days,tb_leave_setting.num_income  FROM (SELECT tb_leave_request.id,SUM(tb_leave_request.total_leave) as total_leave ,((SUM(tb_leave_request.total_leave)/60)/8) as sumday,SUM(tb_leave_request.day_leave) as day_leave,tb_leave_request.leave_type,tb_leave_request.leave_typeTxt FROM tb_leave_request  WHERE tb_leave_request.id_application = '$appid' AND  chk_cancel = '0' GROUP BY leave_type) AS leave_request LEFT JOIN tb_leave_setting ON leave_request.leave_type = tb_leave_setting.id";
        $arr = [];
        if ($this->db->CheckRow($sql) > 0) {
            $res = $this->db->Select($sql);
            foreach ($res as $value) {
                if (floatval($value['sumday']) >= floatval($value['num_days'])) {
                    if ($value['leave_set_worklift'] == 1){
                        //แก้ไขต่อ
                    } else {
                        if($value['years'] == date('Y')){
                            array_push($arr, $value['leave_type']);
                        }
                    }
                }
            }
        }
        return $arr;
    }
    public function limitleave($appid, $idLeave = '', $totalLeave = '')
    {
        
        $sql = "SELECT leave_request.*,tb_leave_setting.num_days,tb_leave_setting.leave_tab1,tb_leave_setting.leave_tab2,tb_leave_setting.leave_tab3,tb_leave_setting.leave_tab4,tb_leave_setting.leave_tab5,tb_leave_setting.leave_set_worklift,tb_leave_setting.leave_typeId as setting_id,tb_leave_setting.num_days,tb_leave_setting.num_income  FROM (SELECT tb_leave_request.id,SUM(tb_leave_request.total_leave) as total_leave ,((SUM(tb_leave_request.total_leave)/60)/8) as sumday,SUM(tb_leave_request.day_leave) as day_leave,tb_leave_request.leave_type,tb_leave_request.leave_typeTxt FROM tb_leave_request  WHERE tb_leave_request.id_application = '$appid' AND  chk_cancel = '0' GROUP BY leave_type) AS leave_request LEFT JOIN tb_leave_setting ON leave_request.leave_type = tb_leave_setting.id WHERE tb_leave_setting.id = '$idLeave'";
        $arr = [];

        $totals = floatval(((floatval($totalLeave) / 60) / 8));
      
        if ($this->db->CheckRow($sql) > 0) {
            $res = $this->db->Select($sql);
            foreach ($res as $value) {
                if ($value['leave_type'] == $idLeave) {
                    if ($value['leave_set_worklift'] == 0) {
                        if (floatval($totals) > floatval($value['num_days'])) {
                            return false;
                        } else {
                            return true;
                        }
                    }else{
                        //สำหรับพักร้อนหรือกำหนดตามอายุงาน
                        $month = $this->workLift($appid);
                        if ($month >= 60) {
                            $y5 = json_decode($value['leave_tab5'], true);
                            if (floatval($totals) > floatval($y5['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 48) {
                            $y4 = json_decode($value['leave_tab4'], true);
                            if (floatval($totals) > floatval($y4['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 36) {
                            $y3 = json_decode($value['leave_tab4'], true);
                            if (floatval($totals) > floatval($y3['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 24) {
                            $y2 = json_decode($value['leave_tab2'], true);
                            if (floatval($totals) > floatval($y2['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 12) {
                            $y1 = json_decode($value['leave_tab1'], true);
                            if (floatval($totals) > floatval($y1['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 11) {
                            if (floatval($totals) > floatval(5)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 10) {
                            if (floatval($totals) >= floatval(5)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 9) {
                            if (floatval($totals) >= floatval(4)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 8) {
                            if (floatval($totals) >= floatval(4)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 7) {
                            if (floatval($totals) >= floatval(3)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 6) {
                            if (floatval($totals) >= floatval(3)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 5) {
                            if (floatval($totals) >= floatval(2)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 4) {
                            if (floatval($totals) >= floatval(2)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 3) {
                            if (floatval($totals) >= floatval(1)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 2) {
                            if (floatval($totals) >= floatval(1)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                    //array_push($arr,$value['leave_type']);
                } else {
                    goto a;
                }
            }
        } else {
            if ($idLeave == '8') return true; //ลาออก
            $sql = "SELECT * FROM tb_leave_setting WHERE tb_leave_setting.id = '$idLeave'";
            if ($this->db->CheckRow($sql) > 0) {
                a:
                $res = $this->db->Select($sql);
                foreach ($res as $value) {
                    if ($value['leave_set_worklift'] == 0) {
                        if (floatval($totals) > floatval($value['num_days'])) {
                            return false;
                        } else {
                            return true;
                        }
                    } else {

                        // เพิ่ม สำหรับ ลาบวช
                        if ($idLeave == '1'){
                            $y1 = json_decode($value['leave_tab1'], true);
                          // print_r(">>>".$idLeave."<<<".floatval($totals)." > ".floatval($y1['num_income']));
                            
                            if (floatval($totals) > floatval($y1['num_income'])) {
                                return false;
                            } else {
                                return true;
                            }
                            
                        }
                        //สำหรับพักร้อนหรือกำหนดตามอายุงาน
                        $month = $this->workLift($appid);

                        if ($month >= 60) {
                            $y5 = json_decode($value['leave_tab5'], true);
                            if (floatval($totals) > floatval($y5['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 48) {
                            $y4 = json_decode($value['leave_tab4'], true);
                            if (floatval($totals) > floatval($y4['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 36) {
                            $y3 = json_decode($value['leave_tab4'], true);
                            if (floatval($totals) > floatval($y3['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 24) {
                            $y2 = json_decode($value['leave_tab2'], true);
                            if (floatval($totals) > floatval($y2['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 12) {
                            $y1 = json_decode($value['leave_tab1'], true);
                           
                            
                            if (floatval($totals) > floatval($y1['num_days'])) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 11) {
                            if (floatval($totals) > floatval(5)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 10) {
                            if (floatval($totals) >= floatval(5)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 9) {
                            if (floatval($totals) >= floatval(4)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 8) {
                            if (floatval($totals) >= floatval(4)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 7) {
                            if (floatval($totals) >= floatval(3)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 6) {
                            if (floatval($totals) >= floatval(3)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 5) {
                            if (floatval($totals) >= floatval(2)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 4) {
                            if (floatval($totals) >= floatval(2)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 3) {
                            if (floatval($totals) >= floatval(1)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else if ($month >= 2) {
                            if (floatval($totals) >= floatval(1)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
        //return json_encode($arr);
    }
    public function workLift($appid)
    {
      
        $db =  $this->db;
        $hire = 0;
        if ($appid != "") {
            $sql = "SELECT hiredate FROM tb_porsen_em_detail WHERE id_application = '$appid'";
            $res = $db->Select($sql);
            foreach ($res as $key => $value) {
                $hire = $this->date_datediff('m', $value['hiredate'], date('Y-m-d'));
            }
        }
        return $hire;
    }
    public function checkPromote($appid, $idLeave = '', $hire)
    {
        $sql = "SELECT leave_request.*,tb_leave_setting.leave_typeId as setting_id,tb_leave_setting.num_days,tb_leave_setting.num_income,tb_leave_setting.leave_set_promote  FROM (SELECT tb_leave_request.id,SUM(tb_leave_request.total_leave) as total_leave ,((SUM(tb_leave_request.total_leave)/60)/8) as sumday,SUM(tb_leave_request.day_leave) as day_leave,tb_leave_request.leave_type,tb_leave_request.leave_typeTxt FROM tb_leave_request  WHERE tb_leave_request.id_application = '$appid' AND  chk_cancel = '0' GROUP BY leave_type) AS leave_request LEFT JOIN tb_leave_setting ON leave_request.leave_type = tb_leave_setting.id WHERE tb_leave_setting.id = '$idLeave'";
        $arr = [];
        $totals = 119;
        if ($this->db->CheckRow($sql) > 0) {
            $res = $this->db->Select($sql);
            foreach ($res as $value) {
                if ($value['leave_type'] == $idLeave) {
                    if ($this->checkContract($appid)) {
                        return true;
                    } else {
                        if ($value['leave_set_promote'] == '1') {
                            if ($this->checkContract($appid)) {
                                if (floatval($hire) >= floatval($totals)) {
                                    return true;
                                } else {
                                    return "ยังไม่ผ่านทดลองงาน ( อายุงาน " . $hire . " วัน )";
                                }
                            } else {
                                return "ยังไม่มีสัญญาจ้างประจำ";
                            }
                        } else {
                            return true;
                        }
                    }
                }
            }
        } else {
            //แก้ไขต่อ วันพรุ่ง
            $sql = "SELECT * FROM tb_leave_setting WHERE tb_leave_setting.id = '$idLeave'";
            if ($this->db->CheckRow($sql) > 0) {
                $res = $this->db->Select($sql);
                foreach ($res as $key => $value) {
                    if ($value['leave_set_promote'] == '1') {
                        if (floatval($hire) >= floatval($totals)) {
                            return true;
                        } else {
                            return "ยังไม่ผ่านทดลองงาน ( อายุงาน " . $hire . " วัน )";
                        }
                    } else {
                        return true;
                    }
                }
            } else {
                return "เพิ่มวันลาก่อนทำลายการ";
            }
        }
    }
    public function checkContract($appid)
    {
        $sql = "SELECT * FROM tb_Contract WHERE id_application = '$appid' AND cont_group = '1' AND cont_type = '3' ORDER BY id DESC LIMIT 0,1";
        if ($this->db->CheckRow($sql) > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getNameLeave($id){
        $sql = "SELECT * FROM tb_leave_setting WHERE id = $id";
        if($this->db->CheckRow($sql)>0){
            $res = $this->db->Select($sql);                                
            foreach ($res as $key => $value) {
               return $value['leave_typeTxt'];
            }                   
        }
    }
}
