	
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

/* 会員登録の手続き以外のアクセスを飛ばす */
if (!isset($_SESSION['join'])) {
    header('Location: entry.php');
    exit();
}
 
if (!empty($_POST['check'])) {
    // パスワードを暗号化
    $hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO
              users (
                name,
                email,
                password,
                teamName
              )
            VALUES(
              :name,
              :email,
              :password,
              :teamName
            )";

    // 入力情報をデータベースに登録
    $dbh->beginTransaction();
    try{
      $stmt=$dbh->prepare($sql);
      $stmt -> bindValue(':name',$_SESSION['join']['name'], PDO::PARAM_STR);
      $stmt -> bindValue(':email',$_SESSION['join']['email'], PDO::PARAM_STR);
      $stmt -> bindValue(':password',$hash, PDO::PARAM_STR);
      $stmt -> bindValue(':teamName',$_SESSION['join']['teamName'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
      header('Location: ./thank.php'); 
      unset($_SESSION['join']); 
      exit;
    }catch(PDOException $e){
      $dbh->rollback();
      exit($e);
    }

      // セッションを破棄
      // thank.phpへ移動
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>確認画面</title>
</head>
<body>
    <div class="content">
        <form action="" method="POST">
            <input type="hidden" name="check" value="checked">
            <h1>入力情報の確認</h1>
            <p>ご入力情報に変更が必要な場合、下のボタンを押し、変更を行ってください。</p>
            <p>登録情報はあとから変更することもできます。</p>
            <?php if (!empty($error) && $error === "error"): ?>
                <p class="error">＊会員登録に失敗しました。</p>
            <?php endif ?>
            <hr>
 
            <div class="control">
                <p>ニックネーム</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?></span></p>
            </div>
            <div class="control">
                <p>メールアドレス</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?></span></p>
            </div>
            <div class="control">
                <p>チーム名</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['teamName'], ENT_QUOTES); ?></span></p>
            </div>
            <br>
            <a href="entry.php" class="back-btn">変更する</a>
            <button type="submit" class="btn next-btn">登録する</button>
            <div class="clear"></div>
        </form>
    </div>
</body>
</html>