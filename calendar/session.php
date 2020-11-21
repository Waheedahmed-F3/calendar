<?php
	session_start();
	
	if(isset($_REQUEST['name']) And isset($_REQUEST['email']) And isset($_REQUEST['title'])){
		$_SESSION['Name'] = $_REQUEST['name'];
		$_SESSION['Email'] = $_REQUEST['email'];
		$_SESSION['Title'] = $_REQUEST['title'];
		header("location: http://events.hec.gov.pk/calendar/calendar.php");
		
	}else{
		$_SESSION["error"] = "<span style='color:red'>Authentication faild! wrong username or pasword.";
		header("location: http://events.hec.gov.pk/calendar/index.php");
	}
	
	



 ?>