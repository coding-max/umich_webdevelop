<?php
    session_start();
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');

    if(isset($_POST['cancel'])) {
        header( 'Location: index.php' ) ;
        return;
    }

    if ( isset($_POST['first_name']) && isset($_POST['last_name'])
        && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {

        // Data validation
        if ( (strlen($_POST['first_name']) < 1) || (strlen($_POST['last_name']) < 1) || (strlen($_POST['headline']) < 1) || (strlen($_POST['summary']) < 1) ) {
            $_SESSION['error'] = 'Missing data';
            header("Location: edit.php?profile_id=".$_POST['profile_id']);
            return;
        }
        if (!(strpos($_POST['email'], '@') !== false)) {
            $_SESSION["error"] = "Email must have an at-sign (@).";
            header("Location: add.php");
            return;
        }

        $sql = "UPDATE profile SET first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su WHERE profile_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':fn' => htmlentities($_POST['first_name']),
            ':ln' => htmlentities($_POST['last_name']),
            ':em' => htmlentities($_POST['email']),
            ':he' => htmlentities($_POST['headline']),
            ':su' => htmlentities($_POST['summary']),
            ':id' => $_POST['profile_id']));
        $_SESSION['success'] = 'Record updated';
        header( 'Location: index.php' ) ;
        return;
    }

    // Guardian: Make sure that profile_id is present
    if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
    }

    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for profile_id';
        header( 'Location: index.php' ) ;
        return;
    }

    $fn = htmlentities($row['first_name']);
    $ln = htmlentities($row['last_name']);
    $em = htmlentities($row['email']);
    $he = htmlentities($row['headline']);
    $su = htmlentities($row['summary']);
    $id = $row['profile_id'];
?>

<!DOCTYPE html>
   <head>
        <title>Maximiliano Pan - Edit</title>-
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
            <h1>Editing Profile for
                <?php if ( isset($_SESSION['name']) ) {
                    echo htmlentities($_SESSION['name']);
                }?>
            </h1>

            <?php
                // Flash pattern
                if ( isset($_SESSION['error']) ) {
                    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
                    unset($_SESSION['error']);
               } ?>
            <form method="post">
                <p>First Name:<input type="text" name="first_name" value="<?= $fn ?>"></p>
                <p>Last Name:<input type="text" name="last_name" value="<?= $ln ?>"></p>
                <p>Email:<input type="text" name="email" value="<?= $em ?>"></p>
                <p>Headline:<input type="text" name="headline" value="<?= $he ?>"></p>
                <p>Summary:<input type="text" name="summary" value="<?= $su ?>"></p>
                <input type="hidden" name="profile_id" value="<?= $id ?>">
                <input type="submit" value="Save"/>
                <input type="submit" value="Cancel" name="cancel"/>
            </form>
        <?php } ?>
    </body>
</html>