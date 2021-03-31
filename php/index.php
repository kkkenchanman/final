<?php 
session_start();
if (!isset($_SESSION['userId'])) {
  header('Location: ./login/login.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yukigassen_APP</title>
  <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src='../js/index.js'></script>

</head>
<body>
  <section id='header'>
    <div id="headerContainer">
      <h1>YUKIGASSEN TIMER APP</h1>
      <div id='rightContent'>
        <a href="./resultLists.php"><p>結果一覧</p></a>
        <a href="./login/login.php"><p>LOGOUT</p></a>
      </div>
    </div>
  </section>
  <main>
  <section id='timer'>
    <div class='timerBtnContainer'><button id='timerStart' class='timerStart'></button></div>
    <p id='gameTimer'></p>
    <div class='timerBtnContainer'><button id='timerReset'>リセット</button></div>
  </section>
  
  <section id="count">
    <div class="countContentLeft countItem">
      <p><button id='myCountPlus'>+</button></p>
      <h2 id="displayMyCount"></h2>
      <p><button id='myCountMinus'>-</button></p>
    </div>
  <div class="countContentCenter ">
    <p id='displayJudgeCount'></p>
  </div>
  <div class="countContentRight countItem">
    <p><button id='opponentCountMinus'>-</button></p>
    <h2 id="displayOpponentCount"></h2>
    <p><button id='opponentCountPlus'>+</button></p>
  </div>
  </section>

  <section id="displayResult">
  <p><input type="text" placeholder='大会名を入力' id='tournamentName'></p>
    <table>
      <tr>
        <th><input type="text" placeholder='自チーム名を入力' id='myTeamName'></th>
        <th>VS</th>
        <th><input type="text" placeholder='相手チーム名を入力' id='opponentTeamName'></th>
      </tr>
      <tr id='firstSetResult' class=''>
        <td class='myResult'></td>
        <td>第1セット</td>
        <td class='opponentResult'></td>
      </tr>
      <tr id='secondSetResult' class=''>
        <td class='myResult'></td>
        <td>第2セット</td>
        <td class='opponentResult'></td>
      </tr>
      <tr id='thirdSetResult' class=''>
        <td class='myResult'></td>
        <td>第3セット</td>
        <td class='opponentResult'></td>
      </tr>
      <tr id='victoryThrowResult' class='close'>
        <td class='myResult'></td>
        <td>VT</td>
        <td class='opponentResult'></td>
      </tr>
      <tr id='totalResult'>
        <td id='totalMyResult'></td>
        <td>トータル</td>
        <td id='totalOpponentResult'></td>
      </tr>
    </table> 
  </section>


    
  <section id='submit'>
  <p><button id='nextSet'>NEXT SET</button></p>
    <form action="result.php" method='POST'>
      <input type="hidden" name='setResults' id='setResults' value=''>
      <input type="hidden" name='names' id='names' value=''>
      <p><input type="submit" class='close' value='GAME FINISH' id='gameFinish'></p>
    </form>
  </section>
  </main>
</body>
</html>