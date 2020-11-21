<?php
$event_id = $_REQUEST['event_id'];
$id = $_REQUEST['id'];

$action = $_REQUEST['action'];
include("db.php");
if($action == 'approve'){
	$title = $_REQUEST['title'];
	$date = $_REQUEST['date'];
	$pass = $_REQUEST['password'];
	
	$sql="SELECT * FROM  event_booking WHERE id = '$event_id' And status = 'Pending'";
	$retval = mysqli_query( $con, $sql);
	$rowcount=mysqli_num_rows($retval);
	if($rowcount==1){
		//schedule_conference
		try {
			$login_client = new SoapClient("https://103.4.92.9:18543/esdk/services/tpCommon?wsdl", array('stream_context' => stream_context_create(array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false)))));
			$client = new SoapClient("https://103.4.92.9:18543/esdk/services/tpProfessionalConfMgr?wsdl", array('stream_context' => stream_context_create(array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false)))));
													
			$login = array('userName'=>'esdk_user2', 'clientType'=>'API','version'=>'2');
			$login_obj = $login_client->LoginRequest($login);
			
			$login_resultCode = $login_obj->resultCode;
			$login_randomSequence = $login_obj->randomSequence;
			$login_hashType = $login_obj->hashType;

			$my_file = 'test.txt';
			$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
			$data = $login_randomSequence;
			fwrite($handle, $data);
			
			//echo "login_resultCode:".$login_resultCode."<br>";
			//echo "login_randomSequence:".$login_randomSequence."<br>";
			//echo "login_hashType:".$login_hashType."<br><br>";
			
			if($login_resultCode==0){
				// invoke java code
				shell_exec("javac MyClass.java");
				shell_exec("java MyClass");
				
				$password = file_get_contents($my_file);
				$password = str_replace("\r\n","<br />",$password);
				
				//echo "authenticate_password:".$password."<br>";
				
				$pwd = array('encryptPassword'=>$password);
				$authenticate_obj = $login_client->Authenticate($pwd);
				$authenticate_resultCode = $authenticate_obj->resultCode;
				if($authenticate_resultCode == 0){
					//echo "authenticate_resultCode:".$authenticate_resultCode."<br>";
					$KeepAlive = array();
					$keepAlive_obj = $login_client->KeepAlive($KeepAlive);
					$keepAlive_resultCode = $keepAlive_obj->resultCode;
					//echo "keepalive_resultCode:".$keepAlive_resultCode."</br>";
					// invoke schedule conference 
					date_default_timezone_set("Asia/Karachi");
					$title = substr($title, 0, 63);
					$query = "SELECT * FROM wp_terms ORDER BY name ASC";
					$result = mysqli_query( $con, $query);
					$given_sites = "SELECT * FROM event_booking WHERE id = '$event_id'";
					$given_sites_result =  mysqli_fetch_row(mysqli_query( $con, $given_sites));
					$venue =$given_sites_result[12];
					$i = -1;
					$sites = array();
					/* if($venue != "All"){
						foreach ( $result as $result ){
							if(! empty($venue) && strpos($venue, $result['name'])!==false){
								$i = $i +1;
								$sites[$i] = array('type' => '4',
								'from' => 0,
								'dialingMode' => 0,
								'name' => $result['name'],
								'uri' => $result['slug']);
							}
						}
					} */
					$repeat = $_REQUEST['repeat'];
					if($repeat == 'Yes'){
						$day = $_REQUEST['day1'];
						$arr = str_split($day);
						$num_lec = $_REQUEST['num_lec'];
						$params1 = array('scheduleConf'=>array('name'=>$title, 'beginTime'=>$date, 'duration'=>'P0Y0M0DT2H0M0.000S', 'accessCode'=>$id,'password'=>$pass, 'sites'=>$sites,'mediaEncryptType'=>'0', 'cpResouce'=>2, 'presentation'=>0, 'conferenceMode'=>0, 'frequency'=>1, 'interval'=>1, 'weekDays'=>$arr,'weekDay'=>1,'monthDay'=>1, 'count'=>$num_lec));
					}
					else{
						$params1 = array('scheduleConf'=>array('name'=>$title, 'beginTime'=>$date, 'duration'=>'P0Y0M0DT2H0M0.000S','accessCode'=>$id,'password'=>$pass, 'sites'=>$sites, 'mediaEncryptType'=>'0', 'cpResouce'=>2, 'presentation'=>0, 'conferenceMode'=>0));
					}
					//var_dump($login_client);
					$cookies = $login_client->_cookies['JSESSIONID']['0'];
					$client->__setCookie("JSESSIONID", $cookies);
					if($repeat == 'Yes')
						$objectresult1 = $client->scheduleRecurrenceConferenceEx($params1);
					else
						$objectresult1 = $client->scheduleConfEx($params1);
					$scheduleConf_resultCode = $objectresult1->resultCode;
					
					echo "Schedule Conference resultCode:".$scheduleConf_resultCode."</br>";
					if($scheduleConf_resultCode==0){
						$sql = "UPDATE event_booking SET status = 'Approved' WHERE event_id = '$id'";
						$res=mysqli_query($con, $sql);
						if($res){
							$to1 =$given_sites_result[9];
								$subject1 = 'Reservation of Meeting Rooms';
								$message1 ='<font face="Arial, Helvetica, sans-serif" size="4" color="" style="font-size: 15px;">';
								$message1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Room Reservation Request is approved successfully and display on calendar.";
								$message1 .='</font>';
								$subject1 = "Schedule conference.";
								$headers1 = "MIME-Version: 1.0\n";
								$headers1 .= "Content-Type: text/html; charset=utf-8\n";
								$headers1 .= 'From: events@hec.gov.pk' . "\n";
								if(mail($to1, $subject1, $message1, $headers1)){
									echo "A conference Schedule Successfuly with Conference ID: ".$id." and Password: ".$pass."<br>";
									echo "To view on SMC click on view. <a href='https://smc.hec.gov.pk/ScheduledConference/Default.aspx'>View</a>";
								}
						}
					}
				}
			}
		} catch (SoapFault $exception) {
				echo $exception;
		}
		
	}else{
		$sql="SELECT * FROM  event_booking WHERE id = '$event_id' And status = 'Approved'";
		$retval = mysqli_query( $con, $sql);
		$rowcount=mysqli_num_rows($retval);
		if($rowcount>1)
			echo "The conference is already Approved.<br>";
		else
			echo "The conference is already Canceled.<br>";
	}
}
else if($action == 'approve_simple'){
	$sql="SELECT * FROM  event_booking WHERE event_id = '$id' And status = 'Pending'";
	$retval = mysqli_query( $con, $sql);
	$rowcount=mysqli_num_rows($retval);
	if($rowcount>0){
		$sql = "UPDATE event_booking SET status = 'Approved' WHERE event_id = '$id'";
		$res=mysqli_query($con, $sql);
		if($res){
			$given_sites = "SELECT * FROM event_booking WHERE id = '$event_id'";
			$given_sites_result =  mysqli_fetch_row(mysqli_query( $con, $given_sites));
			$to1 =$given_sites_result[9];
			$subject1 = 'Reservation of Meeting Rooms';
			$message1 ='<font face="Arial, Helvetica, sans-serif" size="4" color="" style="font-size: 15px;">';
			$message1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Room Reservation Request is approved successfully and display on calendar.";
			$message1 .='</font>';
			$subject1 = "Schedule conference.";
			$headers1 = "MIME-Version: 1.0\n";
			$headers1 .= "Content-Type: text/html; charset=utf-8\n";
			$headers1 .= 'From: events@hec.gov.pk' . "\n";
			if(mail($to1, $subject1, $message1, $headers1)){
				echo "You have Approved the given request.<br>";
			}
		}
	}else{
		$sql="SELECT * FROM  event_booking WHERE event_id = '$id' And status = 'Approved'";
		$retval = mysqli_query( $con, $sql);
		$rowcount=mysqli_num_rows($retval);
		if($rowcount==1)
			echo "The conference is already Approved.<br>";
		else
			echo "The conference is already Canceled12.<br>";
	}
}
else{
	$sql="SELECT * FROM  event_booking WHERE id = '$event_id' And status = 'Pending'";
	$retval = mysqli_query( $con, $sql);
	$rowcount=mysqli_num_rows($retval);
	if($rowcount>1){
		$sql = "UPDATE event_booking SET status = 'Canceled' WHERE event_id = '$id'";
		$res=mysqli_query($con, $sql);
		if($res){
			$given_sites = "SELECT * FROM event_booking WHERE id = '$event_id'";
			$given_sites_result =  mysqli_fetch_row(mysqli_query( $con, $given_sites));
			$to1 =$given_sites_result[9];
			$subject1 = 'Reservation of Meeting Rooms';
			$message1 ='<font face="Arial, Helvetica, sans-serif" size="4" color="" style="font-size: 15px;">';
			$message1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Room Reservation Request is Canceled.";
			$message1 .='</font>';
			$subject1 = "Schedule conference.";
			$headers1 = "MIME-Version: 1.0\n";
			$headers1 .= "Content-Type: text/html; charset=utf-8\n";
			$headers1 .= 'From: events@hec.gov.pk' . "\n";
			if(mail($to1, $subject1, $message1, $headers1)){
				echo "You have canceled the given request.<br>";
			}
		}
	}else{
		$sql="SELECT * FROM  event_booking WHERE id = '$event_id' And status = 'Canceled'";
		$retval = mysqli_query( $con, $sql);
		$rowcount=mysqli_num_rows($retval);
		if($rowcount==1)
			echo "The conference is already Canceled.<br>";
		else
			echo "The conference is already Approved.<br>";
	}
}
?>