<?php   session_start();
$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');
if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}

 /*get story list from database*/
 $sList=$mysqli->prepare("select title,content,story_id from story order by (upVote+downVote) desc;");
 if(!$sList){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$sList->execute();
 $result = $sList->get_result();
?>
<?php
      if(isset($_SESSION['uname'])) // If session is not set then redirect to Login Page
       {
           header("Location:home.php");
       }
?>
<?php   session_start();  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Main Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
    input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.container {
  width: 95%;
  padding: 16px;
}

.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    padding-top: 60px;
}

/* Modal Content/Box */
.modal-content {
    background-color: #fefefe;
    margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
}

/* Add Zoom Animation */
.animate {
    -webkit-animation: animatezoom 0.6s;
    animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
    from {-webkit-transform: scale(0)}
    to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
    from {transform: scale(0)}
    to {transform: scale(1)}
}
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
      </button>
        <span class="icon-bar"></span>
      <a class="navbar-brand" href="#">News</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="account.php">My Account</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php echo "<li>Hi ".$_SESSION['uname']."</li>";?>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div >
    <img src="news.jpg" style="width:100%" alt="Image">
</div>

<div class="container-fluid bg-3 text-center" style="padding-left: 150px;padding-right: 150px;">
  <h3>Hotest News</h3><br>

  <?php while ($row = $result->fetch_assoc()):?>
  <div class='card'>
  <div class='card-title'>
  <h2><?php echo htmlspecialchars($row['title']);?></h2>
  </div>
  <div class='card-block'>
  <p class='card-text' style="font:20px">
  <?php echo htmlspecialchars($row['content']);?>
  </p>
  </div>
    <form action="viewStory.php" method="post">
      <input type="hidden" name="storyID" value= "<?php echo htmlspecialchars($row['story_id']);?>">
      <input type="hidden" name="titleT" value= "<?php echo htmlspecialchars($row['title']);?>">
    	<input type="submit" name="viewStory" value="View" class="btn btn-primary">
    </form>
  </div><hr>
  <?php endwhile;
        $sList->close();
        ?>


</div><br>



<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
</html>
