<?php
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
$sql = 'SELECT * FROM gameResults';
$stmt = $dbh->query($sql);
$result = $stmt->fetchall(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

  <table>
    <tr>
      <th>日付</th>
      <th>大会名</th>
      <th>対戦カード</th>
      <th></th>
      <th></th>
    </tr>
    
    <?php foreach($result as $column): ?>
      <tr>
        <td><?php echo $column['timeStamp']; ?></td>
        <td><?php echo $column['tournamentName']; ?></td>
        <td>
          <div>
            <div>
              <p><?php echo $column['myTeamName']; ?></p>
              <p><?php echo $column['totalSetMyCount']?></p>
            </div>
            <div>
              VS
            </div>
            <div>
              <p><?php echo $column['totalSetOpponentCount']?></p>
              <p><?php echo $column['opponentTeamName']; ?></p>
            </div>
          </div>
        </td>
        <td>
          <form action="comment.php" method='GET'>
            <input type="hidden" name='column' value=<?php echo $column['id'] ?>>
            <p><input type="submit" value='詳細'></p>
          </form>
        </td>
        <td>
          <form action="edit.php" method='GET'>
            <input type="hidden" name='edit' value=<?php echo $column['id'] ?>>
            <p><input type="submit" value='編集'></p>
          </form>
        </td>
        <td><button>削除</button></td>
      </tr>
      
    <?php endforeach; ?>
  </table>
</body>
</html>