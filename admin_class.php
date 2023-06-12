<?php
session_start();
ini_set('display_errors', 1);
Class Action {
private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';

    $this->db = $conn;
	}
	function __destruct() {
		$this->db->close();
		ob_end_flush();
	}


  function hasInternetConnection() {
		$url = 'https://www.google.com';
		$timeout = 5; // Timeout in seconds
		$context = stream_context_create(['http' => [
				'timeout' => $timeout,
		]]);
		$result = @file_get_contents($url, false, $context);
		if ($result === false) {
				// Error occurred, indicating no internet connection
				return false;
		}
		// Request succeeded, internet connection is available
		return true;
	}



  function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".htmlspecialchars($username)."' and password = '".md5(htmlspecialchars($password))."' ");
		if($qry->num_rows > 0){
				
		 $status = $qry->fetch_array();
		 if($status['login_status'] == 'yes') {


       if($this->hasInternetConnection()) {
				require __DIR__ . '/vendor/autoload.php';
				$options = array(
					'cluster' => 'ap1',
					'useTLS' => true
				);
				$pusher = new Pusher\Pusher(
					'1246ce718fc12039a92f',
					'e20bf5f4b84655baffce',
					'1609054',
					$options
				);
			
				$data['message'] = $status['id'];
				$pusher->trigger('my-channel', 'my-event', $data);
				}

			return 9;
		 }
		
			foreach ($status as $key => $value) {
					$_SESSION['last_activity'] = time();
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
					if($key == 'id') {
							$data = " login_status = 'yes' ";
						$this->db->query("UPDATE users set ".$data." where id = ".$value);
					}
			}
				return 1;
		}else{
			return 3;
		}
	}

	function login2(){

		extract($_POST);
		$qry = $this->db->query("SELECT * FROM complainants where email = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}


  function remove_session() {
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".htmlspecialchars($username)."' and password = '".md5(htmlspecialchars($password))."' ");
			if($qry->num_rows > 0){
				$idd = $qry->fetch_assoc();
				$data = " login_status = 'no' ";
				$save = $this->db->query("UPDATE users set $data where id = {$idd['id']}");
				
			if($save){
				return 5;
			}
	}
  }
  //function remove_session() {
		//$data = " login_status = 'no' ";
		//$save = $this->db->query("UPDATE users set $data where id = 1");
		//if($save){
		//	return 1;
		//}
	//}

	function logout(){
		if(isset($_SESSION['login_id'])) {
			$data = " login_status = 'no' ";
			$this->db->query("UPDATE users set ".$data." where id = ".$_SESSION['login_id']);
		}
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			if($_SESSION[$key] == 'login_id') {
				echo $value;
				$data = " login_status = 'no' ";
				$this->db->query("UPDATE users set ".$data." where id = ".$value);
			}
			unset($_SESSION[$key]);
		}
		// return;
		header("location:login.php");
	}
	
	function time_out($hehe)
	{
		// $expire_time = 1*10; 
		if( time() - $_SESSION['last_activity'] > 10 ) {
			if(isset($_SESSION['login_id'])) {
				$data = " login_status = 'no' ";
				$this->db->query("UPDATE users set ".$data." where id = ".$_SESSION['login_id']);
			}
			session_destroy();
			header("location:login.php");
			echo '1';
		}
		else {
      	if(isset($_SESSION['login_id'])) {
				$data = " login_status = 'no' ";
				$this->db->query("UPDATE users set ".$data." where id = ".$_SESSION['login_id']);
			}
			session_destroy();
			header("location:login.php");
    }
		if($hehe) {
			$_SESSION['last_activity'] = time(); // you have to add this line when logged in also;
		}
	}
	
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		// return json_encode($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		if(empty($password))
		$data .= ", password = '' ";
    $data .= ", login_status = 'no' ";
		if(isset($type)) {
			$data .= ", type = '$type' ";
		}
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", address = '$address' ";
		$data .= ", contact = '$contact' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * from complainants where email ='$email' ".(!empty($id) ? " and id != '$id' " : ''))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		if(empty($id))
			$save = $this->db->query("INSERT INTO complainants set $data");
		else
			$save = $this->db->query("UPDATE complainants set $data where id=$id ");
		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
				$qry = $this->db->query("SELECT * FROM complainants where id = $id ");
				if($qry->num_rows > 0){
					foreach ($qry->fetch_array() as $key => $value) {
						if($key != 'password' && !is_numeric($key))
							$_SESSION['login_'.$key] = $value;
					}
						return 1;
				}else{
					return 3;
				}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}
	function save_supplier(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM suppliers where name ='$name' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO suppliers set $data");
		}else{
			$save = $this->db->query("UPDATE suppliers set $data where id = $id");
		}

		if($save)
			return 1;
	}
	function delete_supplier(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM suppliers where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_product(){
		extract($_POST);
		// return $_POST['category'];
		
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','item_code')) && !is_numeric($k)){
				if($k == 'price'){
					$v= str_replace(',', '', $v);
				}
			
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM items where item_code ='$item_code' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($item_code)){
			$i = 0;
			while($i == 0){
				$item_code  = time();
				// $values = array(4065 => 'Male', 4067 => 'Female');
				// $topicName = $values[$category];
				$item_code = sprintf("%d",$item_code);
				$chk = $this->db->query("SELECT * FROM items where item_code ='$item_code' ");
				if($chk->num_rows <= 0){
					$i = 1;
				}
			}
			$data .= ", item_code = '$item_code' ";
		}
		if(empty($id)){
			// echo "INSERT INTO items set $data";
			$save = $this->db->query("INSERT INTO items set $data");
		}else{
			$save = $this->db->query("UPDATE items set $data where id = $id");
		}

		if($save)
			return 1;
	}
	function delete_product(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM items where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_receiving(){
		extract($_POST);
		// echo json_encode($_POST);
		// return;
		$data = " supplier_id = $supplier_id ";
		$data .= ", total_cost = '$total_amount' ";
		// $data .= ", description = '' ";
		// $data .= ", inventory_ids = '$inv_id'";
		if(empty($id)){
			$maki = $this->db->query("INSERT INTO receiving set $data");
			$id = $this->db->insert_id;
		}else{
			$dodong = $this->db->query("UPDATE receiving set $data where id = '$id' ");
			// echo $data; 
			// return;
		}
		
		if(isset($maki)){
			// echo "maki";
			// return;
			$ids = $inv_id;
			$ids = array_filter($ids);
			$ids = implode(",",$inv_id);
			// echo $ids;
			// return;
			// if($ids > 0) {
			// 	$this->db->query("DELETE FROM stocks where id not in ($ids) ");
			// }
			foreach($inv_id as $k=>$v){
				$data  = " 	item_id = {$item_id[$k]}";
				$data .= ", type = 1 ";
				$data .= ", qty = '{$qty[$k]}' ";
				$data .= ", price = '". ltrim($cost[$k], "₱") ."' ";
				// echo $data;
				// return;
				if(empty($v)){
					$inv[] = $this->db->query("INSERT INTO stocks set $data");
					$inv_id[$k] = $this->db->insert_id;
				}else{
					$inv[] = $this->db->query("UPDATE stocks set $data where id = $v");
					$inv_id[$k] = $v;
				}
			}
			$this->db->query("UPDATE receiving set inventory_ids = '".implode(',',$inv_id)."' where id=$id ");
			return 1;
		}
		
		if(isset($dodong)){
			// echo "dodong";
			// return;
			$ids = $inv_id;
			$ids = array_filter($ids);
			
			$ids = implode(",",$inv_id);
		
			if($ace === "false") {	
				$this->db->query("DELETE FROM stocks where id not in ($ids) ");
			}
			foreach($inv_id as $k=>$v){
				$data  = " 	item_id = {$item_id[$k]}";
				$data .= ", type = 1 ";
				$data .= ", qty = '{$qty[$k]}' ";
				$data .= ", price = '{$cost[$k]}' ";
				
				if(empty($v)){
					$inv[] = $this->db->query("INSERT INTO stocks set $data");
					$inv_id[$k] = $this->db->insert_id;
				}else{
					$inv[] = $this->db->query("UPDATE stocks set $data where id = $v");
					$inv_id[$k] = $v;
				}
			}
			
			
			$this->db->query("UPDATE receiving set inventory_ids = '".implode(',',$inv_id)."' where id=$id ");
			return 1;
		}
		
	}
	function delete_receiving(){
		extract($_POST);
		$ids = $this->db->query("SELECT * FROM receiving where id=$id")->fetch_array()['inventory_ids'];
		if(!empty($ids) > 0)
			$this->db->query("DELETE FROM stocks where id in ($ids) ");
		$delete = $this->db->query("DELETE FROM receiving where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_order(){
		extract($_POST);
		$data = " user_id = {$_SESSION['login_id']} ";
   	$data .= ", total_amount = '$total_amount' ";
		$data .= ", amount_tendered = '$total_tendered' ";
		$data .= ", inventory_ids  = '' ";

		if(empty($id)){
			$save = $this->db->query("INSERT INTO sales set $data");
			$id = $this->db->insert_id;
		}else{
			$save = $this->db->query("UPDATE sales set $data where id = '$id' ");
		}
		if($save){
			$ids = $inv_id;
			$ids = array_filter($ids);
			$ids = implode(",",$inv_id);
			if($ids > 0){
			$qry = $this->db->query("SELECT * FROM sales where id= '$id' ")->fetch_array();
			$this->db->query("DELETE FROM stocks where id not in ($ids) and id in ({$qry['inventory_ids']})");
			}
			foreach($inv_id as $k=>$v){
				$data  = " 	item_id = {$item_id[$k]}";
				$data .= ", type = 2 ";
				$data .= ", qty = '{$qty[$k]}' ";
				$data .= ", price = '{$price[$k]}' ";
				if(empty($v)){
					$inv[] = $this->db->query("INSERT INTO stocks set $data");
					$inv_id[$k] = $this->db->insert_id;
				}else{
					$inv[] = $this->db->query("UPDATE stocks set $data where id = $v");
					$inv_id[$k] = $v;
				}
			}
			$this->db->query("UPDATE sales set inventory_ids = '".implode(',',$inv_id)."' where id=$id ");
			return $id;
		}
	}
	function delete_order(){
		extract($_POST);
		$ids = $this->db->query("SELECT * FROM sales where id=$id")->fetch_array()['inventory_ids'];
		if(!empty($ids) > 0)
			$this->db->query("DELETE FROM stocks where id in ($ids) ");
		$delete = $this->db->query("DELETE FROM sales where id = ".$id);
		if($delete){
			return 1;
		}
	}
}
