<?php

// ini_set('display_errors', 1);
// error_reporting(~0);
date_default_timezone_set("Asia/Bangkok");

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
include_once 'config.php';

class PDOClass extends config
{

    public $conn;
    private $LastID;
    public $role = '';
    private $userID=false;

	public function ConnectNoDatabase(){
    	try {
            $this->conn = new PDO("mysql:host=".$this->servername.";charset=utf8;", $this->username, $this->password);
           // $this->conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		    // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    //echo "Connected successfully";
		    	return true;
		    }
		catch(PDOException $e)
		    {
		    	//echo "Connection failed: " . $e->getMessage();
		    	return false;
		    }
    }
    public function Connect(){
    	try {
            $this->conn = new PDO("mysql:host=".$this->servername.";dbname=".$this->database.";charset=utf8;", $this->username, $this->password);
           // $this->conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		    // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    //echo "Connected successfully";
		    	return true;
		    }
		catch(PDOException $e)
		    {
		    	//echo "Connection failed: " . $e->getMessage();
		    	return false;
		    }
    }
    public function Disconnect(){
    	$this->conn = null;
    }
	public function showDatabase($dbCheck=''){
		//Execute a "SHOW DATABASES" SQL query.
		$stmt = $this->conn->query('SHOW DATABASES');

		//Fetch the columns from the returned PDOStatement
		$databases = $stmt->fetchAll(PDO::FETCH_COLUMN);

		//Loop through the database list and print it out.
		foreach($databases as $database){
			//$database will contain the database name
			//in a string format
			if($dbCheck==$database){
				return $database;
			}

		}
		return false;
	}
    public function CreateDB($DBbase){
        try {
			    $this->conn = new PDO("mysql:host=".$this->servername, $this->username, $this->password);
			    // set the PDO error mode to exception
			    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql = "CREATE DATABASE IF NOT EXISTS $DBbase";
			    // use exec() because no results are returned
			    return $this->conn->exec($sql);
		    	//return true;
		    }
		catch(PDOException $e)
		    {
		    	return false;
		    }
    }
    public function createTB($obj){
		$tb = $obj->name;

		$cntCol = count($obj->column);

		$sql = "CREATE TABLE $tb (";
		$sql .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";

		for($i=0;$i<$cntCol;$i++){
			foreach ($obj->column as $key => $value) {
				$sql .= $key .' '.$value->type.',';
			}
		}
		$sql .= " reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
		$this->conn->exec($sql);
	}

	public function Iquery($sql){
		try {
				$this->conn->exec($sql);
				return true;
		    }
		catch(PDOException $e)
		    {
		    	return false;
		    }
	}
	public function IqueryChkUpdate($sql){
		try {
				return $this->conn->exec($sql);
		    }
		catch(PDOException $e)
		    {
		    	return false;
		    }
	}
	public function Insert($sql){
		try {
				return $this->conn->exec($sql);
				
		    }
		catch(PDOException $e)
		    {
		    	return false;
		    }
	}
	public function InsertMultiple($sql){
		try {
				$this->conn->beginTransaction();
			    // our SQL statements
			    $this->conn->exec("INSERT INTO MyGuests (firstname, lastname, email)
			    VALUES ('John', 'Doe', 'john@example.com')");
			    $this->conn->exec("INSERT INTO MyGuests (firstname, lastname, email)
			    VALUES ('Mary', 'Moe', 'mary@example.com')");
			    $this->conn->exec("INSERT INTO MyGuests (firstname, lastname, email)
			    VALUES ('Julie', 'Dooley', 'julie@example.com')");

			    // commit the transaction
			    $this->conn->commit();
			    $this->conn->exec($sql);
		    	echo "New record created successfully";
		    }
		catch(PDOException $e)
		    {
		    	echo $sql . "<br>" . $e->getMessage();
		    }
	}
    public function GetlastID(){
    	//return $this->LastID;
    	return $this->conn->lastInsertId();
    }
    public function Prepared($firstname,$lastname,$email){
    	try {
    			// prepare sql and bind parameters
			    $stmt = $this->conn->prepare("INSERT INTO MyGuests (firstname, lastname, email)
			    VALUES (:firstname, :lastname, :email)");
			    $stmt->bindParam(':firstname', $firstname);
			    $stmt->bindParam(':lastname', $lastname);
			    $stmt->bindParam(':email', $email);

			    // insert a row
			    $firstname = "John";
			    $lastname = "Doe";
			    $email = "john@example.com";
			    $stmt->execute();

			    // insert another row
			    $firstname = "Mary";
			    $lastname = "Moe";
			    $email = "mary@example.com";
			    $stmt->execute();

			    // insert another row
			    $firstname = "Julie";
			    $lastname = "Dooley";
			    $email = "julie@example.com";
			    $stmt->execute();

			    echo "New records created successfully";
		    }
		catch(PDOException $e)
		    {
		    	echo "Prepared failed: " . $e->getMessage();
		    }
    }
    public function SelectData($sql){
    	try {
    			$stmt = $this->conn->prepare($sql);
			    $result  = $stmt->execute();
			    if($stmt->rowCount()>0){
			    	$stmt->setFetchMode(PDO::FETCH_ASSOC);
			    	$this->LastID = $this->conn->lastInsertId();
			    	return json_encode($stmt->fetchAll());
			    }else{
			    	return false;
			    }
			    // set the resulting array to associative

		    }
		catch(PDOException $e)
		    {
		    	return -1;
		    }
	}
	public function Select($sql){
    	try {
    			$stmt = $this->conn->prepare($sql);
			    $result  = $stmt->execute();
			    if($stmt->rowCount()>0){
			    	$stmt->setFetchMode(PDO::FETCH_ASSOC);
			    	$this->LastID = $this->conn->lastInsertId();
			    	return $stmt->fetchAll();
			    }else{
			    	return false;
			    }
			    // set the resulting array to associative

		    }
		catch(PDOException $e)
		    {
		    	return -1;
		    }
    }
    public function CheckTable($sql){
    	try {
    			$stmt = $this->conn->prepare($sql);
			    $result  = $stmt->execute();
			    if($result){
			    	return true;
			    }else{
			    	return false;
			    }

		    }
		catch(PDOException $e)
		    {
		    	return false;
		    }
    }
	public function execute($sql){
    	try {
    			$stmt = $this->conn->prepare($sql);
			    $result  = $stmt->execute();
			    if($result){
			    	return true;
			    }else{
			    	return false;
			    }

		    }
		catch(PDOException $e)
		    {
		    	return false;
		    }
    }
    public function CheckRow($sql){
    	try {
    		$stmt = $this->conn->prepare($sql);
	    	$stmt->execute();
	    	return $stmt->rowCount();
    	}
    	catch (PDOException $e) {
    		return false;
    	}
    }
    public function login($username,$password,$table){
		try {
			$query = "
				SELECT * FROM $table
		  		WHERE username = :username
			";

			$statement = $this->conn->prepare($query);
			$statement->execute(
				array(
					':username' => $username
				)
			);

			$count = $statement->rowCount();

			if($count > 0)
			{
				$result = $statement->fetchAll();

				foreach($result as $row)
				{
					if(password_verify($password, $row["password"]))
					{
						$this->userID =$row['id'];
						$sub_query = "
						INSERT INTO login_details
						(user_id)
						VALUES ('".$row['id']."')
						";
						$statement = $this->conn->prepare($sub_query);
						$statement->execute();
						return $row['id'];
					}
					else
					{
						return false;
					}
				}
			}
			else
			{
				return false;
			}
		} catch (\Throwable $th) {
			return false;
		}

    }
	public function logins($username,$password,$remember='login'){
    	$query = "
				SELECT * FROM $remember
		  		WHERE username = :username
			";

		$statement = $this->conn->prepare($query);
		$statement->execute(
			array(
				':username' => $username
			)
		);

		$count = $statement->rowCount();

		if($count > 0)
		{
			$result = $statement->fetchAll();

			foreach($result as $row)
			{
				if(password_verify($password, $row["userpassword"]))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
    }
    public function getUserId(){
    	return $this->userID;
    }
    public function registorMember($json,$table){
        $shop_id = $json['shop_id'];
        $title_id = $json['title_id'];
        $name = $json['name'];
        $surname = $json['surname'];
        $tel = $json['tel'];
        $position = $json['position'];
        $jd = $json['jd'];
        $username = $json['username'];
		$password = $json['password'];
		$confirm_password = $json['password'];
        $address = $json['address'];
        $picture = $json['picture'];
        $district_id = $json['district_id'];
        $ampher_id = $json['ampher_id'];
		$province_id = $json['province_id'];
        $zipcode_id = $json['province_id'];
        $active = $json['active'];

		$message="";
		$check_query = "
		SELECT * FROM $table
		WHERE username = :username
		";
		$statement = $this->conn->prepare($check_query);
		$check_data = array(
			':username'		=>	$username
		);



		if($statement->execute($check_data))
		{
			if($statement->rowCount() > 0)
			{
				return "ชื่อผู้ใช้นี้ มีผู้ใช้อยู่แล้ว";// '<h3>Username already taken</h3></br><h6>ชื่อผู้ใช้นี้มีผู้ใช้อยู่แล้ว</h6>';
			}
			else
			{

				if(empty($username))
				{
					return "ต้องระบุชื่อผู้ใช้";
				}
				if(empty($password))
				{
					return "กรุณากรอกรหัสผ่าน";
				}
				else
				{
					if($password != $confirm_password)
					{
						return "รหัสผ่านไม่ตรงกัน";
					}
				}
				if($message == '')
				{
					$data = array(
                        ':shop_id'		=>	$shop_id,
                        ':title_id'     =>  $title_id,
                        ':name'      	=>  $name,
                        ':surname'      =>  $surname,
                        ':tel'      	=>  $tel,
                        ':position'   	=>  $position,
						':jd'			=>	$jd,
						':username'		=>	$username,
						':password'		=>	password_hash($password, PASSWORD_DEFAULT),
						':address'		=>	$address,
						':picture'		=>	$picture,
						':district_id'	=>	$district_id,
						':ampher_id'	=>	$ampher_id,
						':province_id'	=>	$province_id,
						':zipcode_id'	=>	$zipcode_id,
						':active'		=>	$active
					);

					$query = "
					INSERT INTO $table
					(shop_id,
					title_id,
					name,
					surname,
					tel,
					position,
					jd,
					username,
					password,
					address,
					picture,
					district_id,
					ampher_id,
					province_id,
					zipcode_id,
					active)
                    VALUES
                    (:shop_id,
                     :title_id,
                     :name,
                     :surname,
                     :tel,
                     :position,
                     :jd,
                     :username,
                     :password,
                     :address,
                     :picture,
                     :district_id,
                     :ampher_id,
                     :province_id,
                     :zipcode_id,
                     :active)";

					$statement = $this->conn->prepare($query);

					if($statement->execute($data))
					{
						return intval($this->conn->lastInsertId());
					}else{
						return 'ผิดพลาด';
					}
				}else{
					return 'ผิดพลาด';
				}
			}
		}
    }
    public function registor($json,$table){

    	$username = $json['username'];
		$password = $json['password'];
		$confirm_password = $json['password'];
        $shop_id = $json['shop_id'];
        $title_id = $json['title_id'];
        $name = $json['name'];
        $surname = $json['surname'];
        $tel = $json['tel'];
        $jd = $json['jd'];
        $address = $json['address'];
        $position = $json['position'];
        $picture = $json['picture'];
        $district_id = $json['district_id'];
        $ampher_id = $json['ampher_id'];
        $province_id = $json['province_id'];
        $zipcode_id = $json['zipcode_id'];
        $active = $json['active'];

		$message="";
		$check_query = "
		SELECT * FROM $table
		WHERE username = :username
		";
		$statement = $this->conn->prepare($check_query);
		$check_data = array(
			':username'		=>	$username
		);
		if($statement->execute($check_data))
		{
			if($statement->rowCount() > 0)
			{
				return "ชื่อผู้ใช้นี้ มีผู้ใช้อยู่แล้ว";// '<h3>Username already taken</h3></br><h6>ชื่อผู้ใช้นี้มีผู้ใช้อยู่แล้ว</h6>';
			}
			else
			{
				if(empty($username))
				{
					return "ต้องระบุชื่อผู้ใช้";
				}
				if(empty($password))
				{
					return "กรุณากรอกรหัสผ่าน";
				}
				else
				{
					if($password != $confirm_password)
					{
						return "รหัสผ่านไม่ตรงกัน";
					}
				}
				if($message == '')
				{
					$data = array(
						':username'		=>	$username,
                        ':password'		=>	password_hash($password, PASSWORD_DEFAULT),
                        ':shop_id'         =>  $shop_id,
                        ':title_id'         =>  $title_id,
                        ':surname'      =>  $surname,
                        ':name'         =>  $name,
                        ':surname'   =>  $surname,
						':tel'		=>	$tel,
						':jd'		=>	$jd,
						':address'		=>	$address,
						':position'		=>	$position,
						':picture'		=>	$picture,
						':district_id'		=>	$district_id,
						':ampher_id'		=>	$ampher_id,
						':province_id'		=>	$province_id,
						':zipcode_id'		=>	$zipcode_id,
						':active'		=>	$active
					);

					$query = "
					INSERT INTO $table
                    (username,
					password,
					shop_id,
					title_id,
					name,
					surname,
					tel,
					jd,
					address,
					position,
					picture,
					district_id,
					ampher_id,
					province_id,
					zipcode_id,
					active)
                    VALUES
                    (:username,
                     :password,
                     :shop_id,
                     :title_id,
                     :name,
                     :surname,
                     :tel,
					 :jd,
                     :address,
                     :position,
                     :picture,
                     :district_id,
                     :ampher_id,
                     :province_id,
                     :zipcode_id,
                     :active)
					";
					$statement = $this->conn->prepare($query);
					if($statement->execute($data))
					{
						return intval($this->conn->lastInsertId());
					}else{
						return 'ผิดพลาด';
					}
				}else{
					return 'ผิดพลาด';
				}
			}
		}
    }

    public function UpdateRegistor($usernames,$passwords,$confirm_passwords,$emails='',$name='',$surname='',$permission='',$id_application=''){
    	$username = trim($usernames);
		$password = trim($passwords);
		$confirm_password = trim($confirm_passwords);
        $email = trim($emails);

		$message="";
		$check_query = "
		SELECT * FROM login
		WHERE username = :username
		";
		$statement = $this->conn->prepare($check_query);
		$check_data = array(
			':username'		=>	$username
		);
		if($statement->execute($check_data))
		{
			if($statement->rowCount() > 0)
			{
                $message .= '<p><label>Username already taken</label></p>';
                if($permission==''){
                    $data = array(
                        ':username'		=>	$username,
                        ':password'		=>	password_hash($password, PASSWORD_DEFAULT),
                        ':name'         =>  $name,
                        ':surname'      =>  $surname,
                        ':id_application'   =>  $id_application,
                        ':email'		=>	$email
                    );

                    $query = "
                    UPDATE login
                    SET username = :username,
                    password = :password,
                    name = :name,
                    surname = :surname,
                    id_application = :id_application,
                    email = :email
                    WHERE  id_application = :id_application
                    ";
                }else{
                    $data = array(
                        ':username'		=>	$username,
                        ':password'		=>	password_hash($password, PASSWORD_DEFAULT),
                        ':name'         =>  $name,
                        ':surname'      =>  $surname,
                        ':group_premis'         =>  $permission,
                        ':id_application'   =>  $id_application,
                        ':email'		=>	$email
                    );

                    $query = "
                    UPDATE login
                    SET username = :username,
                    password = :password,
                    name = :name,
                    surname = :surname,
                    group_premis = :group_premis,
                    id_application = :id_application,
                    email = :email
                    WHERE  id_application = :id_application
                    ";
                }

                $statement = $this->conn->prepare($query);
                if($statement->execute($data))
                {
                    $message = "<label>Update Completed</label>";
                    return true;
                }else{
                    $message = "<label>Update Fail</label>";
                    return 'Registration Fail';
                }
			}
			else
			{
				if(empty($username))
				{
                    $message .= '<p><label>Username is required</label></p>';
                    $obj = [];
                    $obj['title'] = '<h3>Username is required</h3></br><h6>ต้องระบุชื่อผู้ใช้</h6>';
                    $obj['state'] = false;
					return json_encode($obj);
				}
				if(empty($password))
				{
                    $message .= '<p><label>Password is required</label></p>';
                    $obj = [];
                    $obj['title'] = '<h3>Password is required</h3></br><h6>กรุณาใส่รหัสผ่าน</h6>';
                    $obj['state'] = false;
					return json_encode($obj);
				}
				else
				{
					if($password != $confirm_password)
					{
                        $message .= '<p><label>Password not match</label></p>';
                        $obj = [];
                        $obj['title'] = '<h3>Password not match</h3></br><h6>รหัสผ่านไม่ตรงกัน</h6>';
                        $obj['state'] = false;
						return json_encode($obj);
					}
				}
				if($message == '')
				{
					$data = array(
						':username'		=>	$username,
                        ':password'		=>	password_hash($password, PASSWORD_DEFAULT),
                        ':name'         =>  $name,
                        ':surname'      =>  $surname,
                        ':group_premis'         =>  $permission,
                        ':id_application'   =>  $id_application,
						':email'		=>	$email
					);

					$query = "
					INSERT INTO login
                    (username,
                     password,
                     name,
                     surname,
                     group_premis,
                     id_application,
                     email)
                    VALUES
                    (:username,
                     :password,
                     :name,
                     :surname,
                     :group_premis,
                     :id_application,
                     :email)
					";
					$statement = $this->conn->prepare($query);
					if($statement->execute($data))
					{
						$message = "<label>Registration Completed</label>";
						return true;
					}else{
						return 'Registration Fail';
					}
				}else{
					return 'Registration Fail';
				}
			}
		}
    }



}
?>