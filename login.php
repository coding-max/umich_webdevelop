<?php
    session_start();

    if(isset($_POST['cancel'])) {
        header( 'Location: index.php' ) ;
        return;
    }

    if ( isset($_POST["email"]) && isset($_POST["pass"]) ) {
        unset($_SESSION["email"]);
        $salt = 'XyZzy12*_';
        $check = hash('md5', $salt.$_POST['pass']);
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $row !== false ) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;
        } else {
            $_SESSION["error"] = "Incorrect email or password";
            header( 'Location: login.php' ) ;
            return;
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once "bootstrap.php"; ?>
        <title>Maximiliano Pan - Login</title>
    </head>
    <body>
        <div class="container">
            <h1>Please log in</h1>
            <?php
                if ( isset($_SESSION["error"]) ) {
                    echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
                    unset($_SESSION["error"]);
                }
            ?>
            <form method="post">
                Email <input type="text" name="email" id="id_1723"><br/>
                Password <input type="text" name="pass"><br/>
                <input type="submit"  onclick="return doValidate();" value="Log In">
                <input type="submit" value="Cancel" name="cancel">
            </form>
            <script>
                function doValidate() {
                    console.log('Validating...');
                    try {
                        email = document.getElementById('id_1723').value;
                        console.log("Validating email="+email);
                        if ( (email == null || email == "") || (!(email.includes('@'))) ) {
                            alert("Invalid email address");
                            return false;
                        }
                        return true;
                    } catch(e) {
                        return false;
                    }
                    return false;
                }
            </script>
        </div>
    </body>
</html>
