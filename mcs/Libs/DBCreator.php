<?php

use function PHPUnit\Framework\isNull;

class DBCreator
	{
		private $instancdb;


		function __construct($db)
		{
			$this->instancdb = $db;
		}
		function execute($objs){
			$db = $this->instancdb;
			$obj = json_decode($objs);
			$tb = $obj->name;

			//return json_encode($this->insert($obj));
			if($db->CheckTable("SELECT * FROM $tb")){
				if($db->CheckRow("SELECT * FROM $tb ".$obj->Where) >0){
					//old group
					$sqlGname = "SELECT * FROM $tb ".$obj->Where;
					$res = json_decode($db->SelectData($sqlGname));
					$arrOut = [];
					$arrOut['data']=json_encode($obj);
					if($tb=="chat_group_msg"){
						$db->Iquery($this->insert($obj));
						$arrOut['id']=$db->GetlastID();
					}else{
						$arrOut['id']=$res[0]->id;
					}
					return json_encode($arrOut);
				}else{
					//new group
					if($db->Iquery($this->insert($obj))){
						$sqlGname = "SELECT id FROM $tb ".$obj->Where;
						$res = json_decode($db->SelectData($sqlGname));
						$arrOut = [];
						$arrOut['data']=json_encode($obj);
						$arrOut['id']=$res[0]->id;
						return json_encode($arrOut);
					}else{
						return false;
					}
				}
			}else{
				if($db->Iquery($this->createTB($obj))){
					$db->Iquery($this->insert($obj));
					$sqlGname = "SELECT id FROM $tb ".$obj->Where;
					$res = json_decode($db->SelectData($sqlGname));
					$arrOut = [];
					$arrOut['data']=json_encode($obj);
					$arrOut['id']=$res[0]->id;
					return json_encode($arrOut);
				}else{
					return false;
				}
			}
		}

		function createTB($table,$obj){

			$tb = $table;

			$sql = "CREATE TABLE $tb (";
			$sql .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";

			foreach ($obj->sql as $key => $value) {
				$sql .= $key .' '.$value->type.' ,';
			}
			$sql .= "reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
			return $sql;
		}
		function createTBNew($table,$obj){
			$tb = $table;
			$sql = "CREATE TABLE $tb (";
			$sql .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";

			foreach ($obj->sql as $key => $value) {
				$sql .= $key .' '.$value->type.' '.($value->type==='TEXT' || $value->type === 'text' ?'NULL':'').' ,';
			}
			$sql .= "reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
			return $sql;
		}
		public function dropDB()
		{

		}
		public function dropTB($table)
		{
			$db = $this->instancdb;
			$db->query("DROP TABLE ".$table);
		}
		public function strInsert($table,$obj)
		{
			$tb = $table;
			$cntCol = count((array)json_decode(json_encode($obj->sql)));
			$nameCol="";
			$valCol="";
			$sql = "INSERT INTO $tb (";
			$i=0;
			//$json = count((array)json_decode(json_encode($obj->sql)));

			foreach ($obj->sql as $key => $value) {
				if($i == $cntCol-1){
                    $nameCol .= $key;

					if(isset($value->val)){
						$valCol .= '\''.$value->val.'\'';
					}else{
						$valCol .= '\'\'';
                    }

				}else{
					$nameCol .= $key.',';
					if(isset($value->val)){
						$valCol .= '\''.$value->val.'\',';
					}else{
						$valCol .= '\'\',';
					}
				}
				$i++;
            }
            $date = date('Y-m-d H:i:s');
			$sql .= $nameCol.') VALUES ('.$valCol.')';
			return $sql;
		}
		public function strUpdate($table,$id,$obj)
		{
			$tb = $table;
			$cntCol = count((array)json_decode(json_encode($obj->sql)));
			$nameCol="";
			$valCol="";
			$sql = "UPDATE $tb SET ";
			$i=0;
			//$json = count((array)json_decode(json_encode($obj->sql)));

			foreach ($obj->sql as $key => $value) {
                if(isset($value->val)){
                    if($i == $cntCol-1){
                        if($value->val != ''){
                            $nameCol .= $key.'=\''.$value->val.'\'';
                        }else{
                            //$nameCol .= $key.'=\'\'';
                        }

                    }else{
                        if($value->val != ''){
                            $nameCol .= $key.'=\''.$value->val.'\',';
                        }else{
                            //$nameCol .= $key.'=\'\',';
                        }

                    }
                }
				$i++;
			}
			if(strpos(strtoupper($id), "WHERE")>0){
				$sql .= $nameCol.' '.$id;
			}else{
				$sql .= $nameCol.' WHERE id = \''.$id.'\'';
			}
			return $sql;
		}
		public function strUpdateWhere($table,$wheres,$obj)
		{
			$tb = $table;
			$cntCol = count((array)json_decode(json_encode($obj->sql)));
			$nameCol="";
			$valCol="";
			$sql = "UPDATE $tb SET ";
			$i=0;
			//$json = count((array)json_decode(json_encode($obj->sql)));

			foreach ($obj->sql as $key => $value) {
                if(isset($value->val)){
                    if($i == $cntCol-1){
                        if($value->val != ''){
                            $nameCol .= $key.'=\''.$value->val.'\'';
                        }else{
                            //$nameCol .= $key.'=\'\'';
                        }

                    }else{
                        if($value->val != ''){
                            $nameCol .= $key.'=\''.$value->val.'\',';
                        }else{
                            //$nameCol .= $key.'=\'\',';
                        }

                    }
                }
				$i++;
			}
			if(strpos(strtoupper($wheres), "WHERE")>0){
				$sql .= $nameCol.' '.$wheres;
			}else{
				$sql .= $nameCol.' WHERE id = \''.$id.'\'';
			}

			return $sql;
		}

	}

	class NewDBCreator
	{
		private $instancdb;


		function __construct($db)
		{
			$this->instancdb = $db;
		}
		function execute($objs){
			$db = $this->instancdb;
			$obj = json_decode($objs);
			$tb = $obj->name;

			//return json_encode($this->insert($obj));
			if($db->CheckTable("SELECT * FROM $tb")){
				if($db->CheckRow("SELECT * FROM $tb ".$obj->Where) >0){
					//old group
					$sqlGname = "SELECT * FROM $tb ".$obj->Where;
					$res = json_decode($db->SelectData($sqlGname));
					$arrOut = [];
					$arrOut['data']=json_encode($obj);
					if($tb=="chat_group_msg"){
						$db->Iquery($this->insert($obj));
						$arrOut['id']=$db->GetlastID();
					}else{
						$arrOut['id']=$res[0]->id;
					}
					return json_encode($arrOut);
				}else{
					//new group
					if($db->Iquery($this->insert($obj))){
						$sqlGname = "SELECT id FROM $tb ".$obj->Where;
						$res = json_decode($db->SelectData($sqlGname));
						$arrOut = [];
						$arrOut['data']=json_encode($obj);
						$arrOut['id']=$res[0]->id;
						return json_encode($arrOut);
					}else{
						return false;
					}
				}
			}else{
				if($db->Iquery($this->createTB($obj))){
					$db->Iquery($this->insert($obj));
					$sqlGname = "SELECT id FROM $tb ".$obj->Where;
					$res = json_decode($db->SelectData($sqlGname));
					$arrOut = [];
					$arrOut['data']=json_encode($obj);
					$arrOut['id']=$res[0]->id;
					return json_encode($arrOut);
				}else{
					return false;
				}
			}
		}

		function createTB($table,$obj){

			$tb = $table;

			$sql = "CREATE TABLE $tb (";
			$sql .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";

			foreach ($obj->sql as $key => $value) {
				$sql .= $key .' '.$value->type.' ,';
			}
			$sql .= "reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
			return $sql;
		}
		function createTBNew($table,$obj){
			$tb = $table;
			$sql = "CREATE TABLE $tb (";
			$sql .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";

			foreach ($obj->sql as $key => $value) {
				$sql .= $key .' '.$value->type.' '.($value->type==='TEXT' || $value->type === 'text' ?'NULL':'').' ,';
			}
			$sql .= "reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
			return $sql;
		}
		public function dropDB()
		{

		}
		public function dropTB($table)
		{
			$db = $this->instancdb;
			$db->query("DROP TABLE ".$table);
		}
		public function strInsert($table,$obj)
		{
			$tb = $table;
			$cntCol = count((array)json_decode(json_encode($obj['sql'])));
			$nameCol="";
			$valCol="";
			$sql = "INSERT INTO $tb (";
			$i=0;
			//$json = count((array)json_decode(json_encode($obj->sql)));

			foreach ($obj['sql'] as $key => $value) {
				if($i == $cntCol-1){
                    $nameCol .= $key;

					if(isset($value)){
						$valCol .= '\''.$value.'\'';
					}else{
						$valCol .= '\'\'';
                    }

				}else{
					$nameCol .= $key.',';
					if(isset($value)){
						$valCol .= '\''.$value.'\',';
					}else{
						$valCol .= '\'\',';
					}
				}
				$i++;
            }
			$sql .= $nameCol.') VALUES ('.$valCol.')';
			return $sql;
		}
		public function strUpdate($table,$id,$obj)
		{
			$tb = $table;
			$cntCol = count((array)json_decode(json_encode($obj['sql'])));
			$nameCol="";
			$valCol="";
			$sql = "UPDATE $tb SET ";
			$i=0;
			//$json = count((array)json_decode(json_encode($obj->sql)));

			foreach ($obj['sql'] as $key => $value) {
                if(isset($value)){
                    if($i == $cntCol-1){
                        $nameCol .= $key.'=\''.$value.'\'';
                    }else{
                        $nameCol .= $key.'=\''.$value.'\',';
                    }
                }
				$i++;
			}
			if(strpos(strtoupper($id), "WHERE")>0){
				$sql .= $nameCol.' '.$id;
			}else{
				$sql .= $nameCol.' WHERE id = \''.$id.'\'';
			}
			return $sql;
		}
		public function strUpdateWhere($table,$wid,$id,$obj)
		{
			$tb = $table;
			$cntCol = count((array)json_decode(json_encode($obj['sql'])));
			$nameCol="";
			$valCol="";
			$sql = "UPDATE $tb SET ";
			$i=0;
			//$json = count((array)json_decode(json_encode($obj->sql)));

			foreach ($obj['sql'] as $key => $value) {
                if(isset($value)){
                    if($i == $cntCol-1){
                        $nameCol .= $key.'=\''.$value.'\'';
                    }else{
                        $nameCol .= $key.'=\''.$value.'\',';
                    }
                }
				$i++;
			}
			if(strpos(strtoupper($id), "WHERE")>0){
				$sql .= $nameCol.' '.$id;
			}else{
				$sql .= $nameCol.' WHERE '.$wid.' = \''.$id.'\'';
			}
			return $sql;
		}
		public function strSelect($table,$obj)
		{
			$nameCol="";
			$sql = "SELECT * FROM $table ";

			if(is_array($obj['id'])){
				if($obj['id'][0]=="" || isNull($obj['id'][0])){

				}else{
					$c = count($obj['id']);

					$m = $obj['method'];
					$sql .= " WHERE ";

					for ($i=0; $i < $c; $i++) {

						$id = $obj['id'][$i];
						if(($c-1) == $i){
							$sql .= " id $m $id";
						}else{
							if($c>1){
								$sql .= " id $m $id OR";
							}else{
								$sql .= " id $m $id";
							}
						}
					}
				}
			}else{
				if($obj['id']==""){

				}else{
					$id = $obj['id'];
					$m = $obj['method'];
					$sql .= " WHERE $wid $m '$id'";
				}
			}

			return $sql;
		}
		public function strDelete($table,$obj)
		{

			$sql = "DELETE FROM $table ";

			if(is_array($obj['id'])){
				if($obj['id'][0]==""){

				}else{
					$c = count($obj['id']);

					$m = $obj['method'];
					$sql .= " WHERE ";

					for ($i=0; $i < $c; $i++) {
						$id = $obj['id'][$i];
						if(($c-1) == $i){
							$sql .= " id $m $id";
						}else{
							if($c>1){
								$sql .= " id $m $id OR";
							}else{
								$sql .= " id $m $id";
							}
						}
					}
				}
			}else{
				if($obj['id']==""){

				}else{
					$id = $obj['id'];
					$m = $obj['method'];
					$sql .= " WHERE id $m '$id'";
				}
			}

			return $sql;
		}

	}
?>