<?php
session_start();
	if(!isset($_SESSION['Email'])){
		
		header("location:../index.php");
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
			$str = "INSERT INTO event_booking (Title, start_date, start_time, end_time, event_class, room, name, email, job_title) VALUES 
				('$title', '$start_date', '$start_time', '$end_time' ,'$video_conf', '$room_name', '$name', '$email', '$job_title')";
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
	<title>Schedule Events</title>

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
      <a class="navbar-brand" href="data_center.php">Data Center</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#"></a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="data_center_form.php"><i class="fa fa-calendar-check-o"></i> Add Event</a></li>
      <li><a href="../logout.php"><span title="<?php echo $_SESSION['Name'];?>"><i class="fa fa-sign-out"></i> Logout</span></a></li>
    </ul>
  </div>
</nav>
<div class="container">
<h4 style="color:#436E90">Add New Event</h4><hr>
				  
<form action="" class="form-horizontal" method="post">
<input type="hidden" id="room_name" name="room_name" value="Data Center" />
            
			<div class="form-group">
				<label class="col-sm-2 control-label">Event Title<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<input type="text" name="event_title" required id="event_title" style="width:100%;height:30px"   placeholder="Enter Event Title" width="48">
						<p id="content1"></p>
					</div>
			</div>
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
			<p><div class="form-group">
				<label class="col-sm-2 control-label">Start Date<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="input-group input-append date" id="datePicker">
							<input type="text" style="width:100%;height:30px" id="start_date" name="start_date"/>
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
							<input type="text"style="width:100%;height:30px" id="end_date" name="end_date"/>
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
																
					<div class="form-group col-sm-12">
					
						<div class="col-sm-4" align="right">
						
							<input type="submit" id="submit" class="btn" value="Save">
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
				url: "slots_comparison.php",
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
