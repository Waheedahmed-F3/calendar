<?php 
include("db.php");
//http://stackoverflow.com/a/2467974
$query="SELECT * FROM event_booking WHERE room = 'Lecture Hall'"; 
$result=mysqli_query($con,$query);
//store the entire response
$response = array();
//the array that will hold the titles and links
$posts = array();
while($row= mysqli_fetch_array($result)) //mysql_fetch_array($sql)
{ 
	$id=$row['id']; 
	$title=$row['Title']; 
	$class=$row['event_class']; 
	date_default_timezone_set("Asia/Karachi");
	$start_time=strtotime($row['start_time'])."000"; 
	$end_time=strtotime($row['end_time'])."000"; 
	//each item from the rows go in their respective vars and into the posts array
	$posts[] = array('id'=>$id, 'title'=> $title,'url'=>'', 'class'=> $class, 'start'=>$start_time, 'end'=>$end_time); 
} 
//the posts array goes into the response
$response['success'] = 1;
$response['result'] = $posts;
//creates the file
$fp = fopen('events.json.php', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
/* Final Output
{"posts": [
  {
    "title":"output_from_table",
    "url":"output_from_table"
  },  
  ...
]}
*/
?> 