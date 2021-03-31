<?php
session_start();

function dbConnect(){
  $dsn = 'mysql:host=localhost;dbname=yukigassen_APP;charset=utf8';
  $user = 'yukigassen_APP_user';
  $pass = 'yukigassenAPP';

  try{
    $dbh = new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
  }catch(PDOException $e){
    echo 'error'.$e->getMessage();
    exit();
  }
  return $dbh;
}

$dbh = dbConnect();

if(isset($_POST['login'])){
  $email = $_POST['email'];
  $password = $_POST['password'];
}

$sql = "SELECT * FROM users WHERE email=:email";
$stmt = $dbh -> prepare($sql);
$stmt -> bindValue(':email',$email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(password_verify($password,$user['password'])){
  $_SESSION['userName'] = $user['name'];
  $_SESSION['userId'] = $user['userId'];
  $_SESSION['teamName'] = $user['teamName'];
  header('location: ../index.php');
}

echo $user;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <link rel="stylesheet" href=".../css/login.css">
</head>
<body>
<section id='header'>
    <div id="headerContainer">
      <h1>YUKIGASSEN TIMER APP</h1>
      </div>
    </div>
  </section>  
<main>
<h1>ログインフォーム</h1>
<form method="post">
	<div class="form-group">
		<input type="email"  class="form-control" name="email" placeholder="メールアドレス" required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="パスワード" required />
	</div>
	<input type="submit" class="btn btn-default" name="login" value='ログインする'>
	<a href="entry.php">会員登録はこちら</a>
</form>
</main>

</body>
</html>
