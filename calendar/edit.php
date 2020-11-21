<?php
$id = $_REQUEST['id'];
$id1 = $_REQUEST['id1'];
$title = $_REQUEST['title'];
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
		$id = 0;
		$num = -1;
		for($i=0;$i<$num_days;$i++){
			$num = $num +1;
			$start_date = date("Y-m-d", strtotime($start_date));
			$str = "UPDATE event_booking SET Title='".$_POST['title']."', event_class='".$_POST['video_conf']."',description='".$_POST['description']."', start_date='".$start_date."', start_time='".$start_time."', end_time='".$end_time."',contact_person='".$_POST['contact_person']."',contact_email='".$contact_email."',orgnized_department='".$orgnized_department."', orgnized_for='".$orgnized_for ."'WHERE id='".$_REQUEST['id']."'And Title = '".$_REQUEST['title'];
	
			$res=mysqli_query($con, $str);
			$id = mysqli_insert_id($con);
			$start_date = date('Y-m-d', strtotime($start_date. ' +1 days'));
			
		}
		
		header("location: http://10.88.2.206/HEC_trainings/calendar.php");
		
	}
}	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Schedule Trainings</title>

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
      <a class="navbar-brand" href="calendar.php">Schedule Trainings</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#"></a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="form.php"><i class="fa fa-calendar-check-o"></i> Add Training</a></li>
      <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="logout.php"><span title="<?php echo $_SESSION['Name'];?>"><i class="fa fa-sign-out"></i> Logout</span></a></li>
    </ul>
  </div>
</nav>
<div class="container">
<h4 style="color:#436E90">Edit Training</h4><hr>
				  
<form action="" class="form-horizontal" method="post">
<input type="hidden" id="room_name" name="room_name" value="Lecture Hall" />
            <?php 
				$query="SELECT * FROM  event_booking WHERE id='$id' And Title = '$title'";
				$result=mysqli_query($con, $query);
				$row=mysqli_fetch_array($result);
			?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Event Title<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<input type="text" name="event_title" required id="event_title" style="width:100%;height:30px"  value="<?php echo $row['Title'];?>" width="48">
						<p id="content1"></p>
					</div>
			</div>
			<div class="clearfix"></div>
			<p ><div class="form-group" id="boards">
					<label class="col-sm-2 control-label">Video Conference<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<?php $video_conf = $row['event_class'];?>		
						<select id="video_conf" required name="video_conf" style="width:100%;height:30px"  class="dropdown">
							<option  <?php if($video_conf=="event-info") { echo "selected='selected'"; } ?> value="event-info">Yes</option>
							<option <?php if($video_conf=="event-important") { echo "selected='selected'"; } ?> value="event-important">No</option>
						</select>
					</div>
				</div>
			</p>
			
			<div class="clearfix"></div>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">Start Date<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="input-group input-append date" id="datePicker">
							<input type="text" style="width:100%;height:30px" id="start_date" value="<?php echo $row['start_date'];?>" name="start_date"/>
							<span class="input-group-addon add-on"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				</div>
			</p>
			<div class="clearfix"></div>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">End Date<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<?php
							$title = $row['Title'];
							$query2="SELECT Title FROM event_booking WHERE Title = '$title'"; 
							$result2=mysqli_query($con,$query2);
							$rowcount1=mysqli_num_rows($result2);
							$end_date = $row['start_date'];
							if($rowcount1>0){
								for($i=0;$i<$rowcount1-1;$i++){
									$end_date = date("Y-m-d", strtotime($end_date));
									$end_date = date('Y-m-d', strtotime($end_date. ' +1 days'));
								}
							}
						?>
						<div class="input-group input-append date" id="datePicker1">
							<input type="text"style="width:100%;height:30px" id="end_date" value="<?php echo $end_date;?>" name="end_date"/>
							<span class="input-group-addon add-on"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				</div>
			</p>
			<div class="clearfix"></div>
				<p><div class="form-group">
					<label class="col-sm-2 control-label">Select Day(s)<span style="color:red">*</span></label>
						<div class="col-sm-4">
							<?php $week_days = $row['week_days'];?>		
						
							<input type="checkbox" name="week_days[]" <?php if (strpos($week_days, "0") !== false){echo 'checked';} ?> value="0" > Sunday
							<input type="checkbox" name="week_days[]" <?php if (strpos($week_days, "1") !== false){echo 'checked';} ?> value="1"> Monday
							<input type="checkbox" name="week_days[]" <?php if (strpos($week_days, "2") !== false){echo 'checked';} ?> value="2" > Tuesday
							<input type="checkbox" name="week_days[]" <?php if (strpos($week_days, "3") !== false){echo 'checked';} ?> value="3" > Wednesday<br><br>
							<input type="checkbox" name="week_days[]" <?php if (strpos($week_days, "4") !== false){echo 'checked';} ?> value="4" > Thursday
							<input type="checkbox" name="week_days[]" <?php if (strpos($week_days, "5") !== false){echo 'checked';} ?> value="5" > Friday
							<input type="checkbox" name="week_days[]" <?php if (strpos($week_days, "6") !== false){echo 'checked';} ?> value="6" > Saturday
						</div>
					</div>
				</p>
			<div class="form-group">
				<label class="col-sm-2 control-label">Number of Session<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<input type="number" name="num_session" required id="num_session" style="width:100%;height:30px" value="<?php echo $row['num_sessions'];?>"  autocomplete="off" width="48">
						<p id="content1"></p>
					</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Timing<span style="color:red">*</span></label>
				<div class="col-sm-4">
					<div class="demo">
						<p id="datepairExample">
								<input type="text" placeholder="From" id="start_time" name="start_time" value="<?php echo $row['start_time'];?>" style="width:47%;height:30px"  class="time start " onkeypress="return false;"/> to
								<input type="text" placeholder="To" id="end_time" name="end_time" value="<?php echo $row['end_time'];?>"  style="width:47%;height:30px" class="time end" onkeypress="return false;"/>
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
																
					<div class="form-group col-sm-12">
					
						<div class="col-sm-4" align="right">
						
							<input type="submit" id="submit" class="btn" value="Save">
						</div>
						<div class="col-sm-4" align="right">
						
							<input type="button" class="btn" value="Back" onclick="window.location.href='calendar.php'" />
						</div>

					</div>
				</form>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

	<script>
	jQuery(document).ready(function($) {
		$('#datePicker')
        .datepicker({
            format: 'yyyy-mm-dd'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'date');
        });
		$('#datePicker1')
        .datepicker({
            format: 'yyyy-mm-dd'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'date');
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
