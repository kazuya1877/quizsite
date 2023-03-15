<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン-クイズ投稿サイト</title>
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
    <form action="" method="post">
        [ログイン]<br>
        ユーザー名：<input type="text" name="name1"><br>
        パスワード：<input type="password" name="pass1"><br>
        <input type="submit" name="login" value = "ログイン"><hr>
        [ユーザー登録]<br>
        ユーザー名：<input type="text" name="name2"><br>
        パスワード：<input type="password" name="pass2"><br>
        <input type="submit" name="touroku" value = "登録"><hr>
    </form>
    <?php
        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //ユーザー情報のテーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS userinfo"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "pass TEXT,"
        . "point TEXT"
        .");";
        $stmt = $pdo->query($sql);
        //フォームの各値を変数に代入
        if(isset($_POST['name1']))
        {
            $name1 = $_POST['name1'];
            $name2 = $_POST['name2'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];
        }
        //登録ボタンを押したとき
        if(isset($_POST['touroku']))
        {
            //名前、パスワードが書かれているとき
            if($name2 != "" && $pass2 != "")
            {
                $sql = $pdo -> prepare("INSERT INTO userinfo (name, pass, point) VALUES (:name, :pass, :point)");
                $sql -> bindParam(':name', $name2, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass2, PDO::PARAM_STR);
                $sql -> bindParam(':point', $point, PDO::PARAM_STR);
                $point = 0;
                $sql -> execute();
                echo "ユーザー登録が完了しました。ユーザー名、パスワードはメモするようにしてください。
                <br>ログインフォームからログインをしてください。<br>";
            }
            else
            {
                //エラー表示
                ECHO"<FONT COLOR =\"RED\">[登録失敗]<br></FONT>";
                if($name2 == "")
                {
                    ECHO"<FONT COLOR =\"RED\">名前を入力してください。<br></FONT>";
                }
                if($pass2 == "")
                {
                    ECHO"<FONT COLOR =\"RED\">パスワードを入力してください。<br></FONT>";
                }
            }
        }
        //ログインボタンを押したとき
        elseif(isset($_POST['login']))
        {
            //名前、パスワードが書かれているとき
            if($name1 != "" && $pass1 != "")
            {
                //ユーザー名からデータを抽出
                $sql = 'SELECT * FROM userinfo WHERE name=:name ';
                $stmt = $pdo->prepare($sql);                  
                $stmt->bindParam(':name', $name1, PDO::PARAM_STR); 
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
                foreach ($results as $row)
                {
                    //パスワードが正しいとき
                    if($pass1 == $row['pass'])
                    {
                        $_SESSION['username'] = $row['name'];
                        $_SESSION['userpoint'] = $row['point'];
                        echo $_SESSION['username'].'さんようこそ！現在のポイントは'.$_SESSION['userpoint'].'点です。
                        <br>クイズの投稿、解答をしてポイントを増やしましょう！<br>';
                    }
                    //パスワードが違うとき
                    elseif($pass1 != $row['pass'])
                    {
                        ECHO"<FONT COLOR =\"RED\">[ログイン失敗]<br></FONT>";
                        ECHO"<FONT COLOR =\"RED\">パスワードが間違っています。<br></FONT>";
                    }
                }
            }
            else
            {
                //エラー表示
                ECHO"<FONT COLOR =\"RED\">[ログイン失敗]<br></FONT>";
                if($name1 == "")
                {
                    ECHO"<FONT COLOR =\"RED\">名前を入力してください。<br></FONT>";
                }
                if($pass1 == "")
                {
                    ECHO"<FONT COLOR =\"RED\">パスワードを入力してください。<br></FONT>";
                }
            }
        }
    ?>
</body>
</html>