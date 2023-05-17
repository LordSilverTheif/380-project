<?php
require_once("config.php");
$pdo = getDB();
session_start();
    $score = $_GET["score"];
    $game = $_GET["game"];
    $gamertag = $_SESSION["id"];
//    echo $score;
//    echo $game;
//    echo $gamertag;

    $sql="Insert into scores (users_id, game_id, score) values (:gamertag,:gameid,:score)";
    $stmt = $pdo->prepare($sql);
//    echo "prepare";
    $stmt->bindParam(":gamertag", $gamertag);
    $stmt->bindParam(":gameid", $game);
    $stmt->bindParam(":score", $score);
//    echo "bind";
    $stmt->execute();
//    echo "execute";
?>
<!doctype html>
<html lang="en">
<head>
    <title>DeadState</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GameOver</title>
</head>
<body>
<header>
    Welcome to the Dead State.
</header>
<p>Your score was: <?= $score ?></p>
<a href="380-project/GameCenter/snakegame.html" class="btn btn-warning">Click here to play snake</a>
<a href="380-project/GameCenter/index.html" class="btn btn-danger"> Click here to play flappybird</a>
<a href="highscores.php?game=1" class="btn btn-light">Snake High Scores</a>
<a href="highscores.php?game=2" class="btn btn-dark">Flappy Bird High Scores</a>
<a href="welcome.php?" class="btn btn-secondary">Return to Home Page</a>
</body>
</html>