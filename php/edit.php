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
//  試合結果を編集
//-----------------------------------------------------

if(!empty($_POST)){
  $sql = "UPDATE gameResults SET
        tournamentName = :tournamentName,
        myTeamName = :myTeamName,
        opponentTeamName = :opponentTeamName,
        firstSetMyCount = :firstSetMyCount,
        firstSetOpponentCount = :firstSetOpponentCount,
        secondSetMyCount = :secondSetMyCount,
        secondSetOpponentCount = :secondSetOpponentCount,
        thirdSetMyCount = :thirdSetMyCount,
        thirdSetOpponentCount = :thirdSetOpponentCount,
        totalSetMyCount = :totalSetMyCount,
        totalSetOpponentCount = :totalSetOpponentCount
        WHERE id = :id
       ";
  $stmt = $dbh -> prepare($sql);
  $stmt -> bindValue(':tournamentName',$_POST['tournamentName'], PDO::PARAM_STR);
  $stmt -> bindValue(':myTeamName',$_POST['myTeamName'], PDO::PARAM_STR);
  $stmt -> bindValue(':opponentTeamName',$_POST['opponentTeamName'], PDO::PARAM_STR);
  $stmt -> bindValue(':firstSetMyCount',$_POST['firstSetMyCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':firstSetOpponentCount',$_POST['firstSetOpponentCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':secondSetMyCount',$_POST['secondSetMyCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':secondSetOpponentCount',$_POST['secondSetOpponentCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':thirdSetMyCount',$_POST['thirdSetMyCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':thirdSetOpponentCount',$_POST['thirdSetOpponentCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':totalSetMyCount',$_POST['totalSetMyCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':totalSetOpponentCount',$_POST['totalSetOpponentCount'], PDO::PARAM_INT);
  $stmt -> bindValue(':id',$_SESSION['gameId'], PDO::PARAM_INT);
  $stmt->execute();
  header('Location: ./resultLists.php');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>試合結果の編集</title>
  <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/edit.css">
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
<form action="" method="POST">
      <table>
        <h2>
          <input type="text" value=<?php echo $column['tournamentName']?> name='tournamentName'>
        </h2>
        <tr>
          <td>
            <input type="text" value=<?php echo $column['myTeamName']?> name='myTeamName'>
          </td>
          <td>
            VS
          </td>
          <td>
            <input type="text" value=<?php echo $column['opponentTeamName']?> name='opponentTeamName'>
          </td>
        </tr>
        <tr>
          <td>
            <select name="firstSetMyCount" id="firstSetMyCount" onchange="displayTotalSetMyCount()"></select>
          </td>
          <td>FIRST SET</td>
          <td>
            <select name="firstSetOpponentCount" id="firstSetOpponentCount" onchange="displayTotalSetOpponentCount()"></select>
          </td>
        </tr>
        <tr>
          <td>
            <select name="secondSetMyCount" id="secondSetMyCount" onchange="displayTotalSetMyCount()"></select>
          </td>
          <td>SECOND SET</td>
          <td>
            <select name="secondSetOpponentCount" id="secondSetOpponentCount" onchange="displayTotalSetOpponentCount()"></select>
          </td>
        </tr>
        <tr>
          <td>
            <select name="thirdSetMyCount" id="thirdSetMyCount" onchange="displayTotalSetMyCount()"></select>
          </td>
          <td>THIRD SET</td>
          <td>
            <select name="thirdSetOpponentCount" id="thirdSetOpponentCount" onchange="displayTotalSetOpponentCount()"></select>
          </td>
        </tr>
        <tr>
          <td>
            <p id="displayTotalSetMyCount"></p>
            <input type="hidden" name='totalSetMyCount' id='totalSetMyCount'>
          </td>
          <td>TOTAL SCORE</td>
          <td>
            <p id="displayTotalSetOpponentCount"></p>
            <input type="hidden" name='totalSetOpponentCount' id='totalSetOpponentCount'>
          </td>
        </tr>
      </table>
      <input type="submit" value='決定する' id='submit'>
  </form>
</main>


<script>

// ----------------------------------------------------
//  各セットのカウントを表示
//-----------------------------------------------------

  const createOption = (id) =>{
    const counts = [0, 1, 2, 3, 4, 5, 6, 7, 10]
    let select = document.getElementById(id)
    let initialValue

    if(id === 'firstSetMyCount'){
      initialValue = <?php echo $column['firstSetMyCount']?>
    }
    else if(id === 'secondSetMyCount'){
      initialValue = <?php echo $column['secondSetMyCount']?>
    }
    else if(id === 'thirdSetMyCount'){
      initialValue = <?php echo $column['thirdSetMyCount']?>
    }
    else if(id === 'firstSetOpponentCount'){
      initialValue = <?php echo $column['firstSetOpponentCount']?>
    }
    else if(id === 'secondSetOpponentCount'){
      initialValue = <?php echo $column['secondSetOpponentCount']?>
    }
    else if(id === 'thirdSetOpponentCount'){
      initialValue = <?php echo $column['thirdSetOpponentCount']?>
    }

    document.createElement('option')
    counts.map((count) => {
      let option = document.createElement('option')
      option.setAttribute('value', count)
      option.innerHTML = count
      if(count === initialValue){
        option.setAttribute('selected', 'selected')
      }
      select.appendChild(option)
    });
  }

  createOption('firstSetMyCount');
  createOption('secondSetMyCount');
  createOption('thirdSetMyCount');
  createOption('firstSetOpponentCount');
  createOption('secondSetOpponentCount');
  createOption('thirdSetOpponentCount');

// ----------------------------------------------------
//  トータルスコアを表示・値の設定
//-----------------------------------------------------

const displayTotalSetMyCount = () =>{
  let totalSetMyCount =
  Number(document.getElementById('firstSetMyCount').value) +
  Number(document.getElementById('secondSetMyCount').value) +
  Number(document.getElementById('thirdSetMyCount').value)

  document.getElementById('displayTotalSetMyCount').innerHTML = totalSetMyCount

  document.getElementById('totalSetMyCount').setAttribute('value', totalSetMyCount)
}

const displayTotalSetOpponentCount = () =>{
  let totalSetOpponentCount =
  Number(document.getElementById('firstSetOpponentCount').value) +
  Number(document.getElementById('secondSetOpponentCount').value) +
  Number(document.getElementById('thirdSetOpponentCount').value)

  document.getElementById('displayTotalSetOpponentCount').innerHTML = totalSetOpponentCount
  document.getElementById('totalSetOpponentCount').setAttribute('value', totalSetOpponentCount)
}

displayTotalSetMyCount();
displayTotalSetOpponentCount();

</script>
</body>
</html>