<?php
include("db.php");
$room_name = $_POST['room_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$end_time = $_POST['end_time'];
$end_time = date("H:i:s", strtotime($end_time));
$diff=date_diff(date_create($start_date),date_create($end_date));
$num_days = $diff->format("%d");
$num_days = $num_days+1;
for($i=0;$i<$num_days;$i++){
	$sql="SELECT start_time, end_time FROM  event_booking WHERE start_date = '$start_date' And room = '$room_name'";
	$retval = mysqli_query( $con, $sql);
	while($row = mysqli_fetch_array($retval)){
		$start_t = date("H:i:s", strtotime($row['start_time']));
		$end_t = date("H:i:s", strtotime($row['end_time']));
		if($end_time>=$start_t And $end_time<=$end_t){
			echo json_encode(array('check'=>false,'mess' => "Room already reserved From ".$start_t." To ".$end_t));
			die();	
		}
	}
	
	$start_date = date('Y-m-d', strtotime($start_date. ' +1 days'));
}	
echo json_encode(array('check'=>true,'mess' => ""));
			
die();	
?>  
