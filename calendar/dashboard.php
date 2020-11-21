<?php

	session_start();
	if(!isset($_SESSION['Email'])){
		
		header("location:index.php");
	}
include("db.php");
if($_SERVER["PHP_SELF"]) {
	if(isset($_POST['event_title']) And isset($_POST['video_conf']) And isset($_POST['start_date']) And isset($_POST['end_date'])){
		$title = $_POST['event_title'];
		$room_name = $_POST['room_name'];
		$name = $_SESSION['Name'];
		$email = $_SESSION['Email'];
		$job_title = $_SESSION['Title'];
		$video_conf = $_POST['video_conf'];
		$description = $_POST['description'];
		$contact_person = $_POST['contact_person'];
		$contact_email = $_POST['contact_email'];
		$orgnized_department = $_POST['orgnized_department'];
		$presenter_name = $_POST['presenter_name'];
		$presenter_org = $_POST['presenter_org'];
		$orgnized_for = $_POST['orgnized_for'];
		
		
		$start_date = $_POST['start_date'];
		$start_time = $_POST['start_time'];
		$end_date = $_POST['end_date'];
		$end_time = $_POST['end_time'];
		$start_time = date("H:i:s", strtotime($start_time));
		$end_time = date("H:i:s", strtotime($end_time));
		
		$diff=date_diff(date_create($start_date),date_create($end_date));
		$num_days = $diff->format("%d");
		$num_days = $num_days+1;
		for($i=0;$i<$num_days;$i++){
			$start_date = date("Y-m-d", strtotime($start_date));
			$str = "INSERT INTO event_booking (Title, start_date, start_time, end_time, event_class, room, description, contact_person, contact_email, presenter_name, presenter_org, orgnized_department, orgnized_for, name, email, job_title) VALUES 
				('$title', '$start_date', '$start_time', '$end_time' ,'$video_conf', '$room_name','$description', '$contact_person', '$contact_email', '$presenter_name', '$presenter_org', '$orgnized_department', '$orgnized_for', '$name', '$email', '$job_title')";
			$res=mysqli_query($con, $str);
			$start_date = date('Y-m-d', strtotime($start_date. ' +1 days'));
			
		}
		
			//header("location:data_center.php");
		
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
      <a class="navbar-brand" href="">Event Dashboard</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="calendar.php">Home</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
     <li><a href="form.php"><i class="fa fa-calendar-check-o"></i> Add Training</a></li>
      <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="logout.php"><span title="<?php echo $_SESSION['Name'];?>"><i class="fa fa-sign-out"></i> Logout</span></a></li>
    </ul>
  </div>
</nav>
<div class="container">
<h4 style="color:#436E90">Events List</a></h4><hr>
					<div class="table-responsive">
        <table class="table table-hover table-bordered">
          <thead align="center">
		  <tr style="background-color:gray;" align="center">
			<td style="color:white;" >S.No</td>
			<td style="color:white;" >Title</td>
			<td style="color:white;" >Start Date</td>
			<td style="color:white;" >End Date</td>
			<td style="color:white;" >Start Time</td>
			<td style="color:white;" >End Time</td>
			<td style="color:white;" >Video Conference</td>
			<td style="color:white;" >No. Sessions</td>
			<td style="color:white;" >Days</td>
			<td style="color:white;" >Status</td>
			<td style="color:white;" >Action</td>
		  </tr>
            
          </thead>
          <tbody id="myTable">
           <?php
			$email = $_SESSION['Email'];
		    $start=0;
					$end=10;
					if(isset($_GET['page']))
					{
						$page=intval($_GET['page']);
						$start=($page-1)*$end;
					}
					else
					{
						$page=1;
					}
					$sql="SELECT id, Title, start_date, start_time, end_time, event_class, num_sessions,week_days, status
							FROM  event_booking
							WHERE email = '$email'
							GROUP BY(Title)
							LIMIT $start, $end";
					
					$retval = mysqli_query( $con, $sql);
					if(! $retval )
					{
						die('Could not get data: ' . mysql_error());
					}
					$num = 0;

					while($row = mysqli_fetch_array($retval))

					{
						$num = $num + 1;
						$id = $row['id'];
						echo "<tr>"; 
						echo "<td>" .$num ." </td>";
						echo "<td>" .$row['Title']."</td>";
						$title = $row['Title'];
						$query2="SELECT Title FROM event_booking WHERE Title = '$title'"; 
						$result2=mysqli_query($con,$query2);
						$rowcount1=mysqli_num_rows($result2);
						$start_date = $row['start_date'];
						$end_date = $row['start_date'];
						if($rowcount1>0){
							for($i=0;$i<$rowcount1-1;$i++){
								$end_date = date("Y-m-d", strtotime($end_date));
								$end_date = date('Y-m-d', strtotime($end_date. ' +1 days'));
							}
						}
						echo "<td>" .$start_date."</td>";
						echo "<td>" .$end_date."</td>";
						echo "<td>" .$row['start_time']."</td>";
						echo "<td>" .$row['end_time']."</td>";
						$video_con = "No";
						if($row['event_class']=='event-info')
							$video_con = "Yes";
						echo "<td>" .$video_con."</td>";
						echo "<td>" .$rowcount1."</td>";
						$week_days = $row['week_days'];
						echo "<td>" ;
						if (strpos($week_days, "0") !== false){
							echo "Sunday, ";
						}
						if (strpos($week_days, "1") !== false){
							echo "Monday, ";
						}
						if (strpos($week_days, "2") !== false){
							echo "Tuesday, ";
						}
						if (strpos($week_days, "3") !== false){
							echo "Wednesday, ";
						}
						if (strpos($week_days, "4") !== false){
							echo "Thursday, ";
						}
						if (strpos($week_days, "5") !== false){
							echo "Friday, ";
						}
						if (strpos($week_days, "6") !== false){
							echo "Saturday";
						}
						echo "</td>";	
						echo "<td>" .$row['status']."</td>";	
						$id1 = $id + $rowcount1-1;
						echo "<td><a href='edit.php?id=".$row['id']."&id1=".$id1."&title=".$row['Title']."' >Edit</a><br><br>";
						echo "<a href='delete.php?id=".$id ."&id1=".$id1. "&title=".$row['Title']."' >Delete</a></td>";
							
						echo "</tr>";
					}
					echo "</table>";
					$sql1="SELECT DISTINCT(Title) 
							FROM  event_booking";
					$rows=mysqli_num_rows(mysqli_query($con,$sql1));
					$total=ceil($rows/$end);
					
				   echo '<center><nav aria-label="Page navigation example">';
					  echo '<ul class="pagination">';
					  if($page!=1){
							echo "<li class='page-item'><a class='page-link' href='?page=".($page-1)."'>Previous</a></li>";
						}
						if($page==1){
							echo "<li class='page-item disabled' ><span class='page-link'>Previous</span></li>";
						}
						for ($i=1; $i <=$total ; $i++) { 
							//if current building link is current page we don't show link as text else we show
								
								if($i == $page)
									echo "<li class='page-item active'><a class='page-link' href='?page=".$i."'>$i</a></li>";
								else
									echo "<li class='page-item'><a class='page-link' href='?page=".$i."'>$i</a></li>";
						}
						if($page<$total){
							echo "<li class='page-item'><a class='page-link' href='?page=".($page+1)."'>NEXT</a></li>";
						}
						if($page==$total){
							echo "<li class='page-item disabled' ><span class='page-link'>NEXT</span></li>";
						}
						
					  echo '</ul>';
					echo '</nav>';
		   
		   
		   
		   ?>
		   
          </tbody>
        </table>   
      </div>

			
			
																
				
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

	
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
