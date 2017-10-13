<?php
session_start();

$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}


if(isset($_POST['favorite']) && hash_equals($_SESSION['token'], $_POST['token']))
{
  $storyID = $_SESSION['storyID'];
  $userId = $_SESSION['id'];
  $stmt=$mysqli->prepare("insert into favorite (user_id, story_id) values (?,?);");

  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }

  //echo $userId;
  //echo $storyID;

  $stmt->bind_param('ii',$userId,$storyID);

  $stmt->execute();

  $stmt->close();
  header("Location:account.php"); //refresh current page
}

if(isset($_POST['like']) && hash_equals($_SESSION['token'], $_POST['token']))
{
  $storyID = $_SESSION['storyID'];
  //$userId = $_SESSION['id'];
  $upvotes = $_POST['upvote'] + 1;
  $_SESSION['upvote'] = $upvotes;
  $stmt=$mysqli->prepare("update story SET upVote = ? WHERE story_id = ?;");

  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }

  $stmt->bind_param('ii',$upvotes,$storyID);

  $stmt->execute();

  $stmt->close();

  header("Location:main.php"); //refresh current page
}

if(isset($_POST['dislike']) && hash_equals($_SESSION['token'], $_POST['token']))
{
  $storyID = $_SESSION['storyID'];
  //$userId = $_SESSION['id'];
  $downvotes = $_POST['downvote'] + 1;
  $_SESSION['downvote'] = $downvotes;
  $stmt=$mysqli->prepare("update story SET downVote = ? WHERE story_id = ?;");

  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }

  //echo $userId;
  //echo $storyID;

  $stmt->bind_param('ii',$downvotes,$storyID);

  $stmt->execute();

  $stmt->close();
  header("Location:main.php"); //refresh current page
}
?>
