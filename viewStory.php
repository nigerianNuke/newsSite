<?php   session_start();
$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}

  $_SESSION['storyID'] = $_POST['storyID'];


/*get all info about the selected story by title*/
  $storyCont = $mysqli->prepare("select title,content,user.userName,link,upVote,downVote from story join user on story.user_id=user.id WHERE story.story_id = ?");
  if(!$storyCont){
 	printf("Query Prep Failed: %s\n", $mysqli->error);
 	exit;
  }
  $storyCont->bind_param('i',$_SESSION['storyID']);
  $storyCont->execute();

  if(!$storyCont->bind_result($title,$content,$userName,$link,$upvote,$downvote))
  {
    echo "error";
  }
  $storyCont->fetch();
  $_SESSION['title']=$title;
  $_SESSION['storyContent'] = $content;
  $_SESSION['storyUsername'] = $userName;
  $_SESSION['link'] = $link;
  $_SESSION['upvote'] = $upvote;
  $_SESSION['downvote'] = $downvote;
  $storyCont->close();


/* get list of comments for current story*/
  $commentList=$mysqli->prepare("select user.userName, comment.content from comment join user on comment.user_id=user.id where ? =comment.story_id");
  if(!$commentList){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
  }

  $commentList->bind_param('i',$_SESSION['storyID']);
  $commentList->execute();
  $result = $commentList->get_result();
  ?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>View Story</title>
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
        <li><a href="account.php">My Account</a></li>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><?php echo "Hi! ".$_SESSION['uname'];?></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center">
  <div class="col-sm-2"></div>
  <div class="col-sm-8 text-left">
    <h1><?php echo $_SESSION['title']; ?></h1>
    <h4>by: <?php echo $_SESSION['storyUsername']; ?></h4>
    <form action="interaction.php" method="post">
      <span class="glyphicon glyphicon-thumbs-up"><input type="submit" name="like" value="<?php echo $_SESSION['upvote'];?>"></span>
      <span class="glyphicon glyphicon-thumbs-down"><input type="submit" name="dislike" value="<?php echo $_SESSION['downvote'];?>"></span>
        <span class="glyphicon glyphicon-heart"><input type="submit" name="favorite" value="Add to Favorite"></span>
        <input type="hidden" name="upvote" value="<?php echo $_SESSION['upvote'];?>" />
        <input type="hidden" name="downvote" value="<?php echo $_SESSION['downvote'];?>" />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
<hr>

      <p style="border-style: ridge;font:18px;"><?php echo $_SESSION['storyContent'];?></p>
      <label><b>Link: <a target="_blank" href="<?php echo $_SESSION['link'];?>"> <?php echo $_SESSION['link'];?></a> </b></label>

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
      <form action="newComment.php" method="post">
        <label><b>Write Comment: </b></label><br>
      <textarea name="contents" rows="20" required></textarea><br>
      <input type="hidden" name="storyID" value="<?php echo $_SESSION['storyID'];?>" />
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
      <input type="submit" value="Submit" name="submit">
      </form>

  </div>
  <div class="col-sm-2">

  </div>

</div>

<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
</html>
