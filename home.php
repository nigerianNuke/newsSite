<?php
//session_destroy();
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
 $result = $sList->get_result();?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
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
      <a class="navbar-brand" href="#">News</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><span class="glyphicon glyphicon-log-in"></span>
          <button onclick="document.getElementById('login').style.display='block'" style="width:auto;">Login</button>

        </li>
      </ul>
    </div>
  </div>
</nav>

<div id="login" class="modal">
 <form class="modal-content animate" action="signin.php" method="post">


    <div class="container">
      <label><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>

      <label><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>

      <input type="submit" name="login" value="LOGIN">
      <input type="submit" name="signup" value="SIGNUP">
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <button type="button" onclick="document.getElementById('login').style.display='none'" class="cancelbtn">Cancel</button>
      <span></span>
    </div>
  </form>
</div>

 <script>
// Get the modal
var modal = document.getElementById('login');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
</script>

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
    <form action="story.php" method="post">
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
