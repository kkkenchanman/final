<?php
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );


$id = $_GET['column'];

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

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
$stmt -> bindValue(':id',$id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$column = $result;

// ----------------------------------------------------
//  コメントを取得
//-----------------------------------------------------
$sql = "SELECT * FROM comments WHERE gameId=:gameId";
$stmt = $dbh -> prepare($sql);
$stmt -> bindValue(':gameId',$id, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <header>
  
  </header>
  <main>
    <section>
      <table>
        <h2><?php echo $column['tournamentName']?></h2>
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
    </section>
      <form action="commentRegister.php" method="POST">
        <textarea name="comment" id="comment" cols="40" rows="10"></textarea>
        <input type="hidden" name='id' value=<?php echo $id;?>>
        <input type="submit" value='コメントを登録する'>
      </form>
    <section>
      <div>
      <?php foreach($comments as $comment): ?>
        <div><?php echo $comment['comment'];?></div>
      <?php endforeach; ?>
      </div>
    </section>
  </main>
          
</body>
</html>