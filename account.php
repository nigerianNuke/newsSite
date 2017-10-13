<?php   session_start();
$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}

 /*get story list from database*/
 $sList=$mysqli->prepare("select title,story_id,content,link from story where user_id= ?");
 if(!$sList){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

 $sList->bind_param('i',$_SESSION['id']);
 $sList->execute();
 $result = $sList->get_result();

  /*get comment list from database*/
  $cList=$mysqli->prepare("select content,comment_id from comment where user_id= ?");
  if(!$cList){
   printf("Query Prep Failed: %s\n", $mysqli->error);
   exit;
 }

  $cList->bind_param('i',$_SESSION['id']);
  $cList->execute();
  $result2 = $cList->get_result();

 /*get favorite list from database*/
 $fList=$mysqli->prepare("select title,story.story_id from story left join favorite on favorite.story_id = story.story_id where favorite.user_id=?");
 if(!$fList){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}

 $fList->bind_param('i',$_SESSION['id']);
 $fList->execute();
 $result3 = $fList->get_result();

 if(isset($_POST['sedit']) && hash_equals($_SESSION['token'], $_POST['token'])){
   $_SESSION['titleS'] = $_POST['titleSE'];
   $_SESSION['contentsS'] = $_POST['contentsS'];
   $_SESSION['storyIDS'] = $_POST['storyID'];
   $_SESSION['link'] = $_POST['link'];
   header("Location:editStory.php");
 }
 if(isset($_POST['sdelete']) && hash_equals($_SESSION['token'], $_POST['token'])){
  /*delete the story from story table*/
    $storyID= $_POST['storyID'];

    $stmt=$mysqli->prepare("delete from story WHERE story_id = ?;");

    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }

    $stmt->bind_param('i', $storyID);

    $stmt->execute();

    $stmt->close();
    header("Location:account.php"); //refresh current page
 }

/*Update an existing comment*/
  if(isset($_POST['cedit']) && hash_equals($_SESSION['token'], $_POST['token'])){
    $_SESSION['commentIDC'] = $_POST['commentID'];
    $_SESSION['contentsC'] = $_POST['contentsES'];
    header("Location:editComment.php");
 }

 if(isset($_POST['cdelete']) && hash_equals($_SESSION['token'], $_POST['token'])){
  /*delete the comment from comment table*/
    $commentID= $_POST['commentID'];

    $stmt=$mysqli->prepare("delete from comment WHERE comment_id = ?;");

    if(!$stmt){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
    }

    $stmt->bind_param('i', $commentID);

    $stmt->execute();

    $stmt->close();
    header("Location:account.php"); //refresh current page
 }

  if(isset($_POST['fdelete']) && hash_equals($_SESSION['token'], $_POST['token'])){
  /*delete the favorite from favorite table*/
    echo "here";
    $userID= $_SESSION['id'];
    echo $userID;
    $storyID = $_POST['storyIDD'];
    echo $storyID;

    $stmt=$mysqli->prepare("delete from favorite WHERE user_id = ? and story_id= ?;");

    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }

    $stmt->bind_param('ii', $userID,$storyID);

    $stmt->execute();

    $stmt->close();

    header("Location:account.php"); //refresh current page
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Account</title>
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
        <li><?php echo "Hi! ".$_SESSION['uname'];?></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center">
  <div class="col-sm-2 sidenav"></div>
  <div class="col-sm-8 text-left">
    <h1>My Stories</h1>
    <table id="stable" class="table">
      <thead>
        <tr>
          <!-- <th>Author</th> -->
          <th>Title</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><p><?php echo htmlspecialchars($row['title']);?></p>
            <form action="viewStory.php" method="post">
              <input type="hidden" name="storyID" value= "<?php echo htmlspecialchars($row['story_id']);?>"/>
              <input type="hidden" name="titleT" value= "<?php echo htmlspecialchars($row['title']);?>"/>
              <input type="submit" name="viewStory" value="View" class="btn btn-primary"/>
            </form>
          </td>
          <br>
          <td>
            <form action="account.php" method="post">
              <input type="hidden" name="titleSE" value="<?php echo $row['title'];?>" />
              <input type="hidden" name="contentsS" value="<?php echo $row['content'];?>" />
              <input type="hidden" name="storyID" value= "<?php echo htmlspecialchars($row['story_id']);?>"/>
              <input type="hidden" name="link" value="<?php echo $row['link'];?>" />
              <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
              <input type="submit" name="sedit" value="Edit" class="btn btn-info"/>
              <input type="submit" name="sdelete" value="Delete" class="btn btn-danger"/>
            </form>
          </td>
        </tr>
        <?php endwhile;
        $sList->close();
        ?>
      </tbody>
    </table>
    <form action="newStory.php" method="post">
    <input type="submit" name="newStory" value="Write Story" class="btn btn-success"/>
    </form>

    <hr>
    <h1>My Comments</h1>
    <table id="ctable" class="table">
      <thead>
        <tr>
          <th>Comments</th>
          <!-- <th>Story Title</th> -->
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result2->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['content']);?></td>
          <td><?php //echo htmlspecialchars($row['title']);?></td>
          <td>
            <form action="account.php" method="post">
              <input type="hidden" name="commentID" value="<?php echo $row['comment_id'];?>" />
              <input type="hidden" name="contentsES" value="<?php echo $row['content'];?>" />
              <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
              <input type="submit" name="cedit" value="Edit" class="btn btn-info"/>
              <input type="submit" name="cdelete" value="Delete" class="btn btn-danger"/>
            </form>
          </td>
        </tr>
        <?php endwhile;
        $cList->close();
        ?>
      </tbody>
    </table>
    <hr>
    <h1>My Favorite Stories</h1>
    <table id="ftable" class="table">
      <thead>
        <tr>
          <!-- <th>Author</th> -->
          <th>Title</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result3->fetch_assoc()): ?>
        <tr>
          <td><?php //echo htmlspecialchars($row['userid']);?></td>
          <td><p><?php echo htmlspecialchars($row['title']);?></p>
            <form action="viewStory.php" method="post">
              <input type="hidden" name="storyID" value= "<?php echo htmlspecialchars($row['story_id']);?>"/>
              <input type="hidden" name="titleT" value= "<?php echo htmlspecialchars($row['title']);?>"/>
              <input type="submit" name="viewStory" value="View" class="btn btn-primary"/>
            </form>
          <!-- <td><a href="viewStory.php"><?php //echo htmlspecialchars($row['title']);?></a></td> -->
          <td>
            <form action="account.php" method="post">
              <input type="hidden" name="storyIDD" value= "<?php echo htmlspecialchars($row['story_id']);?>"/>
              <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
              <input type="submit" name="fdelete" value="Delete" class="btn btn-danger"/>
            </form>
          </td>
        </tr>
        <?php endwhile;
        $fList->close();?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-2 sidenav"></div>

</div>

<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
</html>
