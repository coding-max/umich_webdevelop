<?php
    session_start();
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');

    if(isset($_POST['cancel'])) {
        header( 'Location: index.php' ) ;
        return;
    }

    // Check to see if we have some POST data, if we do process it
    if ( isset($_POST['first_name']) && isset($_POST['last_name'])
    && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {
        if ( (strlen($_POST['first_name']) < 1) || (strlen($_POST['last_name']) < 1) || (strlen($_POST['headline']) < 1) || (strlen($_POST['summary']) < 1) ) {
            $_SESSION['error'] = 'All values are required';
            header("Location: add.php");
            return;
        }
        if (!(strpos($_POST['email'], '@') !== false)) {
            $_SESSION["error"] = "Email must have an at-sign (@).";
            header("Location: add.php");
            return;
        }

        $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary)
                                VALUES ( :uid, :fn, :ln, :em, :he, :su)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'])
        );
        $_SESSION['success'] = 'Record Added';
        header( 'Location: index.php' ) ;
        return;
    }
?>

<!DOCTYPE html>
    <head>
        <title>Maximiliano Pan - Add</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    </head>
    <body>
        <?php   
            // Check if we are logged in!
            if ( ! isset($_SESSION["name"]) ) { 
            die("Not logged in");
            } else { 
        ?>
            <div class="container">
                <h1>Adding Profile for 
                    <?php if ( isset($_SESSION['name']) ) {
                            echo htmlentities($_SESSION['name']);
                    }?>
                </h1>
                <?php
                    // Flash pattern
                    if ( isset($_SESSION['error']) ) {
                        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
                        unset($_SESSION['error']);
                    }
                ?>
                <form method="POST">
                    <p>First Name: <input type="text" name="first_name" size="60" /></p>
                    <p>Last Name: <input type="text" name="last_name" size="60" /></p>
                    <p>Email:<input type="text" name="email"/></p>
                    <p>Headline:<input type="text" name="headline"/></p>
                    <p>Summary:<input type="text" name="summary"/></p>
                    <input type="submit" value="Add">
                    <input type="submit" value="Cancel" name="cancel">
                </form>
            </div>
        <?php } ?>
        <!--<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>-->
    </body>
</html>