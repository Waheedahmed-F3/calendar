<?php
include("db.php");
$id = $_REQUEST['id'];
$id1 = $_REQUEST['id1'];
$title = $_REQUEST['title'];
$check = 0;
for($i = $id; $i<=$id1; $i++){
	$sql="DELETE FROM event_booking WHERE id = '$i' And Title='$title'";
	$res=mysqli_query($con, $sql);
	if ($res) {
		$check = 1;
	}
}
if ($check==1) {
		header("location: http://10.88.2.206/HEC_trainings/dashboard.php");
} else {
	echo "Error updating record: " . $con->error;
}
?>

