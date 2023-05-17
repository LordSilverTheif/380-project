<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
<header>
    Hi, <b><?php echo htmlspecialchars($_SESSION["gamertag"]); ?></b>. Welcome to the Game Center.
</header>
<div class="optionbuttons">
    <ul>
        <li><a href="380-project/GameCenter/snakegame.html" class="btn btn-light">Play Snake</a></li>
        <li><a href="380-project/GameCenter/index.html" class="btn btn-secondary">Play Flappy Bird</a></li>
        <li><a href="highscores.php?game=1" class="btn btn-info">Snake High Scores</a></li>
        <li><a href="highscores.php?game=2" class="btn btn-dark">Flappy Bird High Scores</a></li>
        <li><a href="feed.php?" class="btn btn-danger">Post page</a></li>
        <?php
            if($_SESSION["is_admin"]==true)
            {
                ?>
                <li><a href="admin_feed.php" class="btn btn-success">Moderate Posts</a></li>
        <?php
            }
        ?>
        <li><a href="reset-password.php" class="btn btn-warning">Reset Your Password</a></li>
        <li><a href="logout.php" class="btn btn-success">Sign Out of Your Account</a></li>
    </ul>
</div>
</body>
</html>