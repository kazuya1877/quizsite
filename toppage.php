<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>トップページ-クイズ投稿サイト</title>
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
    [クイズに解答する]<br>正解で1ポイント付与されます。
    <form action="" method="post">
        解答番号：<input type="number" name="num"><br>
        解答　　：<input type="text" name="answer"><br>
        <input type="submit" name="submit" value="解答する" ><hr>
    </form>
    <?php
        //フォーム内容を代入
        if(isset($_POST['num']) || isset($_POST['answer']))
        {
            $num = $_POST['num'];
            $answer = $_POST['answer'];
        }
        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //解答ボタンを押したとき
        if(isset($_POST['submit']))
        {
            //番号、解答がかかれているとき
            if($num != "" && $answer != "")
            {
                //解答番号のクイズを抽出
                $id = $num; 
                $sql = 'SELECT * FROM quiz WHERE id=:id ';
                $stmt = $pdo->prepare($sql);                  
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
                foreach ($results as $row)
                {
                    echo"[解答結果]<br>";
                    //解答が正しいとき
                    if($answer == $row['answer'])
                    {
                        ECHO"<FONT COLOR =\"GREEN\">正解です！おめでとうございます！<br></FONT>";
                        //ログイン中の時
                        if(isset($_SESSION['username']) && $_SESSION['username'] !="")
                        {
                            //ポイントを加算
                            $_SESSION['userpoint']+=1;
                            $sql = 'UPDATE userinfo SET point=:point WHERE name=:name';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':point', $_SESSION['userpoint'], PDO::PARAM_STR);
                            $stmt->bindParam(':name', $_SESSION['username'], PDO::PARAM_STR);
                            $stmt->execute();
                            echo "1ポイント付与されました。現在のポイントは".$_SESSION['userpoint']."ポイントです。<br>";
                        }
    
                    }
                    //解答が間違っているとき
                    else
                    {
                        ECHO"<FONT COLOR =\"BLUE\">残念！不正解です！<br></FONT>";
                    }
                    echo"解答：　".$row['answer']."<hr>";
                }
            }
            //エラー表示
            else
            {
                ECHO"<FONT COLOR =\"RED\">[解答失敗]<br></FONT>";
                if($num == "")
                {
                    ECHO"<FONT COLOR =\"RED\">解答番号を入力してください。<br></FONT>";
                }
                if($answer == "")
                {
                    ECHO"<FONT COLOR =\"RED\">解答を入力してください。<br></FONT>";
                }
                echo"<hr>";
            }
        }
    ?>
    [クイズ一覧]<br>
    <?php
        //クイズを表示
        $sql = 'SELECT * FROM quiz';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row)
        {
            echo $row['id'].',';
            echo $row['quiz']."<br>";
        }
    ?>
</body>
</html>