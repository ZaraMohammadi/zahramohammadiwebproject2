<?PHP

/*

function connect_db()
{
	$host = "localhost";
	$username = "root";
	$password = "styles123";
	$database = "webfinalproject";
	

	global $db;
	$db = mysqli_connect($host, $username, $password, $database);
	if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
	mysqli_set_charset($db , 'utf8mb4');
}
}

function record_exist( $UserName , $table ,$filed_in_db){
	
	connect_db();
	global $db;
	if(!$UserName) {
        die('ERROR: user ID could not be null or empty.');
    }
	
	global $db;
	$qr = " 	
	SELECT *
	FROM ".$table."
	where ".$filed_in_db." = '".$UserName."';";
    $result = mysqli_query($db, $qr);
	if (mysqli_num_rows($result) > 0) {
  #  echo "User already exists";
	return true;
} 
  else{
	  return false;
  }

}	
function passwordMaker($pass){
	$option = [
    "cost" => "10"
];
 $cript = password_hash($pass , PASSWORD_BCRYPT , $option);
 return  $cript;
}
function Add_users($UID,$N,$LN,$Phon,$mail,$pass,$pos,$Lastseen){
	connect_db();
	global $db;
	$crip = passwordMaker($pass);
	if (record_exist( $UID , 'user' ,'userID')){
	   return false;#یا خواندن تابعی که در آن یوزر می گیرد
	}
	$qr = "INSERT INTO user (userID, name ,lastName ,phoneNumber,mail,password,position,lastSeen )
	 values ('".$UID."','".$N."','".$LN."','".$Phon."','".$mail."','".$crip."','".$pos."','".$Lastseen."');";
	 if(mysqli_query($db, $qr)){
		 return true;
	 }
	else {
	   return false;
	   
   }
}
function delete_record($user_ID , $table , $field) {
	connect_db();
	global $db;
	if($table=='user'){
		$qu="
		SELECT *
		FROM duty
		where performerID = '".$user_ID."'"; 
		$result = mysqli_query($db, $qu);

		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$systemtime= date("Y-m-d h:i:sa");
					if($row['endTime']>$systemtime){
						return false;
						#echo "This user has unfinished duty";
						#echo "Are you sure you want to delete it all?";
					}
			}
		} else {
		
	
				if(!record_exist($user_ID, $table , $field)) {
					return false;
					#echo "User doesn't exists";
				}
				else{
				global $db;
				$qr="
					DELETE FROM ".$table."
					where ".$field." = '".$user_ID."';
				";
					if( mysqli_query($db, $qr)){
						return true;
						#echo "Deleted successfully";
					}
					else{
						return false;
						#echo "Error Deleting record :".mysqli_error($db);
					}
				}
		}
	
}}
function login($us , $pass){
	connect_db();
	global $db;
	$sql = "SELECT * FROM user
	WHERE userID = '".$us."'";
    $result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
	$p = $row ['password'];
    }
	if (password_verify($pass, $p)) {
		return true;
    #echo '  Password is valid!';
} else {
	return false;
   # echo 'Invalid password.';
}
} else {
   return false;
}
}
function show_All_employees(){
	connect_db();
	global $db;
	$qr="
		SELECT * 
		FROM user 
		WHERE position = '0'";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function IdMaker(){
	$id = uniqid();
	return $id;
}
function Add_Duty( $dutyTitle ,$description ,$statuse ,$creationTime,$endTime,$performerID,$SessionID){
	connect_db();
	global $db;
	$dutyID = IdMaker();
	while (record_exist( $dutyID ,'duty' ,'dutyID' )){
		$dutyID = IdMaker();
	}
	
	$qr = "INSERT INTO duty(dutyID, dutyTitle ,description ,statuse ,creationTime,endTime,performerID,SessionID)
	 values ('".$dutyID."','".$dutyTitle."','".$description."','".$statuse."','".$creationTime."','".$endTime."','".$performerID."','".$SessionID."');";
	if( mysqli_query($db, $qr)){
		return true;
	}
	else {
		return false;
	}
}
function Add_Session( $sessionTitle ,$creationTime ,$sessionTime ,$sessionAdmin,$sessionPlace,$description){
	connect_db();
	global $db;
	$q=" select position
	from user
	where userID = '".$sessionAdmin."' ";
	$result = mysqli_query($db, $q);
    $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		foreach($r_arrey as $nums)
	{
	   foreach($nums as $t){
		   if ($t == "0")
		   {
			   return false;
			   #employee cant be admin
		   }
		   
	}}
	/*
	if (!record_exist( $sessionAdmin ,'user' ,'userID')){
		return false;
		#echo "the Admin ID doesn't exist";
	}*//*
	$sessionID = IdMaker();
	while (record_exist( $sessionID ,'Session' ,'sessionID' )){
		$sessionID = IdMaker();
	}
	$qr = "INSERT INTO Session (sessionID, sessionTitle ,creationTime ,sessionTime ,sessionAdmin,sessionPlace,description)
	 values ('".$sessionID."','".$sessionTitle."','".$creationTime."','".$sessionTime."','".$sessionAdmin."','".$sessionPlace."','".$description."');";
	if( mysqli_query($db, $qr)){
		return true ;
	}
	else{
		return false;
	}

   
}
function Add_dutyReport( $dutyID,$reportDocument ,$Name , $type){
	connect_db();
	global $db;
	$ReportID = IdMaker();
	if (!record_exist( $dutyID ,'duty' ,'dutyID')){
		return false;
		#echo "the Duty ID doesn't exist";
	}
	
	$existQuery="
	SELECT *
	FROM dutyReport 
	WHERE dutyID = '".$dutyID."' and ReportID = '".$ReportID."'";
	
	$r = mysqli_query($db, $existQuery);
	
	while (mysqli_num_rows($r) > 0) 
	{
		$ReportID = IdMaker();
		$existQuery="
		SELECT *
		FROM dutyReport 
		WHERE dutyID = '".$dutyID."' and ReportID = '".$ReportID."'";
		$r = mysqli_query($db, $existQuery);
		#echo "The document ID  for this duty already exist.";
    }
		
		$qr = "INSERT INTO dutyReport (dutyID, reportDocument ,RName ,ReportID , type )
		 values ('".$dutyID."','".$reportDocument."','".$Name."','".$ReportID."','".$type."');";
		if( mysqli_query($db, $qr)){
			return true;
		}
		else {
			return false;
		}
   }
function Add_sessionDocument($sessionID, $Document  ,$type , $Name ,$prepost ){
	connect_db();
	global $db;
	$DocumentID = IdMaker();
	if (!record_exist( $sessionID ,'Session' ,'sessionID')){
		#echo "The sessin that you want to add document for , doesn't exist";
		return false;
	}
	$existQuery="
	SELECT *
	FROM sessionDocument 
	WHERE sessionID = '".$sessionID."' and DocumentID = '".$DocumentID."'";
	
	$r = mysqli_query($db, $existQuery);
	
	while (mysqli_num_rows($r) > 0) 
	{
		$DocumentID = IdMaker();
		$existQuery="
		SELECT *
		FROM sessionDocument 
		WHERE sessionID = '".$sessionID."' and DocumentID = '".$DocumentID."'";
	
	$r = mysqli_query($db, $existQuery);
		#echo "The document ID  for this session already exist.";
    }
		
		$qr = "INSERT INTO sessionDocument (sessionID, Document ,DocumentID ,type , SName , prepost )
		 values ('".$sessionID."','".$Document."','".$DocumentID."','".$type."','".$Name."','".$prepost."');";
		if(mysqli_query($db, $qr)){
			return true;
		}
		else{
			return false;
		}

}
function addMembers($User , $sessinId){
	connect_db();
	global $db;
	if (!record_exist( $sessinId , 'Session' ,'sessionID')){
	   return false;#جلسه وجود ندارد
	}
	$q = "select * FROM members 
		WHERE userID = '".$User."' and sessionID = '".$sessinId."' ";
		$result = mysqli_query($db, $q);
		
	if (mysqli_num_rows($result) > 0) {
		return false ;
	}
	
		$r_arrey =[];
		$qr = "INSERT INTO members (userID, sessionID  ) values ('".$User."','".$sessinId."');";
		
	 
	if( mysqli_query($db, $qr)){
		return true;
	}
	else {
		return false;
	}
	
}

function field_update($table , $colum ,$IDField ,$ID , $value , $secondIDField=null , $secondID=null){
	connect_db();
	global $db;
	if($secondID!=null){
	$qr = "UPDATE ".$table."
	SET ".$colum." ='".$value."' 
	WHERE ".$IDField." = '".$ID."' and ".$secondIDField." = '".$secondID."' ";
	}
	else{
	$qr = "UPDATE ".$table." 
	SET ".$colum." = '".$value."' 
	WHERE ".$IDField." = '".$ID."'";
	}
	
	if( mysqli_query($db, $qr)){
		return true;
	}
		else{
			return false;;
		}
}
function multiple_field_update($Table , $ID_field , $ID , $colum2=null , $value2=null , $colum3=null , $value3=null , $colum4=null , $value4=null , $colum5=null , $value5=null , $colum6=null , $value6=null ,$colum7=null , $value7=null,$colum8=null , $value8=null)
{
	connect_db();
	global $db;
	$n=1;
	if($Table == 'dutyReport'){
		$qr="
		UPDATE dutyReport
		SET ".$colum3." = '".$value3."' , ".$colum4." = '".$value4."' , ".$colum5." = '".$value5."'
		WHERE ".$ID_field." = '".$ID."' and ".$colum2." = '".$value2."' " ;
		if(mysqli_query($db , $qr))
		{
		return true;
		#echo "Record updated successfully";
	}
		else{
			return false;
			#echo "Error updating record :".mysqli_error($db);
		}
		
	}
	if($Table == 'sessionDocument'){
		$qr="
		UPDATE sessionDocument
		SET ".$colum3." = '".$value3."', ".$colum4." = '".$value4."' , ".$colum5." = '".$value5."' , ".$colum6." = '".$value6."'
		WHERE ".$ID_field." = '".$ID."' and ".$colum2." = '".$value2."' " ;
		if(mysqli_query($db, $qr)){
		return true;
		#echo "Record updated successfully";
	}
		else{
			return false;
			#echo "Error updating record :".mysqli_error($db);
		}
	}
	
	if($colum2!=null){$n=1; }
	if($colum3!=null){$n=2;}
	if($colum4!=null){$n=3;}
	if($colum5!=null){$n=4;}
	if($colum6!=null){$n=5;}
	if($colum7!=null){$n=6;}
	if($colum8!=null){$n=7;}
		switch ($n) {
		  case 1:
		$qr="
		UPDATE ".$Table."
		SET ".$colum2." = '".$value2."'
		WHERE ".$ID_field." = '".$ID."';";
			break;
		  case 2:
			$qr="
		UPDATE ".$Table."
		SET ".$colum2." = '".$value2."', ".$colum3." = '".$value3."'
		WHERE ".$ID_field." = '".$ID."';";
			break;
		  case 3:
			$qr="
		UPDATE ".$Table."
		SET ".$colum2." = '".$value2."', ".$colum3." = '".$value3."' , ".$colum4." = '".$value4."'
		WHERE ".$ID_field." = '".$ID."';";
			break;
		  case 4:
			$qr="
		UPDATE ".$Table."
		SET ".$colum2." = '".$value2."', ".$colum3." = '".$value3."' , ".$colum4." = '".$value4."' , ".$colum5." = '".$value5."'
		WHERE ".$ID_field." = ".$ID.";";
			break;
		   case 5:
			$qr="
		UPDATE ".$Table."
		SET ".$colum2." = '".$value2."', ".$colum3." = '".$value3."' , ".$colum4." = '".$value4."' , ".$colum5." = '".$value5."' , ".$colum6." = '".$value6."'
		WHERE ".$ID_field." = '".$ID."';";
			break;
		   case 6:
			$qr="
		UPDATE ".$Table."
		SET ".$colum2." = '".$value2."', ".$colum3." = '".$value3."' , ".$colum4." = '".$value4."' , ".$colum5." = '".$value5."' , ".$colum6." = '".$value6."' , ".$colum7." = '".$value7."'
		WHERE ".$ID_field." = '".$ID."';";
			break;
		   case 7:
			$qr="
		UPDATE ".$Table."
		SET ".$colum2." = '".$value2."', ".$colum3." = '".$value3."' , ".$colum4." = '".$value4."' , ".$colum5." = '".$value5."' , ".$colum6." = '".$value6."' , ".$colum7." = '".$value7."' , ".$colum8." = '".$value8."'
		WHERE ".$ID_field." = '".$ID."';";
			break;
		  default:
			return false;
} 
	if(mysqli_query($db, $qr)){
		return true;
		#echo "Record updated successfully";
	}
		else{
			return false;
			#echo "Error updating record :".mysqli_error($db);
		}
}
function list_of_duties_of_sessin($sessinId , $limit = null){
	connect_db();
	global $db;
	$string = 'LIMIT';
	
	if($limit==null){
		$string = ''; 
		$limit = '';
		
	}
	$qr="
		SELECT * 
		FROM duty 
		WHERE SessionID = '".$sessinId."'
		".$string." ".$limit."";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function serching_for_employee($field){
	connect_db();
	global $db;
	$qr="
		select * FROM user 
		WHERE name LIKE '%".$field."%' OR lastName LIKE '%".$field."%'
		;";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		
		return $r_arrey;
}
function sorting($table_name , $field , $idField , $id , $order = '' ){
	connect_db();
	global $db;
	$qr="
		SELECT * 
		FROM ".$table_name." 
		WHERE ".$idField." = '".$id."'
		ORDER BY ".$field." ".$order.";";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
}
function list_of_duties($id , $limit = null){
	connect_db();
	global $db;
	$string = 'LIMIT';
	if($limit==null){
		$string = ''; 
		$limit = '';
		
	}
	$qr="
		SELECT * 
		FROM duty 
		WHERE performerID = '".$id."' 
		ORDER BY creationTime DESC
		".$string." ".$limit."";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function list_of_session_for_employee($UserID , $limit = null){
	connect_db();
	global $db;
	$string = 'LIMIT';
	if($limit==null){
		$string = ''; 
		$limit = '';
		
	}
	$qr = "SELECT *
	FROM `session`
	INNER JOIN members ON members.sessionID = `session`.sessionID
	WHERE userID = '".$UserID."' 
	ORDER BY creationTime DESC 
	".$string." ".$limit." ";
	$result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		
		return $r_arrey;

}
function list_of_members_for_session($sessionID ){
	connect_db();
	global $db;
	$qr = "SELECT *
	FROM `members`
	INNER JOIN user ON members.userID = `user`.userID
	WHERE sessionID = '".$sessionID."' ";
	$result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		
		return $r_arrey;

}
function show_all_docs_of_session($sessionID , $field ='SName' , $order = 'ASC'){
	connect_db();
	global $db;
	$qr = "SELECT *
			FROM `sessionDocument` INNER JOIN `Session` 
			ON `sessionDocument`.sessionID = `Session`.sessionID 
			WHERE `Session`.sessionID = '".$sessionID."' and prepost = '0' 
			ORDER BY ".$field." ".$order.";";
	$result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		
		return $r_arrey;
}
function show_all_mosavabat_after_of_session($sessionID , $field ='SName' , $order = 'ASC'){
	connect_db();
	global $db;
	$qr = "SELECT *
			FROM `sessionDocument` INNER JOIN `Session` 
			ON `sessionDocument`.sessionID = `Session`.sessionID 
			WHERE `Session`.sessionID = '".$sessionID."' and prepost = '1'
			ORDER BY ".$field." ".$order.";";
	$result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		
		return $r_arrey;
}
function show_All_user(){
	connect_db();
	global $db;
	$qr="
		SELECT * 
		FROM user ";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function list_of_duties_of_sessin_with_docs($sessionID){
	connect_db();
	global $db;
	$qr="
		SELECT * 
		FROM duty LEFT JOIN `dutyReport` 
		ON `duty`.dutyID = `dutyReport`.dutyID
		WHERE SessionID = '".$sessionID."'" ;
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function list_of_All_reports($fiel = 'duty.creationTime' , $order = 'DESC' ){
	
	connect_db();
	global $db;
	$qr="
		SELECT * 
		FROM `session` INNER JOIN (duty INNER JOIN `dutyReport`
		ON `duty`.dutyID = `dutyReport`.dutyID) ON session.sessionID = duty.SessionID
		ORDER BY ".$fiel." ".$order."";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function showAll($table , $idfield , $id){
	connect_db();
	global $db;
	$qr="
		SELECT * 
		FROM ".$table." 
		WHERE ".$idfield." = '".$id."'";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function add_NewMember($sessionID){
	connect_db();
	global $db;
	$qr="
		SELECT *
		FROM `user` INNER JOIN `members` 
		ON `user`.userID = `members`.userID 
		WHERE `members`.sessionID != '".$sessionID."'";
	 $result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		return $r_arrey;
	
}
function list_of_session_for_admin($UserID , $limit = null){
	connect_db();
	global $db;
	$string = 'LIMIT';
	if($limit==null){
		$string = ''; 
		$limit = '';
		
	}
	$qr = "SELECT *
	FROM `session`
	WHERE sessionAdmin = '".$UserID."' 
	ORDER BY creationTime DESC 
	".$string." ".$limit." ";
	$result = mysqli_query($db, $qr);
	 $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		
		return $r_arrey;

}
function AdminDashboard(){
	$user = $_SESSION['user'] ;
	$position = $_SESSION['position'] ;
	$name = $_SESSION['name'] ;
	$lastName = $_SESSION['lname'] ;
	$session = [];
	$duties = [];
	$session = list_of_session_for_admin($userId ,'3');
	$session['sessionTitle'];
	$session['creationTime'];
	$duties = list_of_duties($userId , '3');
	
	
}

#####################################page functions####################################################################


#####################################main##################################################
function sessin(){
	session_start();
	$f = 'kkkkk';
$_SESSION['name'] = $f;
$_SESSION['lname'] = 'haji';
}
connect_db();
global $db;
    /*$persian_numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english_numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
	$field = '۱۲۳۴۵' ;
	$english_number = strtr($field, $persian_numbers, $english_numbers);
	echo $english_number;*/
	
	$username = $_POST['username'];
    $password = $_POST['password'];
	echo $username ;

/*if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $username = $_GET['username'];
    $password = $_GET['password'];
	 echo $username ;
	}
	*/

#if ($_SERVER['REQUEST_METHOD'] == 'POST') {/*
		#$username = $_POST['username'];
   # $password = $_POST['login-form'];

   
#}

/*$a = [];
foreach($a as $nums)
	{
		echo $nums['sessionTitle'];
		echo "  ".$nums['creationTime'];
	   #foreach($nums as $t){
		   
		   
	  # }
	   echo "-----------";

	}


#گزارش وظایف
#sorting('duty' ,'dutyTitle' ,'performerID' ,'128904' , 'DESC' );







/*
if(addMembers('120998','987145')){
	echo "d";
}
else echo 'hhh';
/*
$a = list_of_session_for_employee('179897');
foreach($a as $nums)
	{
		#echo $nums['name']." ".$nums['lastName'];
	   foreach($nums as $t){
		   echo $t;
		   
	   }
	   echo "-----------";

	}
/*
if(login('128904','12223ff2' )){
	echo "ok";
}
else {
	echo "no";
}
/*$a=[];
$a = serching_for_employ('a');
foreach($a as $nums)
	{
		echo $nums['name']." ".$nums['lastName'];
	  # foreach($nums as $t){
		   
		   
	  # }
	   echo "-----------";

	}
/*
if(multiple_field_update('duty','dutyID','64dfcc1c50c23' ,'description','استفاده از اکسل' , 'statuse' , '1' )){
	echo "ok";
}
else {
	echo "no";
}
/*
connect_db();
	global $db;
	$sessionAdmin = '128904';
	$q=" select position
	from user
	where userID = '".$sessionAdmin."' ";
	$result = mysqli_query($db, $q);
    $r_arrey=[];
	if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($r_arrey, $row);
    }
		}
		foreach($r_arrey as $nums)
	{
	   foreach($nums as $t){
		   if (&t == '0')
		   {
			   return false;
			   #کارمند حق دسترسی نداره برای ادمین شدن
		   }
		   
	}}

#Add_users('478522' , 'دنیا' , 'کلهر' , '09126588526' , ' ', '877gfd' , '0' , '2023-08-13 05:41:40');

	/*
	connect_db();
	global $db;
	$option = [
    "cost" => "10"
];
 $password ='iuy874';
 echo password_hash($password , PASSWORD_BCRYPT , $option);

function for_fun(){
	connect_db();
	global $db;
	$qr="
			SELECT * 
			FROM user 
			WHERE position = '0'";
		 $result = mysqli_query($db, $qr);
		 $r_arrey=[];
		if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			array_push($r_arrey, $row);
		}
		}
		foreach($r_arrey as $nums)
	{
	   foreach($nums as $t){
		   echo " ".$t;
		   
	   }

	}
}
/*
$result = mysqli_query($db, $existQuery);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo  $row['name'];
    }
} else {
    echo "0 results";
}
/*
$sql = "SELECT * FROM user WHERE userID='179897' ";
$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) > 0) {
    // User already exists
    echo "User already exists";
	}
*/

/*
$qr = " 	
	SELECT *
	FROM user 
	WHERE userID = '120998'";
$result = mysqli_query($db, $qr);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo  $row['name'];
    }
} else {
    echo "0 results";
}
*/
/*
function user_exist($db , $UserName){
	connecting();
	/*if(!$UserName) {
        die('ERROR: user ID could not be null or empty.');
    }*/
	
	#global $db;
	/*
	$qr = " 	
	SELECT *
	FROM user
	where userID = '120998';
	"
	$result = mysqli_query($db, $qr);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo  $row['name'];
    }
} else {
    echo "0 results";
}
}

function Add_users($UID,$N,$LN,$Phon,$mail,$pass,$pos,$Lastseen){
	
	
$q = "INSERT INTO user (userID, name ,lastName ,phoneNumber,mail,password,position,lastSeen )
 values ('".$UID."','".$N."','".$LN."','".$Phon."','".$mail."','".$pass."','".$pos."','".$Lastseen."')"
}
#".$UserName."
user_exist($db ,'120998')
*/
?>