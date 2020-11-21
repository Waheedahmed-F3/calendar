<?php
session_start();
	if(!isset($_SESSION['Email'])){
		
		header("location:index.php");
	}
include("db.php");
$msg = "";
if($_SERVER["PHP_SELF"]) {
	if(isset($_POST['event_title']) And isset($_POST['video_conf']) And isset($_POST['start_date']) And isset($_POST['end_date'])){
		$title = $_POST['event_title'];
		$room_name = $_POST['room_name'];
		
		$repeat = $_POST['repeat'];
		$week_days = "";
		if($repeat == "Yes"){
			$w_days = $_POST['week_days'];
			$week_days = implode(", ",$w_days);
		}
		//$num_session = $_POST['num_session'];
		$num_session = 0;
		$name = $_SESSION['Name'];
		$email = $_SESSION['Email'];
		$job_title = $_SESSION['Title'];
		$video_conf = $_POST['video_conf'];
		$venues = "";
		if($video_conf == 'event-info'){
			$venues = $_POST['venues'];
			$venues = implode(", ",$venues);
		}
		$start_date = $_POST['start_date'];
		$st_date = $_POST['start_date'];
		$start_time = $_POST['start_time'];
		$end_date = $_POST['end_date'];
		$end_time = $_POST['end_time'];
		$start_time = date("H:i:s", strtotime($start_time));
		$end_time = date("H:i:s", strtotime($end_time));
		
		$diff=date_diff(date_create($start_date),date_create($end_date));
		$num_days = $diff->format("%d");
		$num_days = $num_days+1;
		$check = false;
		$event_id =  mysqli_fetch_row(mysqli_query($con, "SELECT max(id) FROM event_booking"));
		$event_id = $event_id[0]+1;
			$approved_events = $event_id;
			if($approved_events<100)
				$approved_events = $approved_events . "00";
			else if($approved_events>=100 And $approved_events<1000)
				$approved_events = $approved_events . "0";
		if($repeat == "Yes"){
				$a = Array();
				if (strpos($week_days, "0") !== false){
					$a[] = "Sun";
				}
				if (strpos($week_days, "1") !== false){
					$a[] = "Mon";
				}
				if (strpos($week_days, "2") !== false){
					$a[] = "Tue";
				}
				if (strpos($week_days, "3") !== false){
					$a[] = "Wed";
				}
				if (strpos($week_days, "4") !== false){
					$a[] = "Thu";
				}
				if (strpos($week_days, "5") !== false){
					$a[] = "Fri";
				}
				if (strpos($week_days, "6") !== false){
					$a[] = "Sat";
				}
			for($i=0;$i<$num_days;$i++){
				//echo $week_days[$i];
				$start_date = date("Y-m-d", strtotime($start_date));
				$date_day = date("D", strtotime($start_date));
				//echo $date_day;
				if (in_array($date_day, $a)) {
					//echo "123";
					$num_session =$num_session+1;
					$str = "INSERT INTO event_booking (event_id, Title, start_date, start_time, end_time, event_class, room, name, email, job_title,status, venues, week_days, num_sessions) VALUES 
						('$approved_events', '$title', '$start_date', '$start_time', '$end_time' ,'$video_conf', '$room_name', '$name', '$email', '$job_title', 'Pending', '$venues', '$week_days', '$num_session')";
					$res=mysqli_query($con, $str);
					if($res)
						$check = true;
				}
				$start_date = date('Y-m-d', strtotime($start_date. ' +1 days'));
			}

		}else{
			for($i=0;$i<$num_days;$i++){
				$num_session =$num_session+1;
				$start_date = date("Y-m-d", strtotime($start_date));
				$str = "INSERT INTO event_booking (event_id, Title, start_date, start_time, end_time, event_class, room, name, email, job_title,status, venues, week_days, num_sessions) VALUES 
					('$approved_events', '$title', '$start_date', '$start_time', '$end_time' ,'$video_conf', '$room_name', '$name', '$email', '$job_title', 'Pending', '$venues', '$week_days', '$num_session')";
				$res=mysqli_query($con, $str);
				if($res)
					$check = true;
				$start_date = date('Y-m-d', strtotime($start_date. ' +1 days'));
			}
		}
		if($check){
			$id = mysqli_insert_id($con);
			
			ini_set("SMTP","111.68.100.154");
			ini_set("smtp_port","25");
			$to = "wahahmed@hec.gov.pk";
			ini_set('sendmail_from', 'events@hec.gov.pk');
			$subject = 'Room Scheduling Request';
			$message ='<font face="Arial, Helvetica, sans-serif" size="4" color="" style="font-size: 15px;">';
			$message .= "Dear Sir, <br>";
			$message .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A new event regarding [".$title."] name ";
			// conference scheduling request //
			if($video_conf == 'event-info'){
				$message .= "is add on Calendar and requesting to Schedule a room on SMC.<br><br>";
				$message .= "Conference Name: ".$title."<br><br>";
				$message .= "Start Date: ".$st_date."<br><br>";
				$message .= "Session Time: ".$start_time." to ".$end_time."<br><br>";
				$message .= "Conference ID: ".$approved_events."<br><br>";
				$pasw = rand(1000, 9999);
				$message .= "Password: ".$pasw."<br><br>";
				$message .= "Click on given buttons to Approve and schedule conference or Decline request.<br>";
				$ti = str_replace(" ","%20",$title);
				if($repeat == "Yes"){
					$days = "";
					if (is_array($w_days) || is_object($w_days)){
						foreach($w_days as $d){
							$days .= $d;
						}
					}
					$message .='<table cellspacing="0" cellpadding="0">
								<tr>
								<td align="center" width="150" height="35" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
								<a href="http://events.hec.gov.pk/calendar/schedule_conference.php/?action=approve&event_id='.$id.'&id='.$approved_events.'&repeat='.$repeat.'&day1='.$days.'&num_lec='.$num_session.'&password='.$pasw.'&title='.$ti.'&date='.$st_date.'" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block"><span style="color: #FFFFFF">Approve</span></a>
								</td><td  width="50" height="35" bgcolor="white"></td>';
					$message .='<td align="center" width="150" height="35" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
								<a href="http://events.hec.gov.pk/calendar/schedule_conference.php/?action=decline&event_id='.$id.'&id='.$approved_events.'&password='.$pasw.'&title='.$ti.'&date='.$st_date.'" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block"><span style="color: #FFFFFF">Decline</span></a>
								</td>
								</tr>
								</table>';
				}
				else{
					$message .='<table cellspacing="0" cellpadding="0">
								<tr>
								<td align="center" width="150" height="35" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
								<a href="http://events.hec.gov.pk/calendar/schedule_conference.php/?action=approve&event_id='.$id.'&id='.$approved_events.'&repeat='.$repeat.'&password='.$pasw.'&title='.$ti.'&date='.$st_date.'" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block"><span style="color: #FFFFFF">Approve</span></a>
								</td><td  width="50" height="35" bgcolor="white"></td>';
					$message .='<td align="center" width="150" height="35" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
								<a href="http://events.hec.gov.pk/calendar/schedule_conference.php/?action=decline&event_id='.$id.'&id='.$approved_events.'&password='.$pasw.'&title='.$ti.'&date='.$st_date.'" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block"><span style="color: #FFFFFF">Decline</span></a>
								</td>
								</tr>
								</table>';
				}
			}
			else{
				$message .= "is add on Calendar and requesting to Approve or Decline request.<br><br>";
				$message .= "Event Name: ".$title."<br><br>";
				$message .= "Start Date: ".$st_date."<br><br>";
				$message .= "Session Time: ".$start_time." to ".$end_time."<br><br>";
				$message .= "Click on given buttons to Approve and schedule conference or Decline request.<br>";
				$message .='<table cellspacing="0" cellpadding="0">
							<tr>
							<td align="center" width="150" height="35" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
							<a href="http://events.hec.gov.pk/calendar/schedule_conference.php/?action=approve_simple&event_id='.$id.'&id='.$approved_events.'" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block"><span style="color: #FFFFFF">Approve</span></a>
							</td><td  width="50" height="35" bgcolor="white"></td>';
				$message .='<td align="center" width="150" height="35" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
							<a href="http://events.hec.gov.pk/calendar/schedule_conference.php/?action=decline&event_id='.$id.'&id='.$approved_events.'" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block"><span style="color: #FFFFFF">Decline</span></a>
							</td>
							</tr>
							</table>';
			}
			
			//$message .='<br><br>To view list of all Requests click <a href="http://events.hec.gov.pk/approve-conference/">Here</a>';
			$message .='<br><br>Thanks';
			$message .='</font>';
			$subject = "Schedule conference.";
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=utf-8\n";
			$headers .= 'From: events@hec.gov.pk' . "\n";
			$subject1 = 'Reservation of Meeting Rooms';
			$message1 ='<font face="Arial, Helvetica, sans-serif" size="4" color="" style="font-size: 15px;">';
			$message1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanks! Your event added successfully and display on calendar after admin approvel.";
			$message1 .='</font>';
			$headers1 = "MIME-Version: 1.0\n";
			$headers1 .= "Content-Type: text/html; charset=utf-8\n";
			$headers1 .= 'From: events@hec.gov.pk' . "\n";
			$to1 = $_SESSION['Email'];
			if(mail($to, $subject, $message, $headers) And mail($to1, $subject1, $message1, $headers1)){
				header("location:form_successfull.php");
			}
		}
		
	}
}	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Reservation of Meeting Rooms</title>
	<link rel="icon" href="HEC-Logo.png">

	<meta name="description" content="Full view calendar component for twitter bootstrap with year, month, week, day views.">
	<meta name="keywords" content="jQuery,Bootstrap,Calendar,HTML,CSS,JavaScript,responsive,month,week,year,day">
	<meta name="author" content="Serhioromano">
	<meta charset="UTF-8">
<!-- given below are the multiselect bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<link rel="stylesheet" href="dist/css/bootstrap-select.css">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.js" defer></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js" defer></script>
	<script src="dist/js/bootstrap-select.js" defer></script>
 <!--- end--->
  
	<link rel="stylesheet" href="bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script language="javascript" type="text/javascript" src="js/datetimepicker.js"></script>
	
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
			<script type="text/javascript" src="jquery.timepicker.js"></script>
			<link rel="stylesheet" type="text/css" href="jquery.timepicker.css" />
			<script type="text/javascript" src="bootstrap-datepicker.js"></script>
			<link rel="stylesheet" type="text/css" href="bootstrap-datepicker.css" />
			<script src="datepair.js"></script>
			<script src="jquery.datepair.js"></script>
	<style type="text/css">
		.btn-twitter {
			padding-left: 30px;
			background: rgba(0, 0, 0, 0) url(https://platform.twitter.com/widgets/images/btn.27237bab4db188ca749164efd38861b0.png) -20px 6px no-repeat;
			background-position: -20px 11px !important;
		}
		.btn-twitter:hover {
			background-position:  -20px -18px !important;
		}
	</style>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Event Form</a>
    </div>
   <ul class="nav navbar-nav">
      <li class="active"><a href="calendar.php">Home</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="form.php"><i class="fa fa-calendar-check-o"></i> Add Event</a></li>
	  <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="logout.php"><span title="<?php echo $_SESSION['Name'];?>"><i class="fa fa-sign-out"></i> Logout</span></a></li>
    </ul>
  </div>
</nav>
<div class="container">
<h4 style="color:#436E90">Add New Event</h4><hr>
	<?php echo $msg ?>			  
<form action="" class="form-horizontal" method="post">
		<!--input type="hidden" id="room_name" name="room_name" value="Lecture Hall" /-->
            
			<div class="form-group">
				<label class="col-sm-2 control-label">Event Title<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<input type="text" name="event_title" required id="event_title" style="width:100%;height:30px"  autocomplete="off"  placeholder="Enter Event Title" width="48">
						<p id="content1"></p>
					</div>
			</div>
			<div class="clearfix"></div>
			<p ><div class="form-group" id="boards">
					<label class="col-sm-2 control-label">Select Room<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<select id="room_name" required name="room_name" style="width:100%;height:30px"  class="dropdown">
							<option value="" selected="selected" disabled="disabled">-- select one --</option>
							<option value="Lecture Hall">Lecture Hall</option>
							<option value="Data Center">Data Center</option>
						</select>
					</div>
				</div>
			</p>
			<div class="clearfix"></div>
			<p ><div class="form-group" id="boards">
					<label class="col-sm-2 control-label">Video Conference<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<select id="video_conf" required name="video_conf" style="width:100%;height:30px"  class="dropdown">
							<option value="event-info">Yes</option>
							<option value="event-important">No</option>
						</select>
					</div>
				</div>
			</p>
			<div class="clearfix"></div>
			<div class="form-group">
			  <label id="sites" class="col-sm-2  control-label">Select Video Conferencing Sites<span style="color:red;">*</span></label>
			   <div class="col-sm-4" id="sites1">
				  <select  style="width:100%;height:30px" class="selectpicker" name='venues[]' multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true">
					
																				<option value="HEC Lecture Hall">HEC Lecture Hall</option>
																				<option value="Regional Center Quetta">Regional Center Quetta</option>
																				<option value="HEC Regional Office, Peshawar">HEC Regional Office, Peshawar</option>
																				<option value="HEC Regional Office, Lahore">HEC Regional Office, Lahore</option>
																				<option value="HEC Regional Office, Karachi">HEC Regional Office, Karachi</option>
																				<option value="Allama Iqbal Open University">Allama Iqbal Open University</option>
																				<option value="Air University">Air University</option>
																				<option value="Bahria University">Bahria University</option>
																				<option value="Center for Advanced Studies in Engineering">Center for Advanced Studies in Engineering</option>
																				<option value="COMSATS Institute of Information Technology Islamabad">COMSATS Institute of Information Technology Islamabad</option>
																				<option value="Capital University of Science and Technology, Islamabad">Capital University of Science and Technology, Islamabad</option>
																				<option value="Federal Urdu University">Federal Urdu University</option>
																				<option value="Foundation University Islamabad">Foundation University Islamabad</option>
																				<option value="FAST(National University of Computer and Emerging Sciences)">FAST(National University of Computer and Emerging Sciences)</option>
																				<option value="Institute of Space Technology">Institute of Space Technology</option>
																				<option value="International Islamic University">International Islamic University</option>
																				<option value="Iqra University">Iqra University</option>
																				<option value="Muslim Youth University">Muslim Youth University</option>
																				<option value="National Defence University, Pakistan">National Defence University, Pakistan</option>
																				<option value="National University of Modern Languages">National University of Modern Languages</option>
																				<option value="National University of Sciences and Technology">National University of Sciences and Technology</option>
																				<option value="National University of Medical Sciences, Islamabad">National University of Medical Sciences, Islamabad</option>
																				<option value="Pakistan Institute of Development Economics">Pakistan Institute of Development Economics</option>
																				<option value="Pakistan Institute of Engineering and Applied Sciences">Pakistan Institute of Engineering and Applied Sciences</option>
																				<option value="Quaid-i-Azam University">Quaid-i-Azam University</option>
																				<option value="Riphah International University">Riphah International University</option>
																				<option value="Shaheed Zulfiqar Ali Bhutto Medical University">Shaheed Zulfiqar Ali Bhutto Medical University</option>
																				<option value="Shifa Tameer-e-Millat University">Shifa Tameer-e-Millat University</option>
																				<option value="Shaheed Zulfikar Ali Bhutto Institute of Science and Technology">Shaheed Zulfikar Ali Bhutto Institute of Science and Technology</option>
																				<option value="Virtual University of Pakistan">Virtual University of Pakistan</option>
																				<option value="Al-Hamd Islamic University">Al-Hamd Islamic University</option>
																				<option value="Balochistan University of Engineering and Technology">Balochistan University of Engineering and Technology</option>
																				<option value="Balochistan University of Information Technology, Engineering and Management Sciences">Balochistan University of Information Technology, Engineering and Management Sciences</option>
																				<option value="Lasbela University of Agriculture, Water and Marine Sciences">Lasbela University of Agriculture, Water and Marine Sciences</option>
																				<option value="Sardar Bahadur Khan Women's University">Sardar Bahadur Khan Women's University</option>
																				<option value="University of Balochistan">University of Balochistan</option>
																				<option value="	University of Turbat">University of Turbat</option>
																				<option value="University of Loralai">University of Loralai</option>
																				<option value="Abdul Wali Khan University">Abdul Wali Khan University</option>
																				<option value="Abasyn University">Abasyn University</option>
																				<option value="Abbottabad University of Science and Technology">Abbottabad University of Science and Technology</option>
																				<option value="Bacha Khan University">Bacha Khan University</option>
																				<option value="CECOS University of IT and Emerging Sciences">CECOS University of IT and Emerging Sciences</option>
																				<option value="City University of Science and IT">	City University of Science and IT</option>
																				<option value="COMSATS Institute of IT">COMSATS Institute of IT</option>
																				<option value="Fast University, Peshawar Campus">Fast University, Peshawar Campus</option>
																				<option value="Gandhara University">Gandhara University</option>
																				<option value="GIK Institute of Engineering & Technology">GIK Institute of Engineering & Technology</option>
																				<option value="Gomal University, D. I. khan">Gomal University, D. I. khan</option>
																				<option value="Hazara University">Hazara University</option>
																				<option value="IQRA National University">IQRA National University</option>
																				<option value="Islamia College University">Islamia College University</option>
																				<option value="Institute of Management Sciences, (IM Sciences), Peshawar">Institute of Management Sciences, (IM Sciences), Peshawar</option>
																				<option value="Khushal Khan Khattak University">Khushal Khan Khattak University</option>
																				<option value="Kohat University of Science and Technology">Kohat University of Science and Technology</option>
																				<option value="Khyber Pakhtunkhwa Agricultural University, Peshawar">Khyber Pakhtunkhwa Agricultural University, Peshawar</option>
																				<option value="Khyber Medical University">Khyber Medical University</option>
																				<option value="Lahore University of Management Sciences (LUMS), Lahore">Lahore University of Management Sciences (LUMS), Lahore </option>
																				<option value="Northern University">Northern University</option>
																				<option value="Pakistan Military Academy (PMA)">Pakistan Military Academy (PMA)</option>
																				<option value="Preston University, Kohat">Preston University, Kohat</option>
																				<option value="Qurtuba University">Qurtuba University</option>
																				<option value="Shaheed Benazir Bhutto University, Sheringal">Shaheed Benazir Bhutto University, Sheringal</option>
																				<option value="Shaheed Benazir Bhutto Women University">Shaheed Benazir Bhutto Women University</option>
																				<option value="Sarhad University of Science and IT">Sarhad University of Science and IT</option>
																				<option value="Shuhada-e-Army Public School University of Technology, Nowshera">Shuhada-e-Army Public School University of Technology, Nowshera</option>
																				<option value="University of Peshawar">University of Peshawar</option>
																				<option value="University of Engineering and Technology, Peshawar">University of Engineering and Technology, Peshawar</option>
																				<option value="University of Agriculture, Peshawar">University of Agriculture, Peshawar</option>
																				<option value="University of Malakand, Chakdara">University of Malakand, Chakdara</option>
																				<option value="University of Science and Technology">University of Science and Technology</option>
																				<option value="University of Swat">University of Swat</option>
																				<option value="University of Haripur">University of Haripur</option>
																				<option value="University of FATA">University of FATA</option>
																				<option value="University of Swabi">University of Swabi</option>
																				<option value="University of Swabi for Women, Swabi">University of Swabi for Women, Swabi</option>
																				<option value="University of Chitral">University of Chitral</option>
																				<option value="Women University Mardan">Women University Mardan</option>
																				<option value="Ali Institute of Education	Lahore">Ali Institute of Education	Lahore</option>
																				<option value="Beaconhouse National University	Lahore">Beaconhouse National University	Lahore</option>
																				<option value="Bahauddin Zakariya University	Multan">Bahauddin Zakariya University	Multan</option>
																				<option value="Cholistan University of Veterinary and Animal Sciences, Bahawalpur">Cholistan University of Veterinary and Animal Sciences, Bahawalpur</option>
																				<option value="Fatima Jinnah Women University	Rawalpindi">Fatima Jinnah Women University	Rawalpindi</option>
																				<option value="Fatima Jinnah Medical University, Lahore">Fatima Jinnah Medical University, Lahore</option>
																				<option value="Forman Christian College	Lahore">Forman Christian College	Lahore</option>
																				<option value="Foundation University Rawalpindi">Foundation University Rawalpindi</option>
																				<option value="Greenfields college	sargodha">Greenfields college	sargodha</option>
																				<option value="GIFT University	Gujranwala">GIFT University	Gujranwala</option>
																				<option value="Global Institute, Lahore">Global Institute, Lahore</option>
																				<option value="Ghazi University	Dera Ghazi Khan">Ghazi University	Dera Ghazi Khan</option>
																				<option value="Government College University, Faisalabad">Government College University, Faisalabad</option>
																				<option value="Government College University, Lahore">Government College University, Lahore</option>
																				<option value="Government College Women University, Faisalabad">Government College Women University, Faisalabad</option>
																				<option value="Government College Women University, Sialkot">Government College Women University, Sialkot</option>
																				<option value="Government Sadiq College Women University	Bahawalpur">Government Sadiq College Women University	Bahawalpur</option>
																				<option value="Hajvery University	Lahore">Hajvery University	Lahore</option>
																				<option value="HITEC University	Taxila">HITEC University	Taxila</option>
																				<option value="Imperial College of Business Studies	Lahore">Imperial College of Business Studies	Lahore</option>
																				<option value="Information Technology University Lahore">Information Technology University Lahore</option>
																				<option value="Islamia University	Bahawalpur">Islamia University	Bahawalpur</option>
																				<option value="Institute of Management Sciences, Lahore">Institute of Management Sciences, Lahore</option>
																				<option value="Institute of Southern Punjab	Multan">Institute of Southern Punjab	Multan</option>
																				<option value="Khawaja Fareed University of Engineering and Information Technology	Rahim Yar Khan">Khawaja Fareed University of Engineering and Information Technology	Rahim Yar Khan</option>
																				<option value="King Edward Medical University	Lahore">King Edward Medical University	Lahore</option>
																				<option value="Kinnaird College for Women University	Lahore">Kinnaird College for Women University	Lahore</option>
																				<option value="Lahore Garrison University	Lahore">Lahore Garrison University	Lahore</option>
																				<option value="Lahore Leads University	Lahore">Lahore Leads University	Lahore</option>
																				<option value="Lahore School of Economics	Lahore">Lahore School of Economics	Lahore</option>
																				<option value="Lahore College for Women University	Lahore">Lahore College for Women University	Lahore</option>
																				<option value="Lahore University of Management Sciences (LUMS)	Lahore">Lahore University of Management Sciences (LUMS)	Lahore</option>
																				<option value="Minhaj University, Lahore">Minhaj University, Lahore</option>
																				<option value="Muhammad Nawaz Sharif University of Agriculture	Multan">Muhammad Nawaz Sharif University of Agriculture	Multan</option>
																				<option value="Muhammad Nawaz Sharif University of Engineering and Technology	Multan">Muhammad Nawaz Sharif University of Engineering and Technology	Multan</option>
																				<option value="National College of Business Administration and Economics Lahore">National College of Business Administration and Economics	Lahore</option>
																				<option value="National College of Arts Lahore">National College of Arts (NCA)	Lahore</option>
																				<option value="National Textile University	Faisalabad">National Textile University	Faisalabad</option>
																				<option value="NFC Institute of Engineering and Technology	Multan">NFC Institute of Engineering and Technology	Multan</option>
																				<option value="NUR International University	Lahore">NUR International University	Lahore</option>
																				<option value="Pakistan Institute of Fashion and Design	Lahore">Pakistan Institute of Fashion and Design	Lahore</option>
																				<option value="Pir Mehr Ali Shah Arid Agriculture University Rawalpindi">Pir Mehr Ali Shah Arid Agriculture University	Rawalpindi</option>
																				<option value="Qarshi University	Lahore">Qarshi University	Lahore</option>
																				<option value="Rawalpindi Medical University, Rawalpindi">Rawalpindi Medical University, Rawalpindi</option>
																				<option value="Sargodha Institute of Technology	Sargodha">Sargodha Institute of Technology	Sargodha</option>
																				<option value="The Superior College	Lahore">The Superior College Lahore</option>
																				<option value="The Institute of Management Sciences, (Pak-AIMS, Lahore">The Institute of Management Sciences, (Pak-AIMS, Lahore</option>
																				<option value="University of Chakwal">University of Chakwal</option>
																				<option value="University of Central Punjab	Lahore">University of Central Punjab Lahore</option>
																				<option value="University of Faisalabad">University of Faisalabad</option>
																				<option value="University of Lahore">University of Lahore</option>
																				<option value="University of Management and Technology, Lahore">University of Management and Technology, Lahore</option>
																				<option value="University of South Asia	Lahore">University of South Asia	Lahore</option>
																				<option value="University of Wah">University of Wah</option>
																				<option value="University of Sargodah">University of Sargodah</option>
																				<option value="University of Agriculture, Faisalabad">University of Agriculture, Faisalabad</option>
																				<option value="University of Education	Lahore">University of Education	Lahore</option>
																				<option value="University of Engineering and Technology, Lahore">University of Engineering and Technology, Lahore</option>
																				<option value="University of Engineering and Technology, Taxila">University of Engineering and Technology, Taxila</option>
																				<option value="University of Gujrat">University of Gujrat</option>
																				<option value="University of Health Sciences, Lahore">University of Health Sciences, Lahore</option>
																				<option value="University of the Punjab">University of the Punjab</option>
																				<option value="University of Veterinary and Animal Sciences	Lahore">University of Veterinary and Animal Sciences	Lahore</option>
																				<option value="University College of Agriculture, Sargodha">University College of Agriculture, Sargodha</option>	
																				<option value="University of Engineering and Technology	Rasul, Mandi Bahauddin">University of Engineering and Technology	Rasul, Mandi Bahauddin</option>
																				<option value="University of Jhang">University of Jhang</option>
																				<option value="University of Okara">University of Okara</option>
																				<option value="University of Sahiwal">University of Sahiwal</option>
																				<option value="Women University Multan">Women University Multan</option>
																				<option value="AEMC">AEMC</option>
																				<option value="Benazir Bhutto Shaheed University	Karachi">	Benazir Bhutto Shaheed University	Karachi</option>
																				<option value="Benazir Bhutto Shaheed University of Technology & Skill Development, Khairpur Mirs">	Benazir Bhutto Shaheed University of Technology & Skill Development, Khairpur Mirs</option>
																				<option value="Baqai Medical University	Karachi">	Baqai Medical University	Karachi</option>
																				<option value="Barret Hodgson University, Karachi">Barret Hodgson University, Karachi</option>
																				<option value="Commecs Institute of Business and Emerging Sciences	Karachi">	Commecs Institute of Business and Emerging Sciences	Karachi</option>
																				<option value="Dawood University of Engineering and Technology	Karachi">	Dawood University of Engineering and Technology	Karachi	</option>
																				<option value="Dow University of Health Sciences	Karachi">	Dow University of Health Sciences	Karachi</option>
																				<option value="Dadabhoy Institute of Higher Education">	Dadabhoy Institute of Higher Education</option>
																				<option value="DHA Suffa University	Karachi">DHA Suffa University	Karachi</option>
																				<option value="Gambat Institute of Medical Sciences	Khairpur">	Gambat Institute of Medical Sciences	Khairpur</option>
																				<option value="Greenwich University, Karachi">Greenwich University, Karachi</option>
																				<option value="Hamdard University	Karachi">	Hamdard University	Karachi</option>
																				<option value="Habib University	Karachi	">	Habib University	Karachi	</option>
																				<option value="Indus University	Karachi">	Indus University	Karachi</option>
																				<option value="Iqra University	Karachi"	>Iqra University	Karachi</option>
																				<option value="Isra University	Hyderabad"	>Isra University	Hyderabad</option>
																				<option value="Indus Valley School of Art and Architecture	Karachi">	Indus Valley School of Art and Architecture	Karachi</option>
																				<option value="Institute of Business Management	Karachi"	>Institute of Business Management	Karachi</option>
																				<option value="Institute of Business & Technology	Karachi">	Institute of Business & Technology	Karachi</option>
																				<option value="Institute of Business Administration, Karachi">	Institute of Business Administration, Karachi</option>
																				<option value="Jinnah University for Women	Karachi">	Jinnah University for Women	Karachi</option>
																				<option value="Jinnah Sindh Medical University, Karachi">	Jinnah Sindh Medical University, Karachi</option>
																				<option value="Karachi Institute of Economics and Technology	Karachi">	Karachi Institute of Economics and Technology	Karachi</option>
																				<option value="Karachi School for Business and Leadership	Karachi">	Karachi School for Business and Leadership	Karachi</option>
																				<option value="KASB Institute of Technology	Karachi"	>KASB Institute of Technology	Karachi</option>
																				<option value="Liaquat University of Medical and Health Sciences	Jamshoro">	Liaquat University of Medical and Health Sciences	Jamshoro</option>
																				<option value="Mehran University of Engineering and Technology	Jamshoro">	Mehran University of Engineering and Technology	Jamshoro</option>
																				<option value="Muhammad Ali Jinnah University	Karachi">Muhammad Ali Jinnah University	Karachi</option>
																				<option value="Nazeer Hussain University	Karachi"	>Nazeer Hussain University	Karachi</option>
																				<option value="National University of Computer and Emerging Sciences	Karachi">	National University of Computer and Emerging Sciences	Karachi</option>
																				<option value="NED University of Engineering and Technology	Karachi">	NED University of Engineering and Technology	Karachi</option>
																				<option value="Newports Institute of Communications and Economics	Karachi">	Newports Institute of Communications and Economics	Karachi</option>
																				<option value="Preston Institute of Management Science and Technology	Karachi">	Preston Institute of Management Science and Technology	Karachi	</option>
																				<option value="Preston University	Karachi"	>Preston University	Karachi</option>
																				<option value="Peoples University of Medical & Health Sciences for Women	Nawabshah">	Peoples University of Medical & Health Sciences for Women	Nawabshah</option>
																				<option value="Quaid-e-Awam University of Engineering, Science and Technology	Nawabshah">	Quaid-e-Awam University of Engineering, Science and Technology	Nawabshah</option>
																				<option value="Shah Abdul Latif University	Khairpur">	Shah Abdul Latif University	Khairpur</option>
																				<option value="Shaheed Benazir Bhutto University, Nawabshah">	Shaheed Benazir Bhutto University, Nawabshah</option>
																				<option value="Shaheed Mohtarma Benazir Bhutto Medical University	Larkana">	Shaheed Mohtarma Benazir Bhutto Medical University	Larkana</option>
																				<option value="Shaheed Zulfiqar Ali Bhutto University of Law	Karachi">	Shaheed Zulfiqar Ali Bhutto University of Law	Karachi</option>
																				<option value="Shaheed Benazir Bhutto University of Veterinary and Animal Sciences	Karachi">	Shaheed Benazir Bhutto University of Veterinary and Animal Sciences	Karachi</option>
																				<option value="Shaheed Benazir Bhutto City University	Karachi">	Shaheed Benazir Bhutto City University	Karachi</option>
																				<option value="Shaheed Benazir Bhutto Dewan University	Karachi">	Shaheed Benazir Bhutto Dewan University	Karachi</option>
																				<option value="Shaheed Zulfiqar Ali Bhutto Institute of Science and Technology (SZABIST)	Karachi">Shaheed Zulfiqar Ali Bhutto Institute of Science and Technology (SZABIST)	Karachi</option>
																				<option value="Sindh Madressatul Islam University	Karachi">	Sindh Madressatul Islam University	Karachi</option>
																				<option value="Sindh Medical University	Karachi">	Sindh Medical University	Karachi</option>
																				<option value="Sindh Agriculture University Tandojam">	Sindh Agriculture University	Tandojam</option>
																				<option value="Sindh Madrasatul Islam	Karachi">	Sindh Madrasatul Islam	Karachi</option>
																				<option value="Sindh Institute of Medical Sciences	Karachi"	>Sindh Institute of Medical Sciences	Karachi</option>
																				<option value="Sindh Institute of Management and Technology, Karachi"	>Sindh Institute of Management and Technology, Karachi</option>
																				<option value="Sir Syed University of Engineering and Technology	Karachi">	Sir Syed University of Engineering and Technology	Karachi</option>
																				<option value="Sukkur Institute of Business Administration	Sukkur">Sukkur Institute of Business Administration	Sukkur</option>
																				<option value="Textile Institute of Pakistan	Karachi"	>Textile Institute of Pakistan	Karachi</option>
																				<option value="University of Karachi or HEJ Karachi">University of Karachi or HEJ Karachi</option>
																				<option value="University of Sindh	Jamshoro">	University of Sindh	Jamshoro</option>
																				<option value="Ziauddin University	Karachi">	Ziauddin University	Karachi</option>
																				<option value="Al-Khair University	Mirpur"> Al-Khair University	Mirpur</option>
																				<option value="Mirpur University of Science and Technology, Mirpur"> Mirpur University of Science and Technology, Mirpur</option>
																				<option value="Mirpur University of Science and Technology Bhimber"> Mirpur University of Science and Technology Bhimber</option>
																				<option value="Mohi-ud-Din Islamic University"> Mohi-ud-Din Islamic University</option>
																				<option value="University of Azad Jammu and Kashmir	Muzaffarabad"> University of Azad Jammu and Kashmir	Muzaffarabad</option>
																				<option value="University of Azad Jammu and Kashmir Neelam"> University of Azad Jammu and Kashmir Neelam</option>
																				<option value="University of Azad Jammu and Kashmir Jhelum Valley"> University of Azad Jammu and Kashmir Jhelum Valley</option>
																				<option value="University of Poonch Rawalakot"> University of Poonch Rawalakot</option>
																				<option value="University of Poonch Haveli District"> University of Poonch Haveli District</option>
																				<option value="University of Kotli, AJK">University of Kotli, AJK</option>
																				<option value="Women University of Azad Jammu and Kashmir Bagh"> Women University of Azad Jammu and Kashmir Bagh</option>
																				<option value="University of Management Sciences and Information Technology	Kotli"> University of Management Sciences and Information Technology	Kotli</option>
																				<option value="Baltistan University Skardu">Baltistan University Skardu</option>
																				<option value="Karakoram International University	Gilgit">Karakoram International University	Gilgit</option>
																				<option value="Other">Other</option>
				  </select>
				</div>
			</div>
			
			<div class="clearfix"></div>
			<div class="clearfix"></div>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">Start Date<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="input-group input-append date" id="datePicker">
							<input type="text" style="width:100%;height:30px" autocomplete="off"  id="start_date" name="start_date"/>
							<span class="input-group-addon add-on"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				</div>
			</p>
			
			<div class="clearfix"></div>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">End Date<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="input-group input-append date" id="datePicker1">
							<input type="text"style="width:100%;height:30px" autocomplete="off" id="end_date" name="end_date"/>
							<span class="input-group-addon add-on"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				</div>
			</p>
			<div class="clearfix"></div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Timing<span style="color:red">*</span></label>
				<div class="col-sm-4">
					<div class="demo">
						<p id="datepairExample">
								<input type="text" placeholder="From" id="start_time" name="start_time" style="width:47%;height:30px"  class="time start " onkeypress="return false;"/> to
								<input type="text" placeholder="To" id="end_time" name="end_time" style="width:47%;height:30px" class="time end" onkeypress="return false;"/>
						</p>
						<p id="content6"></p>
					</div>
				</div>
			</div>
			
			<script>
			jQuery(document).ready(function($) {
				$('#datepairExample .time').timepicker({
					'showDuration': true,
					'timeFormat': 'g:i a'
				});
				
				
				$('#datepairExample .date').datepicker({
					'format': 'm/d/yyyy',
					 
					'autoclose': true
				});

				$('#datepairExample').datepair();
			});
			</script>
			<div class="clearfix"></div>
				<p><div class="form-group">
					<label class="col-sm-2 control-label">Repeat<span style="color:red">*</span></label>
						<div class="col-sm-4">
							<input type="radio" name="repeat" checked id="repeat_no" value="No"> Every Week
							<input type="radio" name="repeat" id="repeat_no" value="No"> Every Month
							<input type="radio" name="repeat" id="repeat_yes" value="Yes"> Select Days
						</div>
					</div>
				</p>
			<div class="clearfix"></div>
				<p><div id="week_days" class="form-group">
					<label class="col-sm-2 control-label">Select Day(s)<span style="color:red">*</span></label>
						<div class="col-sm-4">
						
							<input type="checkbox" name="week_days[]" value="0" > Sunday
							<input type="checkbox" name="week_days[]" value="1"> Monday
							<input type="checkbox" name="week_days[]" value="2" > Tuesday
							<input type="checkbox" name="week_days[]" value="3" > Wednesday<br><br>
							<input type="checkbox" name="week_days[]" value="4" > Thursday
							<input type="checkbox" name="week_days[]" value="5" > Friday
							<input type="checkbox" name="week_days[]" value="6" > Saturday
						</div>
					</div>
				</p>
																
					<div class="form-group col-sm-12">
					
						<div class="col-sm-4" align="right">
						
							<input type="submit" id="submit" class="btn-primary btn" value="Submit">
						</div>

					</div>
				</form>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

	<script>
	jQuery(document).ready(function($) {
		var date = new Date();
		date.setDate(date.getDate());
		$('#datePicker')
        .datepicker({
			startDate: date,
            format: 'yyyy-mm-dd'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'date');
        });
		$('#datePicker1')
        .datepicker({
			startDate: date,
            format: 'yyyy-mm-dd'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'date');
        });
		$("#video_conf").on('change', function(e){
			var v = $("#video_conf").val();
			if(v=="event-info"){
				$("#sites").show();
				$("#sites1").show();
			}
			else{
				$("#sites").hide();
				$("#sites1").hide();
			}
		});
		$("#week_days").hide();
		$("#repeat_no").click(function() {
		 	$("#week_days").hide();
		});
		$("#repeat_yes").click(function() {
		 	$("#week_days").show();
		});
		$("#start_time").on('change', function(e){
			$.ajax({
				type: "POST",
				dataType: 'json',
				url: "slots_comp.php",
				data: {
					room_name: $('#room_name').val(),
					start_date: $('#start_date').val(),
					end_date: $('#end_date').val(),
					start_time: $('#start_time').val()
				},
				success:function(data){
					if(data.check==false){
						$('#content6').text(data.mess);
						$('#content6').css('color', 'red');
						$("#submit").attr('disabled', true);
										
					}else{
						$('#content6').text(data.mess);
						$('#content6').css('color', 'green');
						$("#submit").attr('disabled', false);
										
					}
				}
			});
								  
		});
		$("#end_time").on('change', function(e){
			$.ajax({
				type: "POST",
				dataType: 'json',
				url: "slots_comp_end.php",
				data: {
					room_name: $('#room_name').val(),
					start_date: $('#start_date').val(),
					end_date: $('#end_date').val(),
					end_time: $('#end_time').val()
				},
				success:function(data){
					if(data.check==false){
						$('#content6').text(data.mess);
						$('#content6').css('color', 'red');
						$("#submit").attr('disabled', true);
										
					}else{
						$('#content6').text(data.mess);
						$('#content6').css('color', 'green');
						$("#submit").attr('disabled', false);
										
					}
				}
			});
								  
		});
    
	 
	});
	</script>
	<script type="text/javascript" src="components/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="components/underscore/underscore-min.js"></script>
	<script type="text/javascript" src="components/bootstrap2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="components/jstimezonedetect/jstz.min.js"></script>
	<script type="text/javascript" src="js/language/bg-BG.js"></script>
	<script type="text/javascript" src="js/language/nl-NL.js"></script>
	<script type="text/javascript" src="js/language/fr-FR.js"></script>
	<script type="text/javascript" src="js/language/de-DE.js"></script>
	<script type="text/javascript" src="js/language/el-GR.js"></script>
	<script type="text/javascript" src="js/language/it-IT.js"></script>
	<script type="text/javascript" src="js/language/hu-HU.js"></script>
	<script type="text/javascript" src="js/language/pl-PL.js"></script>
	<script type="text/javascript" src="js/language/pt-BR.js"></script>
	<script type="text/javascript" src="js/language/ro-RO.js"></script>
	<script type="text/javascript" src="js/language/es-CO.js"></script>
	<script type="text/javascript" src="js/language/es-MX.js"></script>
	<script type="text/javascript" src="js/language/es-ES.js"></script>
	<script type="text/javascript" src="js/language/es-CL.js"></script>
	<script type="text/javascript" src="js/language/es-DO.js"></script>
	<script type="text/javascript" src="js/language/ru-RU.js"></script>
	<script type="text/javascript" src="js/language/sk-SR.js"></script>
	<script type="text/javascript" src="js/language/sv-SE.js"></script>
	<script type="text/javascript" src="js/language/zh-CN.js"></script>
	<script type="text/javascript" src="js/language/cs-CZ.js"></script>
	<script type="text/javascript" src="js/language/ko-KR.js"></script>
	<script type="text/javascript" src="js/language/zh-TW.js"></script>
	<script type="text/javascript" src="js/language/id-ID.js"></script>
	<script type="text/javascript" src="js/language/th-TH.js"></script>
	<script type="text/javascript" src="js/calendar.js"></script>
	<script type="text/javascript" src="js/app.js"></script>

	<script type="text/javascript">
		var disqus_shortname = 'bootstrapcalendar'; // required: replace example with your forum shortname
		(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
	</script>
</div>
<style>
	.footer-bottom {
    background-color: #222;
    min-height: 30px;
    width: 100%;
}
.copyright {
    color: #fff;
    line-height: 30px;
    min-height: 30px;
    padding: 7px 0;
}
.design {
    color: #fff;
    line-height: 30px;
    min-height: 30px;
    padding: 7px 0;
    text-align: right;
}
.design a {
    color: #fff;
}

	</style>
	<div class="footer-bottom">

	<div class="container">

		<div class="row">

			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

				<div class="copyright">

					© 2018, HEC, All rights reserved

				</div>

			</div>

			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

				<div class="design">

					 <a href="#"><i class="fa fa-calendar-check-o"></i> Add Event </a>

				</div>

			</div>

		</div>

	</div>

</div>

</body>
</html>
