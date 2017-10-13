<?php
$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}
  $storyID = $_POST['storyID'];

/*get all info about the selected story by title*/
  $storyCont = $mysqli->prepare("select title,content,user.userName,link from story join user on story.user_id=user.id WHERE story.story_id = ?");
  if(!$storyCont){
 	printf("Query Prep Failed: %s\n", $mysqli->error);
 	exit;
  }
  $storyCont->bind_param('i',$storyID);
  $storyCont->execute();

  if(!$storyCont->bind_result($title,$content,$userName,$link))
  {
    echo "error";
  }
  $storyCont->fetch();
  $storyCont->close();


/* get list of comments for current story*/
  $commentList=$mysqli->prepare("select user.userName, comment.content from comment join user on comment.user_id=user.id where ? =comment.story_id");
  if(!$commentList){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
  }

  $commentList->bind_param('i',$storyID);
  $commentList->execute();
  $result = $commentList->get_result();
  ?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Story</title>
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

    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}

    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }

    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }

    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;}
    }
    input[type=text],input[type=password],textarea{
        width:100%;
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
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">News</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li ><a href="home.php">Home</a></li>


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

<div class="container-fluid text-center">
  <div class="col-sm-2"></div>
  <div class="col-sm-8 text-left">
    <h1><?php echo $title; ?></h1>
    <h4>by: <?php echo $userName; ?></h4>
<hr>

      <p style="border-style: ridge;font:18px;"><?php echo $content;?></p>
      <label><b>Link: <a target="_blank" href="<?php echo $link;?>"> <?php echo $link;?></a> </b></label>

      <table id="commentTable" class="table table-bordered">
        <thead>
        <tr>
          <th>Comment</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>

          <td>
            <h5><?php echo htmlspecialchars($row['userName']);?></h5><hr>
            <p><?php echo htmlspecialchars($row['content']);?></p>
          </td>

        </tr>
        <?php endwhile;
        $commentList->close();
        ?>
      </tbody>
      </table>

  </div>
  <div class="col-sm-2">

  </div>

</div>

<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
</html>
