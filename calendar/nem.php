<?php
header('Location:  http://events.hec.gov.pk/');
/*
Template Name:Apply For Training
*/
session_start();
?>

<?php
$mesg = "";
if($_SERVER["PHP_SELF"]) {
	$action = $_POST['action'];
	if(isset($_POST['user_id']) And isset($_POST['event_venue'])){
		$action = $_POST['action'];
			$user_id = $_POST['user_id'];
			$user = get_user_by( 'ID', $user_id );
			$venue = $_POST['event_venue'];
			$title = $_POST['title'];
			date_default_timezone_set("Asia/Karachi");
			$date1 = date("Y-m-d");
			$current = date("h:i a");
			if($action == 'change'){
				$table_name = $wpdb->prefix . 'develop_training';
				$wpdb->query("UPDATE $table_name SET venue='$venue' WHERE user_id = '$user_id' And Title = '$title'");
				
							$header = "MIME-Version: 1.0\n";
							$header .= "Content-Type: text/html; charset=utf-8\n";
							$header .= "From:events@hec.gov.pk";
							$message = "You have changed the venue for the Training. <br>";
							$message .= "Training Name:".$title."<br>";
							$message .= "Venue:".$venue."<br>";
							$message .= "If you have any query contact   events@hec.gov.pk<br>";
							$subject = "Events Registration Portal: Change Venue of Training";
							$to = $user->user_email;
							if( wp_mail($to, $subject, $message, $header)) {
								$mesg = "<span style='color:green'> You have successfully changed the venue for training.</span><br>";;
							}
			}else{
				$check1 = $_POST['check1'];
				$table_name = $wpdb->prefix . 'develop_training';
				$wpdb->insert( $table_name, array(
							'user_id' => $user_id,
							'Title' => $title,
							'training' => '',
							'venue' => $venue,
							'unemployment' => '',
							'check1' => $check1,
							'reg_date'=> $date1,
							'reg_time'=>$current						
					));					
			}
	}
	if($_REQUEST['action'] == 'delete'){
		$id = $_REQUEST['id'];
		$title = $_REQUEST['title'];
		$wpdb->query( "DELETE FROM wp_develop_training WHERE user_id =  '$id' And Title='$title'" );
		$mesg = "<span style='color:green'> you are successfully delete registered training.</span><br>";;
	}
	if($_REQUEST['action'] == 'done'){
		$id = $_REQUEST['id'];
		$user = get_user_by( 'ID', $id );
		$row = $wpdb->get_results("SELECT * FROM wp_develop_training WHERE user_id = '$id'");
		$header = "MIME-Version: 1.0\n";
		$header .= "Content-Type: text/html; charset=utf-8\n";
		$header .= "From:events@hec.gov.pk";
		$message = "Thank you for Registering in the Training(s). <br><br>";
		$num = 0;
		foreach($row as $row){
			$num = $num +1;
			$message .= $num."-&nbsp;&nbsp;&nbsp;Training Name:".$row->Title."<br>";
			$message .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Venue:".$row->venue."<br><br>";
		}
		$message .= "If you you want to change the training or venue of training please login using username and password send on your email.<br>";
		$message .= "If you have any query contact   events@hec.gov.pk<br>";
		$subject = "Events Registration Portal: Register for Training";
		$to = $user->user_email;
		if($num>0){
			if( wp_mail($to, $subject, $message, $header)) {
				header('Location:  /index.php/event/job-market-skill/');
			}
		}
		else{
			header('Location:  /index.php/event/job-market-skill/');
		}
	}
} 
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

<?php get_header();
?>
<style>
	.form-control {
  height: 26px;
  padding: 2px 12px;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
}
.box-content {
    position: relative;
    padding: 15px;
    background: #FBFBF0;
}
</style>
	<div id="container" >
		<div id="content" style="margin-left:10%">
			
			<?php
			
				if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
					<h1 class="entry-title"><?php //the_title(); ?></h1>
					<div class="entry-content">
								<?php if(isset($hasError)){?>
									<div id="wpcrl-register-alert" class="alert alert-danger" role="alert" >
											<?php echo $emailerror.'<br>';
											echo $usererror;

											?>
									</div>
								<?php }
								
								
								?>
							<div class="box">
					<style>
								.stepwizard-step p {
									margin-top: 10px;
								}

								.stepwizard-row {
									display: table-row;
								}

								.stepwizard {
									display: table;
									width: 100%;
									position: relative;
								}

								.stepwizard-step button[disabled] {
									opacity: 1 !important;
									filter: alpha(opacity=100) !important;
								}

								.stepwizard-row:before {
									top: 14px;
									bottom: 0;
									position: absolute;
									content: " ";
									width: 100%;
									height: 1px;
									background-color: #ccc;
									z-order: 0;

								}

								.stepwizard-step {
									display: table-cell;
									text-align: center;
									position: relative;
								}

								.btn-circle {
								  width: 30px;
								  height: 30px;
								  text-align: center;
								  padding: 6px 0;
								  font-size: 12px;
								  line-height: 1.428571429;
								  border-radius: 15px;
								}
							</style>
					<div class="box-content col-sm-10" >
					<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
					
										<h3 class="section-title"><?php _e( 'Trainings List', 'wp-event-manager' );?></h3>
										<?php 
										echo $mesg;
											$user_id = $_REQUEST['id'];
											$row = $wpdb->get_results("SELECT * FROM wp_develop_training WHERE user_id = '$user_id'");
											$num = 0;
											foreach($row as $row){
												$num = $num +1;
												echo "<span style='color:green'> ".$row->Title.",</span>";
											}
											if($num==1)
												echo "<span style='color:red'><br> You may register for another training.</span><br>";
											
											$row = $wpdb->get_results("SELECT distinct name FROM wp_skill_set");
											$num = 1;
											echo "<table class='table'>";
											echo "<tr><th>S.No </th><th>Training Name</th><th>Action</th></tr>";
											foreach ( $row as $row ){
												$posttitle = $row->name;
												$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "'" );
												$user_id = $_REQUEST['id'] ;
												echo "<tr><td><strong>".$num."</td><td>".$posttitle."</strong></td>
												
												<td>";
												
												$row1 = $wpdb->get_var("SELECT Count(*) FROM wp_develop_training WHERE user_id = '$user_id' And Title='$posttitle'");
												if($row1>0){
													echo "<button onclick=\"document.getElementById('id0".$num."').style.display='block'\" class=\"w3-button w3-green w3-large\">Change Venue</button> ";
													$id = $_REQUEST['id'];
													echo " <a href='/index.php/apply-for-training/?id=".$id."&action=delete&title=".$posttitle."'><button class=\"w3-button w3-green w3-large\">Delete</button></a>";
												}else{
																$rows = $wpdb->get_var("SELECT Count(*) FROM wp_develop_training WHERE user_id = '$user_id'");
																if($rows>1){
																	echo "<button disabled onclick=\"document.getElementById('id0".$num."').style.display='block'\" class=\"w3-button w3-green w3-large\">Apply</button>";
																}else
																	echo "<button onclick=\"document.getElementById('id0".$num."').style.display='block'\" class=\"w3-button w3-green w3-large\">Apply</button>";
												}
												
												echo "</td><tr>";
												

												 echo '<div id="id0'.$num.'" class="w3-modal">
													<div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

													  <div class="w3-center"><br>
														<span onclick="document.getElementById(\'id0'.$num.'\').style.display=\'none\'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
													  </div>';
														?>
												 <form class="w3-container" method="POST" action="/index.php/apply-for-training/?id=<?php echo  $_REQUEST['id'];?>">
														
														
													<?php echo '<center><div style="width:80%;" class="w3-section">';
														  global $wpdb;
															$row = $wpdb->get_results("SELECT * FROM wp_skill_set WHERE name = '$posttitle'");
															?>
															<input type="hidden"  name="title" value="<?php echo $posttitle;?>" >
															<?php $user_id = $_REQUEST['id'];
															?>
															<input type="hidden"  name="user_id" value="<?php echo $user_id;?>" >
															<h3 class="section-title"><?php _e($posttitle , 'wp-event-manager' );?></h3>
															<?php 
															$row1 = $wpdb->get_var("SELECT Count(*) FROM wp_develop_training WHERE user_id = '$user_id' And Title='$posttitle'");
															if($row1>0){
																echo '<input type="hidden"  name="action" value="change" >';
																$row2 = $wpdb->get_row("SELECT venue FROM wp_develop_training WHERE user_id = '$user_id' And Title='$posttitle'");
																		
																echo '<br><label>Select venue.</label>																
																<select name="event_venue" id="event_venue" style="width:300px;" required="true">';
																foreach ( $row as $row ){
																		$v = $row->orgnization;
																		$vn = $row2->venue;
																		?>
																		<option <?php if($v==$vn) { echo "selected='selected'"; } ?> value="<?php echo htmlspecialchars($v);?>"><?php echo $row->orgnization; ?></option>
															<?php 	}
																echo '</select><br>
															  <button class="w3-button w3-green w3-section w3-padding" type="submit">Change</button>';
															
															}else{
																echo '<input type="hidden"  name="action" value="save" >';
																echo '<br><label>Select venue.</label>
																
																<select name="event_venue" id="event_venue" style="width:300px;" required="true">';
																foreach ( $row as $row ){
																		$v = $row->orgnization;
																		echo "<option value=\"".htmlspecialchars($v)."\">".$row->orgnization."</option>";
																}
																echo '</select><br>';
																echo '<br><input type="checkbox" required name="check1" value="If the information provided in the form is found incorrect fictitious mistreated, the cost of the training will be recovered from the trainee.."> 
																		<strong> If the information provided in the form is found incorrect fictitious mistreated, the cost of the training will be recovered from the trainee.. </strong><br>';
																	echo '<button class="w3-button w3-green w3-section w3-padding" type="submit">Apply</button>';
															}
														echo '</div>
														</center>
													  </form>

													  <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
														<button onclick="document.getElementById(\'id0'.$num.'\').style.display=\'none\'" type="button" class="w3-button w3-red">Cancel</button>
													  </div>
														
													</div>
												  </div>';
												  $num=$num+1;
											}
											echo "</table>";
					
					?>
					<div class="clearfix"></div>
					<div class="form-group">
						<center><?php 
						$id =$_REQUEST['id'];
						echo "<a href='/index.php/apply-for-training/?id=".$id."&action=done'>
						<input type='button' style='width:100px;' class='btn btn-default' value='Done'></a>";?>
					</center>
					</div>
				</div>
			</div>
			
			

 

						
						
						<script>
				  jQuery(document).ready(function($) {
					  var check = 0;
						$('#emailcode').hide();
						$('#email_code').hide();
						$('#verify_email').hide();
						// Show the login dialog box on click
						$('#phone_verify_code').hide();
							$('#phone_code').hide();
							$('#verify_phone').hide();
							$('#bal').hide();
							$('#kpk').hide();
							$('#punjab').hide();
							$('#sindh').hide();
							$('#ajk').hide();
							$('#gilgit').hide();
							$('#biomedicine').hide();
							$('#engineering').hide();
							$('#social').hide();
							$('#physical').hide();
							$('#business').hide();
							$('#animal').hide();
							
						$('#discipline').on('change', function(e){
							var vl = $('#discipline').val();
							if(vl=='Biological'){
								$('#check').hide();
								$('#biomedicine').show();
								$('#engineering').hide();
								$('#social').hide();
								$('#physical').hide();
								$('#business').hide();
								$('#animal').hide();
							}
							else if(vl=='Business'){
								$('#check').hide();
								$('#biomedicine').hide();
								$('#engineering').hide();
								$('#social').hide();
								$('#physical').hide();
								$('#business').show();
								$('#animal').hide();
							}
							else if(vl=='Engineering'){
								$('#check').hide();
								$('#biomedicine').hide();
								$('#engineering').show();
								$('#social').hide();
								$('#physical').hide();
								$('#business').hide();
								$('#animal').hide();
							}
							else if(vl=='Social'){
								$('#check').hide();
								$('#biomedicine').hide();
								$('#engineering').hide();
								$('#social').show();
								$('#physical').hide();
								$('#business').hide();
								$('#animal').hide();
							}
							else if(vl=='Physical'){
								$('#check').hide();
								$('#biomedicine').hide();
								$('#engineering').hide();
								$('#social').hide();
								$('#physical').show();
								$('#business').hide();
								$('#animal').hide();
							}
							else if(vl=='Agriculture'){
								$('#check').hide();
								$('#biomedicine').hide();
								$('#engineering').hide();
								$('#social').hide();
								$('#physical').hide();
								$('#business').hide();
								$('#animal').show();
							}
						});
							var strength = 0;
							$('#password1').on('keyup',function(e){
								$("#submit").attr('disabled','disabled');
								var password = $('form#login #password1').val();
								
								if (password.length < 6) {
									$('p.status_pass').css('color', 'red');
									$('form#login p.status_pass').text("Warning! passwords to short");
								}
									//length is ok, lets continue. //if length is 8 characters or more, increase strength value 
								if (password.length > 7) strength += 1 
								//if password contains both lower and uppercase characters, increase strength value 
								if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1 
								//if it has numbers and characters, increase strength value 
								if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1 
								//if it has one special character, increase strength value 
								if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1 
								//if it has two special characters, increase strength value 
								if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1 
								//now we have calculated strength value, we can return messages 
								//if value is less than 2 
								if (strength < 2 ) {
									$('p.status_pass').css('color', 'red');
									$('form#login p.status_pass').text("");
									$('form#login p.status_pass').text("Weak");
								}
									else if (strength == 2 ) { 
									$('p.status_pass').css('color', 'green');
									$('form#login p.status_pass').text("");
									$('form#login p.status_pass').text("Good");
									}
									else { 
									$('p.status_pass').css('color', 'green');
									$('form#login p.status_pass').text("");
									$('form#login p.status_pass').text("Strong");
									}
								
							});
							$('#password2').on('keyup',function(e){
								var p1 = $('form#login #password1').val();
								var p2 = $('form#login #password2').val();
								if(p1!=p2){
									$('p.status_pass1').css('color', 'red');
									$('form#login p.status_pass1').text("Warning! passwords are not same");
									$("#submit").attr('disabled','disabled');
								}
								else{
									$('p.status_pass1').css('color', 'green');
									$('form#login p.status_pass1').text("Correct");
									 $("#submit").removeAttr("disabled");
								}
								
							});
							$('#other').hide();
							$('#isb').on('change',function(e){
								var v = $('#isb').val();
								if(v=='Other'){
									$('#other').show();
									$("#other").prop('required',true);
								}
								else{
									$('#other').hide();
									$("#other").prop('required',false);
								}
							});
							$('#bal').on('change',function(e){
								var v = $('#bal').val();
								if(v=='Other'){
									$('#other').show();
									$("#other").prop('required',true);
								}
								else{
									$('#other').hide();
									
								$("#other").prop('required',false);
								}
							});
							$('#kpk').on('change',function(e){
								var v = $('#kpk').val();
								if(v=='Other'){
									$('#other').show();
									$("#other").prop('required',true);
								}
								else{
									$('#other').hide();
									$("#other").prop('required',false);
								}
							});
							$('#punjab').on('change',function(e){
								var v = $('#punjab').val();
								if(v=='Other'){
									$('#other').show();
									$("#other").prop('required',true);
								}
								else{
									$('#other').hide();
									$("#other").prop('required',false);
								}
							});
							$('#sindh').on('change',function(e){
								var v = $('#sindh').val();
								if(v=='Other'){
									$('#other').show();
									$("#other").prop('required',true);
								}
								else{
									$('#other').hide();
									$("#other").prop('required',false);
								}
							});
							$('#ajk').on('change',function(e){
								var v = $('#ajk').val();
								if(v=='Other'){
									$('#other').show();
									$("#other").prop('required',true);
								}
								else{
									$('#other').hide();
									$("#other").prop('required',false);
								}
							});
							$('#gilgit').on('change',function(e){
								var v = $('#gilgit').val();
								if(v=='Other'){
									$('#other').show();
									$("#other").prop('required',true);
								}
								else{
									$('#other').hide();
									$("#other").prop('required',false);
								}
							});
						$('#region').on('change',function(e){
							$('#other').hide();
							var v = $('#region').val();
							if(v=='ict'){
								$('#bal').hide();
								$('#kpk').hide();
								$('#punjab').hide();
								$('#sindh').hide();
								$('#ajk').hide();
								$('#gilgit').hide();
								$('#isb').show();
							}
							else if(v=='bloch'){
								$('#bal').show();
								$('#isb').hide();
								$('#punjab').hide();
								$('#sindh').hide();
								$('#ajk').hide();
								$('#kpk').hide();
							}
							else if(v=='kpk'){
								$('#kpk').show();
								$('#punjab').hide();
								$('#sindh').hide();
								$('#gilgit').hide();
								$('#isb').hide();
								$('#ajk').hide();
								$('#bal').hide();
							}
							else if(v=='punjab'){
								$('#kpk').hide();
								$('#punjab').show();
								$('#sindh').hide();
								$('#isb').hide();
								$('#gilgit').hide();
								$('#ajk').hide();
								$('#bal').hide();
							}
							else if(v=='sindh'){
								$('#kpk').hide();
								$('#sindh').show();
								$('#punjab').hide();
								$('#isb').hide();
								$('#gilgit').hide();
								$('#ajk').hide();
								$('#bal').hide();
							}
							else if(v=='ajk'){
								$('#kpk').hide();
								$('#sindh').hide();
								$('#punjab').hide();
								$('#isb').hide();
								$('#gilgit').hide();
								$('#ajk').show();
								$('#bal').hide();
							}
							else if(v=='gilgit'){
								$('#kpk').hide();
								$('#sindh').hide();
								$('#punjab').hide();
								$('#isb').hide();
								$('#gilgit').show();
								$('#ajk').hide();
								$('#bal').hide();
							}
						});

							// Perform AJAX login on form submit
						$('#username').on('keyup change',function(e){
							var username = $('form#login #username').val();
								
								if (username.length == 0) {
									$('p.statususer').css('color', 'red');
									$('form#login p.statususer').text("Username is required field");
								}else if (username.length < 6) {
									$('p.statususer').css('color', 'red');
									$('form#login p.statususer').text("Warning! Username is to short");
								}else{
										$.ajax({
											type: 'POST',
											dataType: 'json',
											url: abc.ajaxurl,
											data: { 
												'action': 'checkusername', //calls wp_ajax_nopriv_ajaxlogin
												'username': $('form#login #username').val()
												},
											success: function(data){
												$('form#login p.statususer').text(data.message);
												if (data.check == true){
													$('p.statususer').css('color', 'red');
												}
												else{
													$('p.statususer').css('color', 'green');
													$('form#login p.statususer').text("Correct");
												}
											}
										});
										
									}
							
						});
						setInterval(function() {
							 check = 0;
						}, 60000*5);
						$('#abc').on('click', function(e){
							//if(check==0){
								$('form#login p.status').show().text(abc.loadingmessage);
								$.ajax({
									type: 'POST',
									dataType: 'json',
									url: abc.ajaxurl,
									data: { 
										'action': 'sendmail', //calls wp_ajax_nopriv_ajaxlogin
										'email': $('form#login #email').val()
										},
									success: function(data){
										$('form#login p.status').text(data.message);
										if (data.check == true){
											$('#emailcode').show();
											$('#email_code').show();
											$('#verify_email').show();
											$('#abc').html('Resend Code');
											$('p.status').css('color', 'green');
											check = 1;
										}
										else{
											$('p.status').css('color', 'red');
										}
									}
								});
							//}else{
							//	$('form#login p.status').text("Warning! You can't resend code with in 5 minitus");
							//	$('p.status').css('color', 'red');
							//}
							e.preventDefault();
						});
						$('#verify_email').on('click', function(e){
							$('form#login p.status').show().text(abc.loadingmessage);
							$.ajax({
								type: 'POST',
								dataType: 'json',
								url: abc.ajaxurl,
								data: { 
									'action': 'verifymailcode', //calls wp_ajax_nopriv_ajaxlogin
									'email_code': $('form#login #email_code').val()
									},
								success: function(data){
									$('form#login p.status').text(data.message);
									 if (data.check == true){
										 $("#email").attr('readonly','readonly');
										 $("#abc").attr('disabled','disabled');
										 $("#email_code").attr('readonly','readonly');
										 $("#verify_email").attr('disabled','disabled');
										 $("#phone").removeAttr("disabled");
										 $("#send_phone_code").removeAttr("disabled");
									 }

								}
							});
							e.preventDefault();
						});
						
						
						$('#send_phone_code').on('click', function(e){
							
							$('form#login p.status1').show().text(abc.loadingmessage);
							$.ajax({
								type: 'POST',
								dataType: 'json',
								url: abc.ajaxurl,
								data: { 
									'action': 'sendphonecode', //calls wp_ajax_nopriv_ajaxlogin
									'phone': $('form#login #phone').val()
									},
								success: function(data){
									if(data.check==true){
										$('form#login p.status1').text(data.message);
										$('p.status1').css('color', 'green');
										//$('form#login p.status1').text("5 digit code send on your number");
										$('#phone_verify_code').show();
										$('#phone_code').show();
										$('#verify_phone').show();
										$('#send_phone_code').html('Resend Code');
									}
									else if(data.check==false){
										$('form#login p.status1').text(data.message);
										$('p.status1').css('color', 'red');
										//$('form#login p.status1').text("5 digit code send on your number");
										$('#phone_verify_code').hide();
										$('#phone_code').hide();
										$('#verify_phone').hide();
									}
								},
								error:function(data){
									$('form#login p.status1').text(data.message);
									$('p.status1').css('color', 'green');
									$('form#login p.status1').text("5 digit code send on your number");
									$('#phone_verify_code').show();
									$('#phone_code').show();
									$('#verify_phone').show();
										$('#send_phone_code').html('Resend Code');
								}
							});
							e.preventDefault();
						});
						
						$('#verify_phone').on('click', function(e){
							$('form#login p.status1').show().text(abc.loadingmessage);
							$.ajax({
								type: 'POST',
								dataType: 'json',
								url: abc.ajaxurl,
								data: { 
									'action': 'verifyphonecode', //calls wp_ajax_nopriv_ajaxlogin
									'phone_code': $('form#login #phone_code').val()
									},
								success: function(data){
									$('form#login p.status1').text(data.message);
									 if (data.check == true){
										 $("#phone").attr('readonly','readonly');
										 $("#send_phone_code").attr('disabled','disabled');
										 $("#phone_code").attr('readonly','readonly');
										 $("#verify_phone").attr('disabled','disabled');
										 $('#submit').removeAttr("disabled");
									 }

								}
							});
							e.preventDefault();
						});
						

					});
					
				  
				</script>
						<div class="clearfix"></div>
					<?php  ?>
					</div><!-- .entry-content -->
				</div><!-- .post -->

					<?php endwhile; endif; 
					
			
			//	header('Location: http://veppportal.hec.gov.pk/index.php/event-dashboard/');
			
				?>
		</div><!-- #content -->
	</div><!-- #container -->
<?php get_footer(); ?>