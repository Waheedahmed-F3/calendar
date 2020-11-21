<?php 

session_start();
	if(!isset($_SESSION['Email'])){
		
		header("location:../index.php");
	}
?>