<?php
include("db.php");
if($_SERVER["PHP_SELF"]) {
	if(isset($_POST['event_title']) And isset($_POST['video_conf']) And isset($_POST['start_date']) And isset($_POST['end_date'])){
		$title = $_POST['event_title'];
		$room_name = $_POST['room_name'];
		$video_conf = $_POST['video_conf'];
		$start_date = $_POST['start_date'];
		$start_hour = $_POST['start_hour'];
		$start_min = $_POST['start_min'];
		$start_type = $_POST['start_type'];
		$end_date = $_POST['end_date'];
		$end_hour = $_POST['end_hour'];
		$end_min = $_POST['end_min'];
		$end_type = $_POST['end_type'];
		$start_time = $start_hour.":".$start_min." ".$start_type;
		$start_time = date("H:i:s", strtotime($start_time));
		$end_time = $end_hour.":".$end_min." ".$end_type;
		$end_time = date("H:i:s", strtotime($end_time));
		$start_date_time = date("Y-m-d H:i:s", strtotime($start_date." ".$start_time));
		$end_date_time = date("Y-m-d H:i:s", strtotime($end_date." ".$end_time));
		$str = "INSERT INTO event_booking (Title, start_time,end_time, event_class, room) VALUES 
			('$title','$start_date_time', '$end_date_time' ,'$video_conf', '$room_name')";
			$res=mysqli_query($con, $str);
		
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
      <a class="navbar-brand" href="index.php">Lecture Hall</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#"></a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="form.php"><i class="fa fa-calendar-check-o"></i> Add Event</a></li>
      <li><a href="#"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<h4 style="color:#436E90">Add New Event</h4><hr>
				  
<form action="" class="form-horizontal" method="post">
<input type="hidden" id="room_name" name="room_name" value="Lecture Hall" />

			<div class="form-group">
				<label class="col-sm-2 control-label">Event Title<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<input type="text" name="event_title" required id="event_title"  class="form-control" placeholder="Enter Event Title" width="48">
						<p id="content1"></p>
					</div>
			</div>
			<div class="clearfix"></div>
			<p ><div class="form-group" id="boards">
					<label class="col-sm-2 control-label">Video Conference<span style="color:red;">*</span></label>
					<div class="col-sm-4">
						<select id="video_conf" required name="video_conf" class="form-control dropdown">
							<option value="event-info">Yes</option>
							<option value="event-important">No</option>
						</select>
					</div>
				</div>
			</p>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">Start Date<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="input-group input-append date" id="datePicker">
							<input type="text" class="form-control" id="start_date" name="start_date"/>
							<span class="input-group-addon add-on"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				</div>
			</p>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">End Date<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="input-group input-append date" id="datePicker1">
							<input type="text" class="form-control" id="end_date" name="end_date"/>
							<span class="input-group-addon add-on"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				</div>
			</p>
			<?php
			function generate_options($from,$to,$callback=false)
				{
					$reverse=false;
					
					if($from>$to)
					{
						$tmp=$from;
						$from=$to;
						$to=$tmp;
						
						$reverse=true;
					}
					
					$return_string=array();
					for($i=$from;$i<=$to;$i++)
					{
						$return_string[]='
						<option value="'.$i.'">'.($callback?$callback($i):$i).'</option>
						';
					}
					
					if($reverse)
					{
						$return_string=array_reverse($return_string);
					}
					
					
					return join('',$return_string);
				}


				function callback_month($month)
				{
					return date('M',mktime(0,0,0,$month,1));
				}
			?>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">Start Time<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="col-sm-4">
							<select id="start_hour" required name="start_hour" class="form-control dropdown">
								<option value="">hour:</option><?=generate_options(1,12)?>
							</select> 
						</div>
						<div class="col-sm-4">
							<select id="start_min" required name="start_min" class="form-control dropdown">
								<option value="">minutes:</option><?=generate_options(1,60)?>
							</select> 
						</div>
						<div class="col-sm-4">
							<select id="start_type" required name="start_type" class="form-control dropdown">
								<option value="PM">PM</option>
								<option value="AM">AM</option>
							</select> 
						</div>
					</div>
				</div>
			</p>
			<p><div class="form-group">
				<label class="col-sm-2 control-label">End Time<span style="color:red">*</span></label>
					<div class="col-sm-4">
						<div class="col-sm-4">
							<select id="end_hour" required name="end_hour" class="form-control dropdown">
								<option value="">hour:</option><?=generate_options(1,12)?>
							</select> 
						</div>
						<div class="col-sm-4">
							<select id="end_min" required name="end_min" class="form-control dropdown">
								<option value="">minutes:</option><?=generate_options(1,60)?>
							</select> 
						</div>
						<div class="col-sm-4">
							<select id="end_type" required name="end_type" class="form-control dropdown">
								<option value="PM">PM</option>
								<option value="AM">AM</option>
							</select> 
						</div>
					</div>
				</div>
			</p>
																
					<div class="form-group col-sm-12">
					
						<div class="col-sm-4" align="right">
						
							<input type="submit" id="submit" class="btn btn-default" value="Save">
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
