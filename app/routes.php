<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use \Firebase\JWT\JWT;


return function (App $app) {


    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('https://mcs.co.th/admin');
        return $response;
    });

    $app->post('/register', function (Request $request, Response $response) {
        require_once __DIR__.'/../mcs/src/register.php';
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'),true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        //check user can update
        if(true){
            $reg = new register();
            $lastId  = $reg->init("register",$contents['sql']);
            if(is_int($lastId)){
                $msg = new stdClass();
                $msg->statusCode = 200;
                $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $lastId);
            }else{
                $msg = new stdClass();
                $msg->statusCode = 400;
                $msg->data = array('type' => 'REQUEST_FAIL','content' => "This username already exists.");
            }

            $response->getBody()->write(json_encode($msg));
         }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/login/admin', function (Request $request, Response $response,$args) {
        require_once __DIR__.'/../mcs/src/login.php';
        $key = '#@SoftinTech!';
        $contentType = $request->getHeaderLine('Content-type');
        $contentHost = $request->getHeaderLine('Host');
        $contents = json_decode(file_get_contents('php://input'), true);
        if(isset($contents['username']) && isset($contents['password']) && $contents['username'] != '' && $contents['password'] != ''){
            $payload = json_encode($contents);
            $login = new login();
            //check user
            $PK = $login->init("login",$contents);
            if($PK!=false){
                $token_payload = [
                    'username' => $contents['username'],
                    'password' =>  $PK[0]['id'],
                    ];
                $jwt = JWT::encode($token_payload, base64_decode(strtr($key, '-_', '+/')), 'HS256');
                $obj = new stdClass();
                $obj->id = $PK[0]['id'];
                $obj->title_id = $PK[0]['title_id'];
                $obj->name = $PK[0]['name'];
                $obj->surname = $PK[0]['surname'];
                $obj->tel = $PK[0]['tel'];
                $obj->address = $PK[0]['address'];
                $obj->district_id = $PK[0]['district_id'];
                $obj->ampher_id = $PK[0]['ampher_id'];
                $obj->province_id = $PK[0]['province_id'];
                $obj->zipcode_id = $PK[0]['zipcode_id'];
                $obj->token = $jwt;
                $msg = new stdClass();
                $msg->statusCode = 200;
                $msg->data = array('type' => 'REQUEST_SUCCESS','content' => json_encode($obj));
                $response->getBody()->write(json_encode($msg));
            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'ชื่อผู้ใช้หรือรหัสไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
        }else{
            $err = new stdClass();
            $err->statusCode = 400;
            $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
            $response->getBody()->write(json_encode($err));
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);

    });

    $app->post('/shop', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
       $contents = json_decode(file_get_contents('php://input'), true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
       if(isset($headers['authorization'])){
            $auth = explode(" ", $headers['authorization']);
            $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $username = $decoded->username;
            $uid= $decoded->password;
            require_once __DIR__.'/../mcs/src/shop.php';
            require_once __DIR__.'/../mcs/Libs/authDetail.php';
             $auth = new authDetail();

            //check user
            if($auth->CheckLogin("member_register",$username,$uid)){
                $shop = new shop();
                $out = '';
                if($contents['id']!="" && isset($contents['id'])){
                    $out = $shop->init("update",$contents);
                }else{
                    $out = $shop->init("create",$contents);
                }
                if($out){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                    $err->data = array('type' => 'REQUEST_FAIL','content' => 'dont\'t table');
                    $response->getBody()->write(json_encode($err));
                }


            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token incorrect');
                $response->getBody()->write(json_encode($err));
            }
       }
       return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);

    });

    $app->post('/employee', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
       $contents = json_decode(file_get_contents('php://input'), true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
       if(isset($headers['authorization'])){
            $auth = explode(" ", $headers['authorization']);
            $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $username = $decoded->username;
            $uid= $decoded->password;
            require_once __DIR__.'/../mcs/src/employee.php';
            require_once __DIR__.'/../mcs/Libs/authDetail.php';
            $auth = new authDetail();
            
            //check user
            if($auth->CheckLogin("member_register",$username,$uid)){
                $employee = new employee();
                $out = '';
                $out = $employee->init("update",$contents);
                
                if($out && $out > 0){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                    switch ($out) {
                        case -1:
                            $err->data = array('type' => 'REQUEST_FAIL','content' => 'This employee already exists');
                            break; 
                        case -2:
                            $err->data = array('type' => 'REQUEST_FAIL','content' => 'This username already exists.');
                            break;                        
                        default:
                            $err->data = array('type' => 'REQUEST_FAIL','content' => 'dont\'t table');
                            break;
                    }
                    
                    $response->getBody()->write(json_encode($err));
                }
            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token incorrect');
                $response->getBody()->write(json_encode($err));
            }
       }
       return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);

    });

    $app->post('/select', function (Request $request, Response $response,$args) {

        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
            $auth = explode(" ", $headers['authorization']);
            $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $username = $decoded->username;
            $uid= $decoded->password;
            require_once __DIR__.'/../mcs/Libs/authDetail.php';
            require_once __DIR__.'/../mcs/src/select.php';
            $auth = new authDetail();
            //check user
            if($auth->CheckLogin("member_register",$username,$uid)){
                 //check user can update
                 if(true){
                    $sel = new select();
                    $out = $sel->init("select",$contents);
                    if($out!=false){
                        $msg = new stdClass();
                        $msg->statusCode = 200;
                        $msg->data = array('type' => 'REQUEST_SUCCESS','content' =>  $out);
                        $response->getBody()->write(json_encode($msg));
                    }else{
                        $err = new stdClass();
                        $err->statusCode = 400;
                        $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                        $response->getBody()->write(json_encode($err));
                    }
                 }
            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/update', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             require_once __DIR__.'/../mcs/src/update.php';
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                 //check user can update
                 $upd = new update();
                 if(true){
                    $out = $upd->init('update',$contents);

                    if($out!=false){
                        $msg = new stdClass();
                        $msg->statusCode = 200;
                        $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                        $response->getBody()->write(json_encode($msg));
                    }else{
                        $msg = new stdClass();
                        $msg->statusCode = 400;
                        $msg->data = array('type' => 'REQUEST_FAIL','content' => $out);
                        $response->getBody()->write(json_encode($msg));
                    }

                 }
             }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
             }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/delete', function (Request $request, Response $response,$args){
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             require_once __DIR__.'/../mcs/src/delete.php';
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                 //check user can delete
                 if(true){
                    $del = new delete();
                    $out = $del->init("delete",$contents);
                    if($out){
                        $msg = new stdClass();
                        $msg->statusCode = 200;
                        $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                        $response->getBody()->write(json_encode($msg));
                    }else{
                        $err = new stdClass();
                        $err->statusCode = 400;
                        $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                        $response->getBody()->write(json_encode($err));
                    }
                 }
             }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
             }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });
    // upload pic
    $app->post('/upload', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             require_once __DIR__.'/../mcs/src/upload.php';
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                 $upload = new upload();
                 $out = $upload->init("upload",$contents);
                 if($out != false){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                 }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                    $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                    $response->getBody()->write(json_encode($err));
                 }
             }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
             }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });
    // read pic
    $app->post('/download', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             require_once __DIR__.'/../mcs/src/download.php';
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                $download = new download();
              
                $out = $download->init("download",$contents);
                 if($out){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                 }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                    $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                    $response->getBody()->write(json_encode($err));
                 }
             }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
             }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    // material
    $app->post('/material', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        
        $contents = json_decode(file_get_contents('php://input'), true);
 
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             require_once __DIR__.'/../mcs/src/material.php';
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                $material = new material();
                
                $out = $material->init("update",$contents);
                
                 if(is_int($out) && intval($out) > 0 || is_array($out)){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                 }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                     switch ($out) {
                         case -1:
                             $err->data = array('type' => 'REQUEST_FAIL','content' => 'This name material already exists.');
                             break;                         
                         default:
                             $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                             break;
                     }                   
                    
                    $response->getBody()->write(json_encode($err));
                 }
             }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
             }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/dashboard', function (Request $request, Response $response) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                if(isset($contents['id']) && is_array($contents['id'])){
                    $file = file_get_contents(__DIR__.'/../mcs/data/dashboard.json');
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => json_decode($file));
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $msg = new stdClass();
                    $msg->statusCode = 400;
                    $msg->data = array('type' => 'REQUEST_FAIL','content' => "ID is array only");
                    $response->getBody()->write(json_encode($msg));
                }
             }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
             }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });
    $app->post('/menu', function (Request $request, Response $response,$args) {
       $key = '#@SoftinTech!';
       $contents = json_decode(file_get_contents('php://input'), true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
       if(isset($headers['authorization'])){
            $auth = explode(" ", $headers['authorization']);
            $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $username = $decoded->username;
            $uid= $decoded->password;
            require_once __DIR__.'/../mcs/Libs/authDetail.php';
             $auth = new authDetail();
            //check user
            if($auth->CheckLogin("member_register",$username,$uid)){
                if(isset($contents['id']) && is_array($contents['id'])){
                    $file = file_get_contents(__DIR__.'/../mcs/data/menu.json');
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => json_decode($file));
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $msg = new stdClass();
                    $msg->statusCode = 400;
                    $msg->data = array('type' => 'REQUEST_FAIL','content' => "ID is array only");
                    $response->getBody()->write(json_encode($msg));
                }

            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
       }
       return $response
       ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/member', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
       $contents = json_decode(file_get_contents('php://input'), true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
       if(isset($headers['authorization'])){
            $auth = explode(" ", $headers['authorization']);
            $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $username = $decoded->username;
            $uid= $decoded->password;
            require_once __DIR__.'/../mcs/src/member.php';
            require_once __DIR__.'/../mcs/Libs/authDetail.php';
             $auth = new authDetail();
            //check user
            if($auth->CheckLogin("member_register",$username,$uid)){
                $member =new member();
                $out = $member->init('register',$contents);
                if(is_int($out)){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                    $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                    $response->getBody()->write(json_encode($err));
                }

            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
       }
       return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);

    });

    
    // ข้อมูลจะไปแสดงที่ select box ร้านค้า
    $app->post('/shoplist', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
            $auth = explode(" ", $headers['authorization']);
            $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $username = $decoded->username;
            $uid= $decoded->password;
            require_once __DIR__.'/../mcs/src/shop.php';
            require_once __DIR__.'/../mcs/Libs/authDetail.php';
            $auth = new authDetail();
             //check user
            if($auth->CheckLogin("member_register",$username,$uid)){
                if(isset($contents['registerId']) && isset($contents['shopId']) && is_array($contents['shopId'])){
                    $shop = new shop();
                    $out = $shop->init("list",$contents);
                    $file = file_get_contents(__DIR__.'/../mcs/data/shop.json');
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' =>$out);
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $msg = new stdClass();
                    $msg->statusCode = 400;
                    $msg->data = array('type' => 'REQUEST_FAIL','content' => "ID ไม่ตรงกับฐานข้อมูลทีมี");
                    $response->getBody()->write(json_encode($msg));
                }
            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/mcs/product', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
            
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                if(isset($contents['productCode'])){
                    $file = file_get_contents(__DIR__.'/../mcs/data/mcs_product.json');
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => json_decode($file));
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $msg = new stdClass();
                    $msg->statusCode = 400;
                    $msg->data = array('type' => 'REQUEST_FAIL','content' => "กรุณากำหนด productCode");
                    $response->getBody()->write(json_encode($msg));
                }

            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/queue', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
         $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             require_once __DIR__.'/../mcs/src/queue.php';
             $auth = new authDetail();
             //check user
             if($auth->CheckLogin("member_register",$username,$uid)){
                $queue = new queue();
                $out = $queue->init("get",$contents);
                if($out!=false){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                    $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                    $response->getBody()->write(json_encode($err));
                }
               

            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    $app->post('/product/sale', function (Request $request, Response $response,$args) {
        $key = '#@SoftinTech!';
        $contents = json_decode(file_get_contents('php://input'), true);
        $headers = array_change_key_case(apache_request_headers(),CASE_LOWER);
        if(isset($headers['authorization'])){
             $auth = explode(" ", $headers['authorization']);
             $decoded = JWT::decode($auth[1], base64_decode(strtr($key, '-_', '+/')), ['HS256']);
             $username = $decoded->username;
             $uid= $decoded->password;
             require_once __DIR__.'/../mcs/Libs/authDetail.php';
             require_once __DIR__.'/../mcs/src/product.php';
             $auth = new authDetail();
             //check user
 
             if($auth->CheckLogin("member_register",$username,$uid)){
                $product = new product();
                $out = '';
                $out = $product->init("update",$contents);
                if($out!=false){
                    $msg = new stdClass();
                    $msg->statusCode = 200;
                    $msg->data = array('type' => 'REQUEST_SUCCESS','content' => $out);
                    $response->getBody()->write(json_encode($msg));
                }else{
                    $err = new stdClass();
                    $err->statusCode = 400;
                    $err->data = array('type' => 'REQUEST_FAIL','content' => $out);
                    $response->getBody()->write(json_encode($err));
                }
               

            }else{
                $err = new stdClass();
                $err->statusCode = 400;
                $err->data = array('type' => 'REQUEST_FAIL','content' => 'Token ไม่ถูกต้อง');
                $response->getBody()->write(json_encode($err));
            }
        }
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    });

    ////////////////////////////////////////////////////END///////////////////////////////////////////


};
