<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__."/../Libs/index.php";
require_once __DIR__."/../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php";



class download
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
            case 'download':                
                return $this->download($json,$json['images']);
                break;
            default:
                break;
        }
    }


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
        $target_dir = __DIR__."/../public/{$request['folder']}/";
        $subfolder = $target_dir."{$request['id']}/";
        //$subfolder3 = $subfolder2.(isset($request[7])?$y."-".$m."-".$d."/":"{$year}-{$month}-{$day}/"); // leave_type
        //$subfolder2 = $subfolder."".$dayofyear."/";


        // $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if(!file_exists($target_dir)) {
            @mkdir($target_dir , 0777);
            @chmod($target_dir , 0777);
        }
        if(!file_exists($subfolder)){
            @mkdir($subfolder , 0777);
            @chmod($subfolder , 0777);
        }
        $arr = [];
        $len = count($pic);

        for ($i=0; $i < $len; $i++) {

            $target_file = $subfolder."namePIC{$i}.json";
           // $pic = str_replace('data:image/png;base64,', '', $pic[$i]);
            $pics = str_replace(' ', '+', $pic[$i]);
            $data = base64_decode($pics);
            if(file_exists($target_file)){
                $obj = new stdClass();
                $obj->ls = "";
                $obj->url = "มีข้อมูลแล้ว";
            }else{
                file_put_contents($target_file,$data);
            }
        }
        $output = shell_exec("ls {$subfolder}");
        $oparray = preg_split('/\s+/', trim($output));
        // $obj = new stdClass();
        // $obj->ls = $oparray;
        // $obj->url = $subfolder;
        return $oparray;

    }
    public function download($request,$pic){
        
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $target_dir = __DIR__."/../public/{$request['folder']}/";
        $subfolder = $target_dir."{$request['id']}/";
        
        $arr = [];
        $len = count($pic);

        for ($i=0; $i < $len; $i++) {
            $target_file = $subfolder.$pic[$i];
            if(file_exists($target_file)){                
                $pics = file_get_contents($target_file);
                $data = base64_encode($pics);
                array_push($arr,$data);
                
            }else{
                array_push($arr,"NO DATA");                
            }
        }
        return $arr;
        

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

}
