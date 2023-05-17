<?php
    require_once ("config.php");
    session_start();
    $pdo = getDB();
if(!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] == false){
    header("location: welcome.php");
    exit;
}
$message ="";
$message_err = "";
$postCreate = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //var_dump($_POST);
    $postId = $_POST["postToDelete"];
    $actionString = "action".$postId;
    $action= $_POST[$actionString];
    if($action==="flag")
    {
        $sql3 = "Update posts
        set is_flagged=true
        where id=:id";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->bindParam(":id",$postId );
        $stmt3->execute();
    }
    else if($action==="delete") {
        $sql2 = "Delete from posts where id=:id";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(":id",$postId );
        $stmt2->execute();
    }

}
    $sql = "Select p.user_id, p.message, p.timestamp, u.gamertag, p.id
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
<!--<form action="--><?php //echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><!--" method="post">-->
<!--    <div class="form-group">-->
<!--        <a href="welcome.php?" class="btn btn-warning">Return to Home Page</a>-->
<!--        <label>Type your message bellow, hit enter to post the message</label>-->
<!--        <input type="text" name="message" class="form-control --><?php //echo (!empty($message_err)) ? 'is-invalid' : ''; ?><!--" ">-->
<!--        <span class="invalid-feedback">--><?php //echo $message_err; ?><!--</span>-->
<!--    </div>-->
<!--    <div class="form-group">-->
<!--        <input type="submit" class="btn btn-primary" value="Submit">-->
<!--        <input type="reset" class="btn btn-secondary ml-2" value="Reset">-->
<!--    </div>-->
<!--</form>-->
<?php

    foreach($posts as $post)
    {
        $postId = $post["id"];
        ?>
            <div class="form-group">
                <div class="card" style="width: 18rem;">
        <!--            <img src="..." class="card-img-top" alt="...">-->
                    <div class="card-body">
                        <h5 class="card-title"><?= $post["gamertag"]?> posted at <?= $post["timestamp"]?></h5>
                        <p class="card-text"><?= $post["message"]?></p>
        <!--                <a href="#" class="btn btn-primary">Go somewhere</a>-->
                    </div>
                </div>
            </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="hidden" name="postToDelete" value="<?= $postId?>">
                <input type="radio" class="btn-check" name="action<?= $postId?>" value="flag" id="flag<?= $postId?>">
                <label for="flag<?= $postId?>" class="btn btn-warning">Flag Post</label>
                <input type="radio" class="btn-check" name="action<?= $postId?>" value="delete" id="delete<?= $postId?>">
                <label for="delete<?= $postId?>" class="btn btn-danger">Delete Post</label>
                <input type="submit" class="btn btn-secondary" value="Submit">
            </div>
        </form>
<?php
    }
?>
</body>
</html>
