<?php
require_once "config.php";
$pdo = getDB();
$game = $_GET["game"];
if($game==1){
    $gamename= "Snake";
}
else{
    $gamename="Flappy Bird";
}
$sql = "Select u.gamertag, g.name, s.score from scores s
join games g on g.id = s.game_id
join users u on u.id = s.users_id
where g.id=:gameid
order by score desc ;
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":gameid",$game );
$stmt->execute();
$rows = $stmt->fetchAll();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<h1>High Scores for <?=$gamename?> </h1>
<table class="table table-hover table-striped table-bordered table-dark">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Gamertag</th>
        <th scope="col">Game Name</th>
        <th scope="col">Score</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i=1;
    foreach($rows as $row)
    {
    ?>
        <tr>
            <th scrope="row"><?= $i?> </th>
            <td><?= $row["gamertag"] ?></td>
            <td><?= $row["name"]?></td>
            <td><?= $row["score"]?></td>
        </tr>
    <?php
        $i++;
    }
    ?>
    </tbody>
</table>
</body>
</html>
