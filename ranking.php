<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ランキング-クイズ投稿サイト</title>
</head>
<body>
    <h1>クイズ投稿サイト</h1><hr>
    <a href = "toppage.php">トップページ</a>/<a href = "login.php">ユーザー登録,ログイン</a>/<a href = "quiz.php">クイズを投稿する</a>/<a href = "ranking.php">ランキング</a><hr>
    <?php
        session_start();
        //ログイン中のとき
        if(!empty($_SESSION['username']) && !empty($_SESSION['userpoint']))
        {
            echo"ログイン中：".$_SESSION['username']."さん現在のポイントは".$_SESSION['userpoint']."点です。<hr>";
        }
        else
        {
            echo"未ログイン：ユーザー登録、ログインをするとポイントが付与されるようになります。<hr>";
        }
    ?>
    <h2>ポイントランキング</h2>
    <!--表の項目を作成-->
    <table width="50%" border="1">
         <tr>
          <th scope="col">順位</th>
          <th scope="col">ユーザー名</th>
          <th scope="col">ポイント</th>
         </tr>
    <?php
        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //ポイントを降順にで抽出
        $sql = 'SELECT*FROM userinfo ORDER BY point DESC ';
        $stmt = $pdo->prepare($sql);                  
        $stmt->execute();                             
        $results = $stmt->fetchAll();
        $i = 0;
        //ランキングを表示
        foreach ($results as  $row)
        {
            $i++;
    ?>
    <tr>
      <td><?php print(htmlspecialchars($i)."位"); ?> </td>
      <td><?php print(htmlspecialchars($row['name'])); ?> </td>
      <td><?php print(htmlspecialchars($row['point'])); ?> </td>
    </tr>
    <?php
    }
    ?>
    </table>
</body>
</html>      