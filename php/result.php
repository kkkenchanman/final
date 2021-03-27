<?php 
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

$setResults = $_POST['setResults'];
$namesList =$_POST['names'];

$results = json_decode($setResults, true);
$names = json_decode($namesList, true);


$firstSetMyCount = $results[0]['myCount'];
$firstSetOpponentCount = $results[0]['opponentCount'];
$secondSetMyCount = $results[1]['myCount'];
$secondSetOpponentCount = $results[1]['opponentCount'];

if(empty($results[2]['myCount'])){
  $thirdSetMyCount = 0;
}else{
  $thirdSetMyCount  = $results[2]['myCount'];
}

if(empty($results[2]['opponentCount'])){
  $thirdSetOpponentCount = 0;
}else{
  $thirdSetOpponentCount = $results[2]['opponentCount'];
}

if(empty($results[3]['myCount'])){
  $victoryThrowMyCount = 0;
}else{
  $victoryThrowMyCount = $results[3]['myCount'];
}

if(empty($results[3]['opponentCount'])){
  $victoryThrowOpponentCount = 0;
}else{
  $victoryThrowOpponentCount = $results[3]['opponentCount'];
}

$totalMyResults = $firstSetMyCount + $secondSetMyCount + $thirdSetMyCount;
$totalOpponentResults = $firstSetOpponentCount + $secondSetOpponentCount + $thirdSetOpponentCount;

if(empty($names['tournamentName'])){
  $tournamentName = '大会名';
}else{
  $tournamentName = $names['tournamentName'];
}

if(empty($names['myTeamName'])){
  $myTeamName = '自チーム名';
}else{
  $myTeamName = $names['myTeamName'];
}

if(empty($names['opponentTeamName'])){
  $opponentTeamName = '相手チーム名';
}else{
  $opponentTeamName = $names['opponentTeamName'];
}


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

  $sql = "INSERT INTO 
          gameResults (
            tournamentName,
            myTeamName,
            opponentTeamName,
            firstSetMyCount, 
            firstSetOpponentCount, 
            secondSetMyCount, 
            secondSetOpponentCount, 
            thirdSetMyCount, 
            thirdSetOpponentCount, 
            victoryThrowMyCount, 
            victoryThrowOpponentCount,
            totalSetMyCount,
            totalSetOpponentCount,
            winOrLose
            ) 
          VALUES(
            :tournamentName,
            :myTeamName,
            :opponentTeamName,
            :firstSetMyCount, 
            :firstSetOpponentCount, 
            :secondSetMyCount, 
            :secondSetOpponentCount, 
            :thirdSetMyCount, 
            :thirdSetOpponentCount, 
            :victoryThrowMyCount, 
            :victoryThrowOpponentCount,
            :totalSetMyCount,
            :totalSetOpponentCount,
            1
          )";

  $dbh = dbConnect();
  $dbh -> beginTransaction();
  try{
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':tournamentName',$tournamentName, PDO::PARAM_STR);
    $stmt -> bindValue(':myTeamName',$myTeamName, PDO::PARAM_STR);
    $stmt -> bindValue(':opponentTeamName',$opponentTeamName, PDO::PARAM_STR);
    $stmt -> bindValue(':firstSetMyCount',$firstSetMyCount, PDO::PARAM_INT);
    $stmt -> bindValue(':firstSetOpponentCount',$firstSetOpponentCount, PDO::PARAM_INT);
    $stmt -> bindValue(':secondSetMyCount',$secondSetMyCount, PDO::PARAM_INT);
    $stmt -> bindValue(':secondSetOpponentCount',$secondSetOpponentCount, PDO::PARAM_INT);
    $stmt -> bindValue(':thirdSetMyCount',$thirdSetMyCount, PDO::PARAM_INT);
    $stmt -> bindValue(':thirdSetOpponentCount',$thirdSetOpponentCount, PDO::PARAM_INT);
    $stmt -> bindValue(':victoryThrowMyCount',$victoryThrowMyCount, PDO::PARAM_INT);
    $stmt -> bindValue(':victoryThrowOpponentCount',$victoryThrowOpponentCount, PDO::PARAM_INT);
    $stmt -> bindValue(':totalSetMyCount',$totalMyResults, PDO::PARAM_INT);
    $stmt -> bindValue(':totalSetOpponentCount',$totalOpponentResults, PDO::PARAM_INT);
    $stmt -> execute();
    $dbh -> commit();
  }catch(PDOException $e){
    $dbh -> rollback();
    exit($e);
  }

  $sql = 'SELECT * FROM gameResults';
  $stmt = $dbh->query($sql);
  $result = $stmt->fetchall(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RESULT</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/result.css">
</head>
<body>
  <section id='header'>
    <h1>YUKIGASSEN TIMER APP</h1>
  </section>
  <main>
  <section id='gameResultArea'>
  <h1>試合結果</h1>
  <h2><?php echo $tournamentName;?></h2>
  <table>
      <tr>
        <th><?php echo $myTeamName;?></th>
        <th>VS</th>
        <th><?php echo $opponentTeamName;?></th>
      </tr>
      <tr id='firstSetResult' class=''>
        <td class='myResult'><?php echo $firstSetMyCount;?></td>
        <td>第1セット</td>
        <td class='opponentResult'><?php echo $firstSetOpponentCount;?></td>
      </tr>
      <tr id='secondSetResult' class=''>
        <td class='myResult'><?php echo $secondSetMyCount;?></td>
        <td>第2セット</td>
        <td class='opponentResult'><?php echo $secondSetOpponentCount;?></td>
      </tr>
      <tr id='thirdSetResult' class=''>
        <td class='myResult'><?php echo $thirdSetOpponentCount;?></td>
        <td>第3セット</td>
        <td class='opponentResult'><?php echo $thirdSetOpponentCount;?></td>
      </tr>
      <tr id='victoryThrowResult' class='close'>
        <td class='myResult'></td>
        <td>VT</td>
        <td class='opponentResult'></td>
      </tr>
      <tr id='totalResult'>
        <td id='totalMyResult'><?php echo $totalMyResults;?></td>
        <td>トータル</td>
        <td id='totalOpponentResult'><?php echo $totalOpponentResults;?></td>
      </tr>
    </table>      
  </section>
  <section id='timeLineArea'>
    <h1>タイムライン</h1>
    <div id='timelineContents'>
      <?php foreach($result as $column): ?>
        <div class="timelineContent">
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
          </div> 
        <?php endforeach; ?>
       
    </div>
    
  </section>
  </main>
  <section id='nextGame'>
    <p><button id='nextGameBtn'>NEXT GAME</button></p>   
  </section>  

<script>
$(function(){

  $('#nextGame').on('click', function(){
    if(confirm('次の試合へ進みますか？')){
      window.location.href = './index.php'
    }
  })


})        
</script>
</body>
</html>

