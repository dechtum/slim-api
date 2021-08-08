<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";



class register
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
            case 'register':
                return $this->register($json);
                break;
            case 'update':
                $newIdRow = $this->set($json,$json['action']);
                echo $newIdRow;
                break;
            case 'delete':
                $sql = "DELETE FROM $json->action WHERE id = '$json->id'";
                echo $this->del($sql);
                break;
            case 'fileupload':
                echo $this->uploadContext($request,$json->pic);  //allfile
                break;
            case 'filedelete':
                echo $this->remove($request);
                break;
            case 'fileall':
                echo $this->fileall($request);
                break;
            case 'lastrow':
                $sql = "SELECT * FROM $json->action WHERE id_application = $json->id_application ORDER BY id DESC LIMIT 0,1";
                echo $this->get($sql);
                break;
            case 'lastid':
                $sql = "SELECT * FROM $json->action ORDER BY id DESC LIMIT 0,1";
                echo $this->getLastId($sql);
                break;
            default:
                break;
        }
    }
    ///////////// Function Main
    public function register($json){ //FOR SELECT
        $db = $this->db;
        $db->Disconnect();
        if($db->Connect()){
            $regs = $db->registor($json,"member_register");
            if(is_int($regs)){
                if($this->createTableMEMBER("MEMBER",$regs)){
                    return $regs;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
   }
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
        $db->database ="ADMIN";
        if($db->Connect()){
            $sql = "SELECT * FROM $table WHERE id = '".$json['id']."'";
            if($db->CheckRow($sql)>0){
                $sql = $dbMage->strUpdate($table,$json['id'],$json);

                if($db->Iquery($sql)){
                //  $sql = "SELECT id FROM $table ORDER BY id DESC LIMIT 0,1";
                //  $res = $db->Select($sql);
                //  foreach ($res as $key => $value){
                //  echo $value['id'];
                //  }

                    return true;
                }else{
                return false;
                }
            }else{
                $sql = "SELECT * FROM $table";
                if($db->CheckTable($sql)){
                    $sql = $dbMage->strInsert($table,$json);
                    if($db->Iquery($sql)){
                      //  echo var_dump($db->GetlastID());
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
    ///////////// Function OTHER
    public function fileall($request){
        $json = json_decode(file_get_contents('php://input'));
        if(is_dir($json->url)) {
            $output = shell_exec("ls {$json->url}/{$json->create_date}");
            $oparray = preg_split('/\s+/', trim($output));
            $obj = new stdClass();
            $obj->ls = $oparray;
            $obj->url = "{$json->url}/{$json->create_date}";
            return json_encode($obj);
        }else{
            return false;
        }
    }
    public function upload($request){
     //   $dayofyear =date('z') + 1;
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        if(isset($request[7])){
            list($y,$m,$d)=explode("A",$request[7]);
        }


        foreach ($_FILES as $key => $value) {
            $target_dir = "public/{$request[2]}/"; // leaverequest
            $subfolder = $target_dir."{$request[4]}/"; // id_company
            $subfolder1 = $subfolder."{$request[5]}/"; // id_em_detail
            $subfolder2 = $subfolder1."{$request[6]}/"; // leave_type
            $subfolder3 = $subfolder2.(isset($request[7])?$y."-".$m."-".$d."/":"{$year}-{$month}-{$day}/"); // leave_type

            //$subfolder2 = $subfolder."".$dayofyear."/";
            $target_file = $subfolder3.$value["name"];

            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if(!file_exists($target_dir)) {
                @mkdir($target_dir , 0777);
                @chmod($target_dir , 0777);
            }
            if(!file_exists($subfolder)){
                @mkdir($subfolder , 0777);
                @chmod($subfolder , 0777);
            }
            if(!file_exists($subfolder1)){
                @mkdir($subfolder1 , 0777);
                @chmod($subfolder1 , 0777);
            }
            if(!file_exists($subfolder2)){
                @mkdir($subfolder2 , 0777);
                @chmod($subfolder2 , 0777);
            }
            if(!file_exists($subfolder3)){
                @mkdir($subfolder3 , 0777);
                @chmod($subfolder3 , 0777);
            }
            if(file_exists($target_file)){
                return "มีข้อมูลแล้ว";
            }else{
                if (move_uploaded_file($value["tmp_name"], $target_file)) {
                    $output = shell_exec("ls {$subfolder3}");
                    $oparray = preg_split('/\s+/', trim($output));
                    $obj = new stdClass();
                    $obj->ls = $oparray;
                    $obj->url = $subfolder3;
                    return json_encode($obj);
                } else {
                    return false;
                }
            }
        }
    }
    public function uploadContext($request,$pic){
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $target_dir = "public/{$request[2]}/"; // checkid
        $subfolder = $target_dir."{$request[4]}/"; // id_company
        $subfolder1 = $subfolder."{$request[5]}/"; // id_application
        $subfolder2 = $subfolder1."{$request[6]}/";
        $subfolder3 = $subfolder2."{$request[8]}/";
        $subfolder4 = $subfolder3."{$request[7]}_";
        //$subfolder3 = $subfolder2.(isset($request[7])?$y."-".$m."-".$d."/":"{$year}-{$month}-{$day}/"); // leave_type
        //$subfolder2 = $subfolder."".$dayofyear."/";
        $target_file = $subfolder3.$request[7].".png";
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if(!file_exists($target_dir)) {
            @mkdir($target_dir , 0777);
            @chmod($target_dir , 0777);
        }
        if(!file_exists($subfolder)){
            @mkdir($subfolder , 0777);
            @chmod($subfolder , 0777);
        }
        if(!file_exists($subfolder1)){
            @mkdir($subfolder1 , 0777);
            @chmod($subfolder1 , 0777);
        }
        if(!file_exists($subfolder2)){
            @mkdir($subfolder2 , 0777);
            @chmod($subfolder2 , 0777);
        }
        if(!file_exists($subfolder3)){
            @mkdir($subfolder3 , 0777);
            @chmod($subfolder3 , 0777);
        }
        if(file_exists($target_file)){
            $obj = new stdClass();
            $obj->ls = "";
            $obj->url = "มีข้อมูลแล้ว";
            return json_encode($obj);
        }else{
            $pic = str_replace('data:image/png;base64,', '', $pic);
            $pic = str_replace(' ', '+', $pic);
            $data = base64_decode($pic);
            file_put_contents( $target_file,$data);
            if(file_exists($target_dir)){
                $output = shell_exec("ls {$subfolder3}");
                $oparray = preg_split('/\s+/', trim($output));
                $obj = new stdClass();
                $obj->ls = $oparray;
                $obj->url = $target_file;
                return json_encode($obj);
            }else {
                return false;
            }
        }
    }
    public function remove($request){
        $json = json_decode(file_get_contents('php://input'));
        $target_dir = $json->url."/".$json->name;
        if(file_exists($target_dir)) {
            chmod($target_dir , 0755); //Change the file permissions if allowed
            if(unlink($target_dir)){
                $output = shell_exec("ls {$json->url}");
                $oparray = preg_split('/\s+/', trim($output));
                $obj = new stdClass();
                $obj->ls = $oparray;
                $obj->url = $json->url;
                return json_encode($obj);
            }else{
                return false;
            }
        }
    }
    public function ExportExcelAll($sql=''){
        $db = $this->db;
        $db->Disconnect();
        if($db->Connect()){
           if($db->CheckRow($sql)>0){
               $out =  $db->Select($sql);
               $arr = [];
               foreach ($out as $key => $value) {
                    $obj = new stdClass();
                    $obj->id = $value['id'];
                    $obj->id_application = $value['id_application'];
                    $obj->cmp_name = $value['name'];
                    $ubtime = explode("T",$value['chk_in_frist']);
                    $btime = explode(".",$ubtime[1]);
                    if($btime[1]=="000Z"){
                        // web
                        $obj->date_time = $btime[1] == "000Z" ?$btime[0] :$value['chk_in_frist'];
                        if($value['chk_out_last']!='0'){
                            $ubtime1 =  explode("T",$value['chk_out_last']);
                            $btime1 = explode(".",$ubtime1[1]);
                            $obj->date_time2 = $btime1[1] == "000Z" ?$btime1[0] :$value['chk_out_last'];
                            $obj->status = '1';
                        }else{
                            $obj->date_time2 = '0';
                            $obj->status = '0';
                        }
                        $obj->device = 'web';
                    }else{
                        $obj->date_time = $value['chk_in_frist'];
                        if($value['chk_out_last']!='0'){
                            $obj->date_time2 = $value['chk_out_last'];
                            $obj->status = '1';
                        }else{
                            $obj->date_time2 = '0';
                            $obj->status = '0';
                        }
                        $obj->device = 'mobile';
                        // mobile
                    }


                    $obj->create_date = $value['create_date'];
                    array_push($arr,$obj);
               }
               return json_encode($arr);
           }else{
               return false;
           }
        }else{
            return false;
        }
    }
    public function getLastId($sql=''){
        $db = $this->db;
        $db->Disconnect();
        if($db->Connect()){
           if($db->CheckRow($sql)>0){
                $res = $db->Select($sql);
                foreach ($res as $key => $value){
                    echo $value['id'];
                }
           }else{
               return false;
           }
        }else{
            return false;
        }
    }
    public function login($username,$password,$host){
        $db = $this->db;
        $db->Disconnect();
        if($db->Connect()){
           if($db->login($username,$password,$host)!=false){
                return $db->login($username,$password,$host);
           }else{
               return false;
           }
        }else{
            return false;
        }
    }
    public function createTableMEMBER($database,$uid){
        $db = $this->db;
        $dbMage =$this->dbMage;
        $db->Disconnect();
        if($db->ConnectNoDatabase()){
            $databseName = "{$uid}_{$database}";
            if($db->showDatabase($databseName)==false){
                if($db->CreateDB($databseName)){
                    //สร้าง table
                    $db->database = $databseName;
                    $db->Disconnect();
                    if($db->Connect()){
                        $member = file_get_contents(__DIR__."/../../mysql_admin/member.sql");
                        return $db->execute($member);
                    }
                }
            }
        }else{
            return false;
        }
    }
}
