<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";
$pdo = getDB();
// Define variables and initialize with empty values
$gamertag = $password = "";
$gamertag_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if gamertag is empty
    if(empty(trim($_POST["gamertag"]))){
        $gamertag_err = "Please enter gamertag.";
    } else{
        $gamertag = trim($_POST["gamertag"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($gamertag_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, gamertag, password, is_admin FROM users WHERE gamertag = :gamertag";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":gamertag", $param_gamertag, PDO::PARAM_STR);

            // Set parameters
            $param_gamertag = trim($_POST["gamertag"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if gamertag exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $gamertag = $row["gamertag"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["gamertag"] = $gamertag;
                            $_SESSION["is_admin"] = $row["is_admin"];

                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid gamertag or password.";
                        }
                    }
                } else{
                    // gamertag doesn't exist, display a generic error message
                    $login_err = "Invalid gamertag or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>

    <?php
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>gamertag</label>
            <input type="text" name="gamertag" class="form-control <?php echo (!empty($gamertag_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $gamertag; ?>">
            <span class="invalid-feedback"><?php echo $gamertag_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </form>
</div>
</body>
</html>