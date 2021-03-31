<?php
session_start();
if (!isset($_SESSION['userId'])) {
  header('Location: ./login/login.php');
  exit();
}
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

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
$sql = "SELECT * FROM gameResults WHERE myTeamName=:teamName";
$stmt = $dbh -> prepare($sql);
$stmt -> bindValue(':teamName',$_SESSION['teamName'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------------------------------------------------
// 削除
// ----------------------------------------------------
if(!empty($_POST)){
  $sql = "DELETE FROM gameResults WHERE id = :id";
  $stmt = $dbh -> prepare($sql);
  $stmt -> bindValue(':id',$_POST['gameId'],PDO::PARAM_INT);
  $stmt->execute();
  header('Location: ./resultLists.php');
}


?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>結果一覧</title>
  <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/resultLists.css">
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
  <h2><?php echo $_SESSION['teamName'];?>試合結果一覧</h2>
  <table>
    <tr>
      <th>日付</th>
      <th>大会名</th>
      <th>対戦カード</th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
    
    <?php foreach($result as $column): ?>
      <tr>
        <td><?php echo $column['timeStamp']; ?></td>
        <td><?php echo $column['tournamentName']; ?></td>
        <td>
          <div class='gameResults'>
            <div>
              <p><?php echo $column['myTeamName']; ?></p>
              <p><?php echo $column['totalSetMyCount']?></p>
            </div>
            <div>
              VS
            </div>
            <div>
              <p><?php echo $column['opponentTeamName']; ?></p>
              <p><?php echo $column['totalSetOpponentCount']?></p>
            </div>
          </div>
        </td>
        <td>
          <form action="comment.php" method='GET'>
            <input type="hidden" name='gameId' value=<?php echo $column['id'] ?>>
            <p><input type="submit" value='詳細'></p>
          </form>
        </td>
        <td>
          <form action="edit.php" method='GET'>
            <input type="hidden" name='gameId' value=<?php echo $column['id'] ?>>
            <p><input type="submit" value='編集'></p>
          </form>
        </td>
        <td>
        <form action="" method='POST' onsubmit="return deleteConfirm()">
            <input type="hidden" name='gameId' value=<?php echo $column['id'] ?>>
            <p><input type="submit" value='削除'></p>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  </main>


  <script>
    const deleteConfirm = () => {
        /* 確認ダイアログ表示 */
        var flag = confirm ( "試合結果を消去してもよろしいですか？\n\n消去したくない場合は[キャンセル]ボタンを押して下さい");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
</script>  
</body>
</html>