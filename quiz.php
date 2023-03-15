<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>クイズを投稿する-クイズ投稿サイト</title>
</head>
<body>
    <h1>クイズ投稿サイト</h1><hr>
    <a href = "toppage.php">トップページ</a>/<a href = "login.php">ユーザー登録,ログイン</a>/<a href = "quiz.php">クイズを投稿する</a>/<a href = "ranking.php">ランキング</a><hr>
    <?php
        session_start();
        //ログイン中のとき
        if(isset($_SESSION['username']) && $_SESSION['username'] !="")
        {
            echo"ログイン中：".$_SESSION['username']."さん現在のポイントは".$_SESSION['userpoint']."点です。<hr>";
        }
        else
        {
            echo"未ログイン：ユーザー登録、ログインをするとポイントが付与されるようになります。<hr>";
        }
    ?>
    <!--入力フォームの作成-->
    [クイズを投稿する]投稿で3ポイント付与されます。
    <form action="" method="post">
        問題文：<br><textarea name="quiz" cols="80" rows="10"></textarea><br>
        解答：<br><input type="text" name="answer"><br>
        <input type="submit" name="up" value="投稿" ><hr>
    </form>
    <?php
        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //クイズのテーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS quiz"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "quiz TEXT,"
        . "answer TEXT"
        .");";
        $stmt = $pdo->query($sql);
        //フォームの値を変数に代入
        if(isset($_POST['quiz']))
        {
            $quiz = $_POST['quiz'];
            $answer = $_POST['answer'];
        }
        //投稿ボタンを押したとき
        if(isset($_POST['up']))
        {
            //問題文、解答が書かれているとき
            if($quiz != "" && $answer != "")
            {
                //DBへ書き込む
                $sql = $pdo -> prepare("INSERT INTO quiz (quiz, answer) VALUES (:quiz, :answer)");
                $sql -> bindParam(':quiz', $quiz, PDO::PARAM_STR);
                $sql -> bindParam(':answer', $answer, PDO::PARAM_STR);
                $sql -> execute();
                echo "クイズを投稿しました。<br>";
                //ログイン中の時
                if(isset($_SESSION['username']) && $_SESSION['username'] !="")
                {
                    //ポイントを加算
                    $_SESSION['userpoint']+=3;
                    $sql = 'UPDATE userinfo SET point=:point WHERE name=:name';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':point', $_SESSION['userpoint'], PDO::PARAM_STR);
                    $stmt->bindParam(':name', $_SESSION['username'], PDO::PARAM_STR);
                    $stmt->execute();
                    echo "3ポイント付与されました。現在のポイントは".$_SESSION['userpoint']."ポイントです。<br>";
                }
            }
            else
            {
                //エラー表示
                ECHO"<FONT COLOR =\"RED\">[投稿失敗]<br></FONT>";
                if($quiz == "")
                {
                    ECHO"<FONT COLOR =\"RED\">問題文を入力してください。<br></FONT>";
                }
                if($answer == "")
                {
                    ECHO"<FONT COLOR =\"RED\">解答を入力してください。<br></FONT>";
                }
            }
        }
    ?>
</body>
</html>