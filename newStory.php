<?php   session_start();
$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}


/* save image path to database*/
  if (hash_equals($_SESSION['token'], $_POST['token']))
  {
  //    echo "<font size = '5'><font color=\"#e31919\">Error: NO CHOSEN FILE <br />";
  //    echo"<p><font size = '5'><font color=\"#e31919\">INSERT TO DATABASE FAILED";
  //  }
  //  else
  //  {
  //    move_uploaded_file($_FILES["uploadimg"]["tmp_name"],"images/" . $_FILES["uploadimg"]["name"]);
  //    echo"<font size = '5'><font color=\"#0CF44A\">SAVED<br>";
   //
  //    $file="images/".$_FILES["uploadimg"]["name"];

    $userID = $_SESSION['id'];
    $title=$_POST['title'];
    $content=$_POST['content'];
    $link=$_POST['link'];

    $stmt=$mysqli->prepare("insert into story (user_id, title, content, upVote, downVote, link) VALUES (?, ?, ?, '0', '0', ?);");

    if(!$stmt){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
    }


    $stmt->bind_param('isss', $userID, $title, $content,$link);

    $stmt->execute();

    $stmt->close();

    header("Location: account.php");



    //  if (!mysql_query($sql))
    //  {
    //     die('Error: ' . mysql_error());
    //  }
    //  echo "<font size = '5'><font color=\"#0CF44A\">SAVED TO DATABASE";

   }



?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>New Story</title>
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
    input[type=text],textarea{
        width:100%;
        padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
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
        <li ><a href="main.php">Home</a></li>
        <li class="active"><a href="#">My Account</a></li>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><?php echo "Hi! "; echo $_SESSION['uname'];?></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center">
  <div class="col-sm-2 sidenav"></div>
  <div class="col-sm-8 text-left">
    <h1>Create Stories</h1>
    <form action="newStory.php" method="post" enctype="multipart/form-data">
        <label><b>Title</b></label>
      <input type="text" placeholder="Enter Title" name="title" required>

      <label><b>Content</b></label><br>
      <textarea name="content" rows="20" required></textarea><br>
      <input type="text" placeholder="Enter External Link" name="link">
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
      <input type="submit" value="Submit" name="submit">
    </form>
  </div>
  <div class="col-sm-2 sidenav">

  </div>

</div>

<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
</html>
