<?php 
    class PermiMenu
    {
        public $url = "permission/permis_btn.php";
       
        public function  setBtn($obj,$response){
            $json  = json_decode($response);
        }
        public function root(){
            return true;
           // return (($_SESSION['user_id']==4||$_SESSION['user_id']==82||$_SESSION['user_id']==7 ||$_SESSION['user_id']==2)?true:false);
        }
        public function supper_admin($db,$id=''){
            if($id!=''){
                $sql = "SELECT * FROM tb_porsen_em_group WHERE porsen_em_group_role = 5 AND porsen_em_positionId = $id";
                if($db->CheckRow($sql)>0){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        public function getlavel($db,$id=''){
            if($id!=''){
                $sql = "SELECT porsen_em_group_role FROM tb_porsen_em_group WHERE porsen_em_positionId = $id";
                if($db->CheckRow($sql)>0){
                    $res = $db->Select($sql);
                    foreach ($res as $key => $value) {
                        return $value['porsen_em_group_role'];
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }

        }
    }
    class GenMenu extends PermiMenu
    {
        private $arrAllow = '';
        private $arrAllows = [];
        private $arrmenu = '';
        private $arrmenus = [];
        private $db;
        public $lag='';
        
        public function setLagauge($lag=''){
            $this->lag = $lag;
        }
        public function GenMenuInit($db,$obj,$Permision){
         
            $djson = json_decode($obj);
            $submenu = json_decode($djson->submenu);  //$submenu[0]->name
            $sub = count($submenu);
            $memu = '';
            $arrayMainManu = [];
            $impodemenu = '';
            $sub1Menu = '';
            $name = ($this->lag==''?'name':($this->lag=='th'?'name':'page'));
            if($sub>0){
                foreach ($submenu as $key => $value){
                    if($value->submenu!=''){
                        $showsub2 = '';
                        $sub2 = $value->submenu;
                        if(count($sub2)>0){
                            foreach ($sub2 as $s2key => $s2value) {
                                $showsub3 = '';
                                    $sub3 = $s2value->submenu;
                                    if(count($sub3)>0){
                                        foreach ($sub3 as $s3key => $s3value) {
                                            //echo $s3value->name;
                                            $showsub3 .= '<li onclick="ChagePage(this)" data-url="'.$s3value->link.'" data-page="'.$s3value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column"  class="menu-link">'.$s3value->{$name}.'</a></li>';
                                        }
                                        $m2 = '<li class="nav-item has-sub" >
                                                            <a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$s2value->{$name}.'</a>
                                                            <ul class="menu-content">
                                                                '.$showsub3.'
                                                            </ul>
                                                        </li>';
                                        $showsub2 .= $this->Sub2Selected($db,$s2value,$m2 );
                                    }else{
                                        if($this->root()){
                                            $showsub2 .= '<li onclick="ChagePage(this)" data-url="'.$s2value->link.'" data-page="'.$s2value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$s2value->{$name}.'</a></li>';
                                        }else{
                                            $showsub2 .= $this->Sub2Selected($db,$s2value,'<li onclick="ChagePage(this)" data-url="'.$s2value->link.'" data-page="'.$s2value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$s2value->{$name}.'</a></li>');
                                        }

                                    }

                            }
                            $m1 ='<li class="nav-item has-sub"  >
                                                 <a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$value->{$name}.'</a>
                                                 <ul class="menu-content">
                                                     '.$showsub2.'
                                                 </ul>
                                            </li>';
                            $impodemenu .= $this->Sub2Selected($db,$value,$m1);
                        }else{
                            if($this->root()){
                                $impodemenu .= '<li onclick="ChagePage(this)" data-url="'.$value->link.'" data-page="'.$value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$value->{$name}.'</a></li>';
                            }else{

                                $impodemenu .= $this->Sub1NoSubSelected($value,'<li onclick="ChagePage(this)" data-url="'.$value->link.'" data-page="'.$value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$value->{$name}.'</a></li>');
                            }
                        }
                    }else{
                        if($this->root()){
                            $impodemenu .= '<li onclick="ChagePage(this)" data-url="'.$value->link.'" data-page="'.$value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$value->{$name}.'</a></li>';
                        }else{
                            $impodemenu .= $this->Sub1Selected($value,'<li onclick="ChagePage(this)" data-url="'.$value->link.'" data-page="'.$value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-link">'.$value->{$name}.'</a></li>');
                        }
                    }
                }

                $memu = '
                    <li class="nav-item has-sub">
                        <a href="'.$djson->link.'"  data-page="'.$djson->page.'" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <title>'.$djson->{$name}.'</title>
                                <desc>Created with Sketch.</desc>
                                <defs></defs>
                                <g id="Stockholm-icons-/-Home-/-Library" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect id="bound" x="0" y="0" width="24" height="24"></rect>
                                    <path
                                        d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                        id="Combined-Shape" fill="#000000"></path>
                                    <rect id="Rectangle-Copy-2" fill="#000000" opacity="0.3"
                                        transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) "
                                        x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                                </g>
                            </span>
                            <span class="menu-text">'.$djson->{$name}.'</span>
                        </a>
                        <ul class="menu-content">
                            '.$impodemenu.'
                        </ul>
                    </li>';
            }else{
                $memu = '
                <li class="menu-item " aria-haspopup="true">
                    <a class="menu-link" href="'.$djson->link.'" data-page="'.$djson->page.'" >
                        <span class="svg-icon menu-icon">
                            <title>'.$djson->{$name}.'</title>
                            <desc>Created with Sketch.</desc>
                            <defs></defs>
                            <g id="Stockholm-icons-/-Home-/-Library" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect id="bound" x="0" y="0" width="24" height="24"></rect>
                                <path
                                    d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                    id="Combined-Shape" fill="#000000"></path>
                                <rect id="Rectangle-Copy-2" fill="#000000" opacity="0.3"
                                    transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) "
                                    x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                            </g>
                        </span>
                        <span class="menu-text">'.$djson->{$name}.'</span>
                    </a>
                </li>                   
                ';
            }

            if($djson->allow == "0"){
                //check permission user
                if($this->root()){
                    $arrayNameRoot = [];
                    $arrayNameRoot['id'] = intval($djson->id);
                    $arrayNameRoot['val'] = $memu;
                    array_push($this->arrAllows,$arrayNameRoot);
                }else{
                    $arrayName = [];
                    $arrayName['id'] = intval($djson->id);
                    $arrayName['val'] = $this->MainMenuSelected($db,$djson,$memu);
                    array_push($this->arrmenus,$arrayName);

                }
            }else if($djson->allow == "1"){

                $arrayNameRoot = [];
                $arrayNameRoot['id'] = intval($djson->id);
                $arrayNameRoot['val'] = $memu;
                array_push($this->arrAllows,$arrayNameRoot);
            }
            return false;
        }
        public function GenMenuInitReact($db,$obj,$Permision){
         
            $djson = json_decode($obj);
            $submenu = json_decode($djson->submenu);  //$submenu[0]->name
         
            $memu = '';
                    
         
            $name = ($this->lag==''?'name':($this->lag=='th'?'name':'page'));
            
            ///////////////////////// main ////////////////////////////
            $obj = new stdClass();  //main
            $obj->url = $djson->link;
            $obj->page = $djson->page;
            $obj->name = $djson->{$name};
            $obj->icon = $djson->icon;
            $obj->child = json_decode($djson->submenu);  //submenu 1  
            $memu = json_encode($obj);     
           
            if($djson->allow == "0"){
                //check permission user
                if($this->root()){
                    $arrayNameRoot = [];
                    $arrayNameRoot['id'] = intval($djson->id);
                    $arrayNameRoot['val'] = $memu;
                    array_push($this->arrAllows,$arrayNameRoot);  // parent menu                    
                }else{
                    $arrayName = [];
                    $arrayName['id'] = intval($djson->id);
                    $arrayName['val'] = $this->MainMenuSelected($db,$djson,$memu); // parent menu
                    array_push($this->arrmenus,$arrayName);                   
                }
            }else if($djson->allow == "1"){
                $arrayNameRoot = [];
                $arrayNameRoot['id'] = intval($djson->id);
                $arrayNameRoot['val'] = $memu;
                array_push($this->arrAllows,$arrayNameRoot);               
            }
           
            return false;
        }
        private function getSubMenu($djson){
            
            $submenu = json_decode($djson->submenu);  //$submenu[0]->name
            $name = ($this->lag==''?'name':($this->lag=='th'?'name':'page'));
            $child1Menu=[];
            $child2Menu=[];
            $child3Menu=[];
            $child4Menu=[]; 
            
            if(count($submenu)>0){
                foreach ($submenu as $key => $value){
                    if($value->submenu!=''){
                        $sub2 = $value->submenu;
                        if(count($sub2)>0){                           
                            foreach ($sub2 as $s2key => $s2value) {
                                $showsub3 = '';
                                $arr3parent = [];
                                $sub3 = $s2value->submenu;                               
                                if(count($sub3)>0){                                   
                                    foreach ($sub3 as $s3key => $s3value) {
                                        $obj = new stdClass();
                                        $obj->url = $s3value->link;
                                        $obj->page = $s3value->page;
                                        $obj->name = $s3value->{$name};
                                        $obj->icon = $s3value->icon;
                                        $obj->child = $child5Menu;
                                        array_push($child4Menu,$obj);                                     
                                        //$showsub3 .= '<li onclick="ChagePage(this)" data-url="'.$s3value->link.'" data-page="'.$s3value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column"  class="menu-item">'.$s3value->{$name}.'</a></li>';
                                    }
                                  
                                }else{
                                    if($this->root()){                                     
                                        $obj = new stdClass();
                                        $obj->url = $s2value->link;
                                        $obj->page = $s2value->page;
                                        $obj->name = $s2value->{$name};
                                        $obj->icon = $s2value->icon;
                                        $obj->child = $child4Menu; 
                                        array_push($child3Menu,$obj);  
                                        //array_push($showsub2arr,$arr3parent);
                                        //$showsub2 .= '<li onclick="ChagePage(this)" data-url="'.$s2value->link.'" data-page="'.$s2value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-item">'.$s2value->{$name}.'</a></li>';
                                    }else{                                        
                                        $obj = new stdClass();
                                        $obj->url = $s2value->link;
                                        $obj->page = $s2value->page;
                                        $obj->name = $s2value->{$name};
                                        $obj->icon = $s2value->icon;
                                        $obj->child = $child4Menu;                                       
                                        array_push($child3Menu,$this->Sub2Selected($db,$s2value,$obj));
                                        //$showsub2 .= $this->Sub2Selected($db,$s2value,'<li onclick="ChagePage(this)" data-url="'.$s2value->link.'" data-page="'.$s2value->page.'"><a href="#" data-i18n="nav.page_layouts.1_column" class="menu-item">'.$s2value->{$name}.'</a></li>');
                                    }
                                }
                            }
                            if($this->root()){                                
                                $obj = new stdClass();
                                $obj->url = $value->link;
                                $obj->page = $value->page;
                                $obj->name = $value->{$name};
                                $obj->icon = $value->icon;
                                $obj->child = $child3Menu;
                                array_push($child2Menu,$obj);
                            }else{                          
                                $obj = new stdClass();
                                $obj->url = $value->link;
                                $obj->page = $value->page;
                                $obj->name = $value->{$name};
                                $obj->icon = $value->icon;
                                $obj->child = $child3Menu;
                                array_push($child2Menu,$this->Sub1NoSubSelected($db,$value,$obj));
                            }
                        }
                    } 
                    //////////////// submenu1 /////////////////   
                    $obj = new stdClass();
                    $obj->url = $value->link;
                    $obj->page = $value->page;
                    $obj->name = $value->{$name};
                    $obj->icon = $value->icon;
                    $obj->child = $child2Menu;                                       
                    array_push($child1Menu,$obj);                    
                    //////////////// end submenu1 /////////////
                }                
            }
            return $child1Menu;
        }
        public function showAllow(){
            // asort($this->arrAllows);
            // return implode('',$this->arrAllows);

            ksort($this->arrAllows);
            $this->arrAllow = '';
            foreach ($this->arrAllows as $value){               
                $this->arrAllow .=$value['val'];
            }
            return $this->arrAllow;
        }
        public function showAllowReact(){
            // asort($this->arrAllows);
            // return implode('',$this->arrAllows);
            ksort($this->arrAllows);
            return $this->arrAllows;
        }
        public function showPermission(){
            ksort($this->arrmenus);
            $this->arrmenu = '';
            foreach ($this->arrmenus as $value){
                $this->arrmenu .=$value['val'];
            }
            return $this->arrmenu;
        }
        private function MainMenuSelected($db,$obj,$memu){
            $checkKey = '';
           
            $sql = "SELECT porsen_em_permision FROM tb_porsen_em_group WHERE porsen_em_positionId = '".$_SESSION['id_position']."'";
            if($db->CheckRow($sql)>0){
                $res = $db->Select($sql);
                foreach ($res as $key1 => $value1) {
                    //chk_5_View_C1202010491556494100
                    $meneChild = json_decode($value1['porsen_em_permision']);
                    foreach ($meneChild as $key => $value) {
                        $arr = $meneChild[$key];
                        foreach ($arr as $Mckey => $Mcvalue) {
                            $key = explode('_',$Mckey); //'chk_5_view_c0000000
                            if($key[1]==$obj->id && $key[3] = $obj->key){
                                if($Mcvalue == true){
                                    switch ($key[2]) {
                                        case 'View':
                                            if($checkKey == $obj->key){
                                            }else{
                                                return $memu;
                                            }
                                            $checkKey = $obj->key;
                                            break;
                                        case 'Add':

                                            break;
                                        case 'Edit':

                                            break;
                                        case 'Del':

                                            break;
                                        case 'Print':

                                            break;
                                        case 'Aprove':

                                            break;
                                        case 'Setting':

                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }else{
                                if($obj->allow == "1"){
                                    return $memu;
                                }
                            }
                        }
                    }

                }
            }
        }
        private function Sub1NoSubSelected($obj,$memu){
            $checkKey = '';
            $db = $this->db;
            $sql = "SELECT * FROM tb_porsen_em_group WHERE porsen_em_positionId = '".$_SESSION['id_position']."'";
            if($db->CheckRow($sql)>0){
                $res = $db->Select($sql);
                foreach ($res as $key1 => $value1) {
                    //chk_5_View_C1202010491556494100
                    $meneChild = json_decode($value1['porsen_em_permision']);
                    for ($i=0; $i < count($meneChild); $i++) {
                        foreach ($meneChild[$i] as $key => $value) {
                            $k = explode('_',$key);
                            if($k[3]==$obj->key && $k[1] == $obj->id){
                                if($value==true){
                                    switch ($k[2]) {
                                        case 'View':
                                            return $memu;
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }

                                }else{
                                    if($obj->allow == "1"){
                                        return $memu;
                                    }
                                }
                            }else{
                                if($obj->allow == "1"){
                                    return $memu;
                                }
                            }

                        }
                    }

                }
            }else{
                if($obj->allow == "1"){
                    return $memu;
                }
            }
        }
        private function Sub1Selected($obj,$memu){

            $checkKey = '';
            $db = $this->db;
            $sql = "SELECT * FROM tb_porsen_em_group WHERE porsen_em_positionId = '".$_SESSION['id_position']."'";
            if($db->CheckRow($sql)>0){
                $res = $db->Select($sql);
                foreach ($res as $key1 => $value1) {
                    //chk_5_View_C1202010491556494100
                    $meneChild = json_decode($value1['porsen_em_permision']);

                    foreach ($meneChild as $key => $value) {
                        $arr = $meneChild[$key];
                        foreach ($arr as $Mckey => $Mcvalue) {
                            $key = explode('_',$Mckey); //'chk_5_view_c0000000
                            if($key[1]==$obj->id && $key[3] = $obj->key){
                                if($Mcvalue == true){
                                    switch ($key[2]) {
                                        case 'View':
                                            if($checkKey == $obj->key){

                                            }else{
                                                return $memu;
                                            }

                                            $checkKey = $obj->key;
                                            break;
                                        case 'Add':

                                            break;
                                        case 'Edit':

                                            break;
                                        case 'Del':

                                            break;
                                        case 'Print':

                                            break;
                                        case 'Aprove':

                                            break;
                                        case 'Setting':

                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                }else{
                                    if($this->root()){
                                        return $memu;
                                    }else{
                                        return '';
                                    }

                                }
                            }else{
                                if($obj->allow == "1"){
                                    return $memu;
                                }

                            }
                        }
                    }

                }
            }else{
                if($obj->allow == "1"){
                    return $memu;
                }
            }
        }
        private function Sub2Selected($db,$obj,$memu){
            $checkKey = '';
           
            $sql = "SELECT * FROM tb_porsen_em_group WHERE porsen_em_positionId =  '22'";// '".$_SESSION['id_position']."'";
           
            if($db->CheckRow($sql)>0){
                $res = $db->Select($sql);
                foreach ($res as $key1 => $value1) {
                    //chk_5_View_C1202010491556494100
                    $meneChild = json_decode($value1['porsen_em_permision']);
                    foreach ($meneChild as $keys => $item) {

                        foreach ($item as $key=> $items) {
                            $k = explode('_',$key); //'chk_5_view_c0000000
                            switch ($k[2]) {
                                case 'View':
                                    if($k[3] == $obj->key){
                                        if($items == true){
                                            //echo var_dump($items);
                                            return $memu;
                                        }else{
                                            if($this->root()){
                                                return $memu;
                                            }
                                        }
                                    }
                                    break;
                                case 'Add':

                                    break;
                                case 'Edit':

                                    break;
                                case 'Del':

                                    break;
                                case 'Print':

                                    break;
                                case 'Aprove':

                                    break;
                                case 'Setting':

                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        }

                    }



                }
            }
        }
    }
    class Menu extends GenMenu
    {       
        private $db;
        public function AllMenu($db,$arrid=[])
        { // เพิ่มสำหรับ ADMIN ให้มองเห็น
            $this->db = $db;
            $ejson='';
            $sql = "SELECT * FROM tb_link_menu ORDER BY main_menu_index";            
            if ($db->CheckRow($sql) > 0){
                $res = $db->Select($sql);
                foreach ($res as $key => $value){
                    $obj = new stdClass();
                    $obj->id = $value['id'];
                    $obj->key = $value['id'];
                    $obj->name = $value['main_menu_th'];
                    $obj->page = $value['main_menu_en'];
                    $obj->link = $value['main_menu_link'];
                    $obj->icon = $value['main_menu_icon'];
                    $obj->index = $value['main_menu_index'];
                    $obj->allow = $value['main_menu_allow'];               
                    if (@count($value['main_menu_allow']) > 0 && $value['main_menu_allow'] != "[]") {
                        $ressub = json_decode($value['list']);
                        $submenu = [];
                        foreach ($ressub as $k => $v) {
                            $objsub = new stdClass();
                            $objsub->id = $value['id'];
                            $objsub->key = $v->key;
                            $objsub->name = $v->sum_menu_th;
                            $objsub->page = $v->sum_menu_en;
                            $objsub->link = $v->sum_menu_link;
                            $objsub->icon = '';
                            $objsub->index = $v->sum_menu_index;
                            $objsub->allow = $value['main_menu_allow'];
                            $subsub2 = array();
                            if (@count($v->sum_menu_list) > 0 && $v->sum_menu_list != "[]") {
                                $sub2 = $v->sum_menu_list;
                                foreach ($sub2 as $pkey => $pvalue) {
                                    $objsub2 = new stdClass();
                                    $objsub2->id = $value['id'];
                                    $objsub2->key = $pvalue->key;
                                    $objsub2->name = $pvalue->sum_menu_th;
                                    $objsub2->page = $pvalue->sum_menu_en;
                                    $objsub2->link = $pvalue->sum_menu_link;
                                    $objsub2->icon = '';
                                    $objsub2->index = $pvalue->sum_menu_index;
                                    $objsub2->allow = $value['main_menu_allow'];
                                    $subsub3 = array();
                                    if (@count($pvalue->sum_menu_list) > 0 && $pvalue->sum_menu_list != "[]" && $pvalue->sum_menu_list != "") {
                                        $sub3 = $pvalue->sum_menu_list;
                                        foreach ($sub3 as $p3key => $p3value) {
                                            $objsub3 = new stdClass();
                                            $objsub3->id = $value['id'];
                                            $objsub3->key = $p3value->key;
                                            $objsub3->name = $p3value->sum_menu_th;
                                            $objsub3->page = $p3value->sum_menu_en;
                                            $objsub3->link = $p3value->sum_menu_link;
                                            $objsub3->icon = '';
                                            $objsub3->index = $p3value->sum_menu_index;
                                            $objsub3->allow = $value['main_menu_allow'];
                                            array_push($subsub3, $objsub3);
                                        }
                                        $objsub2->submenu = $subsub3;
                                    } else {
                                        $objsub2->submenu = $subsub3;
                                    }
                                    array_push($subsub2, $objsub2);
                                }
                                $objsub->submenu = $subsub2;
                            } else {
                                $objsub->submenu = $subsub2;
                            }
                            array_push($submenu, $objsub);
                        }
                    }
                    $obj->submenu = json_encode($submenu);
                    $ejson = json_encode($obj);
                    $this->GenMenuInit($db,$ejson, $arrid);                  
                }
            } 
            $out = $this->showAllow();  
            $out .= $this->showPermission();   
            return $out;  
        } 
        public function AllMenuReact($db,$arrid=[])
        { // เพิ่มสำหรับ ADMIN ให้มองเห็น
            $this->db = $db;
            $ejson='';
            $ess =  "id = '2' ";
            $hr =  "id = '15' ";
            $sql = "SELECT * FROM tb_link_menu WHERE $ess ORDER BY main_menu_index";            
            if ($db->CheckRow($sql) > 0){
                $outmenu = [];
                $res = $db->Select($sql);
                foreach ($res as $key => $value){
                    $obj = new stdClass();
                    $obj->id = $value['id'];
                    $obj->key = $value['id'];
                    $obj->name = $value['main_menu_th'];
                    $obj->page = $value['main_menu_en'];
                    $obj->link = $value['main_menu_link'];
                    $obj->icon = $value['main_menu_icon'];
                    $obj->index = $value['main_menu_index'];
                    $obj->allow = $value['main_menu_allow'];   
                              
                    if ($value['main_menu_allow'] != "[]") {
                        $ressub = json_decode($value['list']);
                        $submenu = [];
                        foreach ($ressub as $k => $v) {
                            $objsub = new stdClass();
                            $objsub->id = $value['id'];
                            $objsub->key = $v->key;
                            $objsub->name = $v->sum_menu_th;
                            $objsub->page = $v->sum_menu_en;
                            $objsub->link = $v->sum_menu_link;
                            $objsub->icon = '';
                            $objsub->index = $v->sum_menu_index;
                            $objsub->child = $v->sum_menu_list;
                            $objsub->allow = $value['main_menu_allow'];
                            $subsub2 = [];
                            
                            if (count($v->sum_menu_list) > 0 && $v->sum_menu_list != "[]") {                               
                                $sub2 = $v->sum_menu_list;
                                foreach ($sub2 as $pkey => $pvalue) {
                                    $objsub2 = new stdClass();
                                    $objsub2->id = $value['id'];
                                    $objsub2->key = $pvalue->key;
                                    $objsub2->name = $pvalue->sum_menu_th;
                                    $objsub2->page = $pvalue->sum_menu_en;
                                    $objsub2->link = $pvalue->sum_menu_link;
                                    $objsub2->icon = '';
                                    $objsub->child = $pvalue->sum_menu_list;
                                    $objsub2->index = $pvalue->sum_menu_index;
                                    $objsub2->allow = $value['main_menu_allow'];
                                    $subsub3 = [];
                                    
                                    if (count($pvalue->sum_menu_list) > 0 && $pvalue->sum_menu_list != "[]" && $pvalue->sum_menu_list != "") {
                                        $sub3 = $pvalue->sum_menu_list;
                                        
                                        foreach ($sub3 as $p3key => $p3value) {
                                            $objsub3 = new stdClass();
                                            $objsub3->id = $value['id'];
                                            $objsub3->key = $p3value->key;
                                            $objsub3->name = $p3value->sum_menu_th;
                                            $objsub3->page = $p3value->sum_menu_en;
                                            $objsub3->link = $p3value->sum_menu_link;
                                            $objsub3->icon = '';
                                            $objsub->child = $p3value->sum_menu_list;
                                            $objsub3->index = $p3value->sum_menu_index;
                                            $objsub3->allow = $value['main_menu_allow'];
                                            array_push($subsub3, $objsub3);
                                        }
                                        $objsub2->submenu = $subsub3;
                                        
                                    }else{
                                        $objsub2->submenu = $subsub3;
                                    }
                                    array_push($subsub2, $objsub2);                                   
                                }
                                $objsub->submenu = $subsub2;
                            } else {
                                $objsub->submenu = $subsub2;
                            }
                            array_push($submenu, $objsub);                           
                        }
                    }
                    $obj->submenu = json_encode($submenu);
                    array_push($outmenu,$obj);

                    $ejson = json_encode($obj);                    
                    $this->GenMenuInitReact($db,$ejson, $arrid);                  
                }   
                echo json_encode($outmenu);  
            } 
            //  $out = $this->showAllowReact();  
            //  $out .= $this->showPermission(); 
             
            // return json_encode($out);  
        }    
    }
    class navnoti extends PermiMenu
    {
        public $idPermission;
        public $idApp='';
        private $rowNum=[];
        private $db;
        private  $arrMsg = [];
        private $sqlLeave = '';
        private $getSqlPermis = '';
        private $getRule = '';
    
    
        public function __construct($db)
        {
            $this->db = $db;
        }
        public function getSqlPermis()
        {
           return $this->getSqlPermis;
        }
        public function setSqlPermis($role)
        {
    
            $role = $role;
            $db = $this->db;
            $gPerm = $db->getPermiId();
            $gPosition = $db->getPermiIdOfPosition();
            $gPermCmp = $db->getPermiIdOfCompany();
            $gPermCmpSub = $db->getPermiIdOfCompanySub();
            $gPermShowList = $db->getPermiIdShowListName();
            $gPermisRule = $db->getPermiRule();
    
            $role->ColPos = "id_position";
            $role->ColCmp = "id_company";
            $companySub = " (id_company = '".$gPermCmp."'";
           
            if(is_array($gPermCmpSub)){
                if(count($gPermCmpSub)>0 && $gPermCmpSub !== null){
                    foreach ($gPermCmpSub as $key => $value) {
                        $companySub .= " OR id_company = '".$value."'";
                    }
                }
            }       
            $companySub .= ") ";
    
            switch ($this->getlavel($this->db,$_SESSION['id_position'])){
                case 0:
                    //$this->getSqlPermis = $role->CheckRole('tb_leave_request', " (chk_app = 1 AND chk_cancel = 1) AND (id_application == '".$_SESSION['id_application']."')", 'ORDER BY id DESC');
                    $this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE (chk_app = '1' OR chk_cancel = '1') AND id_application = '".$_SESSION['id_application']."'";
                    break;
                case 1:
                    $this->getSqlPermis = $role->CheckRole('tb_leave_request', " (chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."') AND ".$companySub, 'ORDER BY id DESC');
                    //$this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE (chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."') AND ".$companySub;
                    break;
                case 2:
                    $this->getSqlPermis = $role->CheckRole('tb_leave_request', " (chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."') AND ".$companySub, 'ORDER BY id DESC');
                    //$this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE (chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."') AND ".$companySub;
    
                    break;
                case 3:
                    $this->getSqlPermis = $role->CheckRole('tb_leave_request', " (chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."') AND".$companySub, 'ORDER BY id DESC');
                    //$this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE (chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."') AND".$companySub;
                    break;
                case 4:
                    $this->getSqlPermis = $role->CheckRole('tb_leave_request', " (chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."') AND ".$companySub, 'ORDER BY id DESC');
                    //$this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE chk_app = '0' AND chk_cancel = '0' AND id_application != '".$_SESSION['id_application']."' AND ".$companySub;
                    break;
                case 5:
                    //$this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE chk_app = '0' AND chk_cancel = '0' ".$sql;
                    $this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE chk_app = '0' AND chk_cancel = '0'";
                    //$this->getSqlPermis = $role->CheckRole('tb_leave_request', " (chk_app = '0' AND chk_cancel = '0') AND ".$companySub, 'ORDER BY id DESC');
                    break;
                default:
                  //$this->getSqlPermis = $role->CheckRole('tb_leave_request', " (chk_app = 1 AND chk_cancel = 1) AND (id_application == '".$_SESSION['id_application']."')", 'ORDER BY id DESC');
                    $this->getSqlPermis = "SELECT * FROM tb_leave_request WHERE (chk_app = '1' OR chk_cancel = '1') AND id_application = '".$_SESSION['id_application']."'";
                    break;
                }
    
        }
        public function getnumNew()
        {
            $db = $this->db;
            $idApp = $this->idApp;
            $idUnder = [];
            $sqlPos = '';
            $getSqlPermis = $this->getSqlPermis;
    
            $db->Disconnect();
            if ($db->Connect()) {
                $this->idPermission = $db->getPermiId();
                 if($idApp!=""){
                     $sqlUpper = "SELECT id,id_position FROM tb_porsen_em_detail WHERE id_application = '$idApp'";
                     if($db->CheckRow($sqlUpper)>0){
                        $resLogin = $db->Select($sqlUpper);
                        foreach ($resLogin as $porsenKey => $porsenRow){
                            $sqlPos = "SELECT tb_leave_request.* FROM tb_position RIGHT JOIN tb_leave_request ON tb_position.id = tb_leave_request.id_position WHERE pos_supperviser = '".$porsenRow['id_position']."'";
                            if($db->CheckRow($sqlPos) > 0){
                                $resIdupder = $db->Select($sqlPos);
                                foreach ($resIdupder as $UnderKey => $UnderVal) {
                                    array_push($idUnder,$UnderVal['id']);
                                }
                            }
                        }
                    }
                }
                 //return $sqlPos;
                return count($idUnder);
            }
        }
        public function getnum($where='')
        {
            $db = $this->db;
            $tb='';
            $db->Disconnect();
            if ($db->Connect()) {
                $cnt = $db->CheckRow($this->getSqlPermis);
                return $cnt  ;
            }
        }
        public function getmessage($where='')
        {
            $db = $this->db;
            $tb='';
            $db->Disconnect();
            if ($db->Connect()) {
                $cnt = $db->CheckRow($this->getSqlPermis);
                if ($cnt > 0) {
                    $res = $db->Select($this->getSqlPermis);
                    $this->arrMsg = [];
                    foreach ($res as $key => $value) {
                        $tb .= '<a href="javascript:gotoLeave(\''. $value['id'] . '\',\''.$value['leave_requestorTxt'].'\')" class="list-group-item">
                                <div class="media">
                                    <div class="media-left valign-middle">'.$this->checkCase($value['leave_type']).'</div>
                                    <div class="media-body">
                                        <h6 class="media-heading">' . $value['leave_requestorTxt'] . '</h6>
                                        <p class="notification-text font-small-3 text-muted">' . $value['leave_typeTxt'] . '</p>
                                        <p class="notification-text font-small-3 text-muted">' . $value['leave_date_from1'] . ' ( ' . ($value['day_leave'] > 0 ? $value['day_leave'] . " วัน " : " ") . ' ' . ($value['hour_leave'] > 0 ? $value['hour_leave'] . " ชั่วโมง" : "") . ')</p>
                                        <small>
                                            <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">'.$this->time_since(time() - strtotime($value['reg_date'])).'</time>
                                        </small>
                                    </div>
                                </div>
                            </a>';
                        array_push($this->rowNum, $key);
                    }
                    return  $tb;//json_encode($this->arrMsg);
                }
            }
        }
        public function getmessageNew($where='')
        {
            $db = $this->db;
            $tb='';
            $db->Disconnect();
            if ($db->Connect()) {
                $cnt = $db->CheckRow($this->getSqlPermis);
                if ($cnt > 0) {
                    $res = $db->Select($this->getSqlPermis);
                    $this->arrMsg = [];
                    foreach ($res as $key => $value) {
                        $tb .= '<a href="javascript:gotoLeave(\''. $value['id'] . '\',\''.$value['leave_requestorTxt'].'\')" >
                                <div class="media">
                                    <div class="media-left align-self-center">'.$this->checkCaseNew($value['leave_type']).'</div>
                                    <div class="media-body">
                                        <h6 class="media-heading">' . $value['leave_requestorTxt'] . '</h6>
                                        <p class="notification-text font-small-3 text-muted">' . $value['leave_typeTxt'] . '</p>
                                        <p class="notification-text font-small-3 text-muted">' . $value['leave_date_from1'] . ' ( ' . ($value['day_leave'] > 0 ? $value['day_leave'] . " วัน " : " ") . ' ' . ($value['hour_leave'] > 0 ? $value['hour_leave'] . " ชั่วโมง" : "") . ')</p>
                                        <small>
                                            <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">'.$this->time_since(time() - strtotime($value['reg_date'])).'</time>
                                        </small>
                                    </div>
                                </div>
                            </a>';
                        array_push($this->rowNum, $key);
                    }
                    return  $tb;//json_encode($this->arrMsg);
                }
            }
        }
        public function getPermisGroup(){
            $db = $this->db;
            $db->Disconnect();
            if ($db->Connect()) {
                return $db->getPermiId();
            }
        }
        public function getCompany()
        {
    
        }
        private function checkCase($key)
        {
            switch ($key) {
                case '1':
                    return '<i class="icon-pied-piper-alt icon-bg-circle bg-cyan"></i>';
                    break;
                case '2':
                    return '<i class="icon-heart-o icon-bg-circle bg-yellow bg-darken-3"></i>';
                    break;
                case '3':
                    return '<i class="icon-medkit2 icon-bg-circle bg-red"></i>';
                    break;
                default:
                    return '<i class="icon-bell3 icon-bg-circle bg-green"></i>';
                    break;
            }
        }
         private function checkCaseNew($key)
        {
            switch ($key) {
                case '1':
                    return '<i class="ft-plus-square icon-bg-circle bg-cyan"></i>';
                    break;
                case '2':
                    return '<i class="ft-heart icon-bg-circle bg-yellow"></i>';
                    break;
                case '3':
                    return '<i class="ft-plus-square icon-bg-circle bg-red"></i>';
                    break;
                default:
                    return '<i class="ft-bell icon-bg-circle bg-green"></i>';
                    break;
            }
        }
        private function time_since($since) {
            $chunks = array(
                array(60 * 60 * 24 * 365 , 'year'),
                array(60 * 60 * 24 * 30 , 'month'),
                array(60 * 60 * 24 * 7, 'week'),
                array(60 * 60 * 24 , 'day'),
                array(60 * 60 , 'hour'),
                array(60 , 'minute'),
                array(1 , 'second')
            );
    
            for ($i = 0, $j = count($chunks); $i < $j; $i++) {
                $seconds = $chunks[$i][0];
                $name = $chunks[$i][1];
                if (($count = floor($since / $seconds)) != 0) {
                    break;
                }
            }
    
            $print = ($count == 1) ? '1 '.$name : "$count {$name}s ago";
            return $print;
        }
    }    

?>