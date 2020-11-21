<?php
session_start();
	if(!isset($_SESSION['Email'])){
		
		header("location:index.php");
	}
include("db.php");
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
<div style="height:550px">
<br><br><br>
<center><h4 style="color:green">Thanks! Your event added successfully and display on calendar after admin approvel.</h4></center><hr>
<center><span style="color:green">If you want to add more event click.</span><a href="http://events.hec.gov.pk/calendar/form.php" style="color:red;">Here</a></center><hr>
</div>

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
