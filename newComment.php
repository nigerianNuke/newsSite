<!DOCTYPE html>
<html lang="en">
<head>
  <title>New Comment</title>
</head>
<body>


<?php
session_start();
$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}
// echo $_SESSION['id'];
// echo $_SESSION['token'];
// echo $_POST['token'];

if(!hash_equals($_SESSION['token'], $_POST['token'])){
  session_destroy();
	die("Request forgery detected");
}
//echo "here";
$comment= $_POST['contents'];
$userID = $_SESSION['id'];
$storyID = $_POST['storyID'];

$stmt=$mysqli->prepare("insert into comment (user_id, story_id, content) values (?, ?, ?);");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}


$stmt->bind_param('iis', $userID , $storyID, $comment);

$stmt->execute();

$stmt->close();

header("Location: account.php");
?>

<footer>

</footer>
</body>


</html>
