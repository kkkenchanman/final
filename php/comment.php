<?php
session_start();
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );


// ----------------------------------------------------
//  ゲームIDの受け取り
//-----------------------------------------------------

if(!empty($_GET['gameId'])){
  $_SESSION['gameId'] = $_GET['gameId'];
};

// ----------------------------------------------------
//  データベース接続
//-----------------------------------------------------

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

// ----------------------------------------------------
//  試合結果を取得
//-----------------------------------------------------
$sql = "SELECT * FROM gameResults WHERE id = :id";
$stmt = $dbh -> prepare($sql);
$stmt -> bindValue(':id',$_SESSION['gameId'], PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$column = $result;

// ----------------------------------------------------
//  コメントを登録
//-----------------------------------------------------

if(!empty($_POST['comment'])){
  $comment = $_POST['comment'];
  $sql="INSERT INTO
        comments (
          comment,
          gameId,
          userId,
          userName
        )
      VALUES(
          :comment,
          :gameId,
          :userId,
          :userName
        )";

$dbh->beginTransaction();
try{
  $stmt=$dbh->prepare($sql);
  $stmt -> bindValue(':comment',$comment, PDO::PARAM_STR);
  $stmt -> bindValue(':gameId',$_SESSION['gameId'], PDO::PARAM_INT);
  $stmt -> bindValue(':userId',$_SESSION['userId'], PDO::PARAM_INT);
  $stmt -> bindValue(':userName',$_SESSION['userName'], PDO::PARAM_STR);
  $stmt->execute();
  $dbh->commit();
  header('Location: ./comment.php');
  exit;
}catch(PDOException $e){
  $dbh->rollback();
  exit($e);
}
};

// ----------------------------------------------------
//  コメントを取得
//-----------------------------------------------------
$sql = "SELECT * FROM comments WHERE gameId=:gameId";
$stmt = $dbh -> prepare($sql);
$stmt -> bindValue(':gameId',$_SESSION['gameId'], PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>コメント</title>
  <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/comment.css">
</head>
<body>
<section id='header'>
    <div id="headerContainer">
      <h1>YUKIGASSEN TIMER APP</h1>
      <div id='rightContent'>
      <a href="./index.php"><p>タイマー</p></a>
        <a href="./resultLists.php"><p>結果一覧</p></a>
        <a href="./login/login.php"><p>LOGOUT</p></a>
      </div>
    </div>
  </section>
  <main>
      <h2><?php echo $column['tournamentName']?></h2>
      <table>
        <tr>
          <td><?php echo $column['myTeamName']?></td>
          <td>VS</td>
          <td><?php echo $column['opponentTeamName']?></td>
        </tr>
        <tr>
          <td><?php echo $column['firstSetMyCount']?></td>
          <td>FIRST SET</td>
          <td><?php echo $column['firstSetOpponentCount']?></td>
        </tr>
        <tr>
          <td><?php echo $column['secondSetMyCount']?></td>
          <td>SECOND SET</td>
          <td><?php echo $column['secondSetOpponentCount']?></td>
        </tr>
        <tr>
          <td><?php echo $column['thirdSetMyCount']?></td>
          <td>THIRD SET</td>
          <td><?php echo $column['thirdSetOpponentCount']?></td>
        </tr>
        <tr>
          <td><?php echo $column['totalSetMyCount']?></td>
          <td>TOTAL SCORE</td>
          <td><?php echo $column['totalSetOpponentCount']?></td>
        </tr>
      </table>
      <div id='commentArea'>
        <div id='commentLeft'>
          <h2>コメント一覧</h2>
          <?php foreach($comments as $comment): ?>
            <div id='displayComment'>
              <div class='userName'>@<?php echo $comment['userName'];?></div>
              <div><?php echo $comment['comment'];?></div>
            </div>

          <?php endforeach; ?>
        </div>
        <div id="commentRight">
          <h2>コメント登録</h2>
          <form action='' method="POST">
            <textarea name="comment" id="comment" cols="60" rows="5"></textarea>
            <p><input type="submit" value='コメントを登録する'></p>
          </form>
        </div>
      </div>


  </main>
          
</body>
</html>