<?php
    require_once ("config.php");
    session_start();
    $pdo = getDB();
$message ="";
$message_err = "";
$postCreate = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

// Validate message
    if(empty(trim($_POST["message"]))){
        $gamertag_err = "Message Cannot be blank";
    } else{
        $sql2 = "Insert into posts (user_id, message, timestamp) value(:user_id, :message, :timestamp)";
        $stmt2 = $pdo->prepare($sql2);
        $time  = date("Y-m-d H:i:s");
        $userId = $_SESSION["id"];
        $message = $_POST["message"];
        $stmt2->bindParam(":user_id", $userId);
        $stmt2->bindParam("message", $message);
        $stmt2->bindParam(":timestamp",$time );
        $stmt2->execute();
    }
}
    $sql = "Select p.user_id, p.message, p.timestamp, u.gamertag, p.is_flagged
            from posts p
            join users u on u.id = p.user_id order by p.timestamp desc";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    $postCreate = "Post has been succsessfully created";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Chat Room</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group">
        <a href="welcome.php?" class="btn btn-warning">Return to Home Page</a>
        <label>Type your message bellow, hit enter to post the message</label>
        <input type="text" name="message" class="form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>" ">
        <span class="invalid-feedback"><?php echo $message_err; ?></span>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Submit">
        <input type="reset" class="btn btn-secondary ml-2" value="Reset">
    </div>
</form>
<?php

    foreach($posts as $post)
    {
        ?>
        <div class="card" style="width: 18rem;">
<!--            <img src="..." class="card-img-top" alt="...">-->
            <div class="card-body">
                <?php
                    if($post["is_flagged"]==true)
                {
                    ?>
                    <h5 class="warning">WARNRING THIS POST HAS BEEN FLAGGED BY ADMIN</h5>
                <?php
                }
                ?>
                <h5 class="card-title"><?= $post["gamertag"]?> posted at <?= $post["timestamp"]?></h5>
                <p class="card-text"><?= $post["message"]?></p>
<!--                <a href="#" class="btn btn-primary">Go somewhere</a>-->
            </div>
        </div>
<?php
    }
?>
</body>
</html>
