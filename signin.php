<?php   session_start();
$mysqli = new mysqli('localhost', 'newsAdmin', 'newssite', 'newsSite');

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}


if(isset($_POST['login']))
{
    $stmt = $mysqli->prepare("select id,password from user where userName= ?");
    if(!$stmt){
	     printf("Query Prep Failed: %s\n", $mysqli->error);
	      exit;
      }

    $stmt->bind_param('s',$_POST['uname']);
    $stmt->execute();

    if(!$stmt->bind_result($id,$psw))
    {
      echo "error";
    }
    $password_guess = $_POST['psw'];
    $stmt->fetch();
    $stmt->close();

    if(password_verify($password_guess,$psw)){
        session_start();
        $_SESSION['id']=$id;
        $_SESSION['token'] = bin2hex(32);
        echo"
        <script>
        alert('Welcome back!');
        window.location.href='main.php';
        </script>";
        exit;
    }
    else{
      echo"
      <script>
      alert('Invalid User Name or Password');
      window.location.href='home.php';
      </script>";
    }
}
elseif(isset($_POST['signup']))
{
    $username = $_POST['uname'];
    $password = password_hash($_POST['psw'],PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("insert into user (userName,password) values(?,?)");
    if(!$stmt){
	     printf("Query Prep Failed: %s\n", $mysqli->error);
	      exit;
      }

    $stmt->bind_param("ss",$username,$password);

    $stmt->execute();
    if(mysqli_affected_rows($mysqli) < 1)
    {
      $stmt->close();
      echo"
      <script>
      alert('User Name Already Exists');
      window.location.href='home.php';
      </script>";
      exit;
    }
    $stmt->close();


    session_start();
        $stmt = $mysqli->prepare("select id from user where userName= ?");
        if(!$stmt){
    	     printf("Query Prep Failed: %s\n", $mysqli->error);
    	      exit;
          }

        $stmt->bind_param('s',$username);
        $stmt->execute();

        if(!$stmt->bind_result($id))
        {
          echo "error";
        }
        $stmt->fetch();
        $stmt->close();

        $_SESSION['id']=$id;
        $_SESSION['uname']=$name;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        echo"
        <script>
        alert('Welcome!');
        window.location.href='main.php';
        </script>";
        exit;

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Signin</title>
</head>
<body>
  <footer>

  </footer>
</body>


</html>
