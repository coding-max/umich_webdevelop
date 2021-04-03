<?php
    session_start();
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');

    $stmt = $pdo->prepare("SELECT first_name, last_name, email, headline, summary, profile_id FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for profile_id';
        header( 'Location: index.php' ) ;
        return;
    }
?>

<!DOCTYPE html>
<head>
    <title>Maximiliano Pan - Profile information</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
        <div class="container">
            <h1>Profile information</h1>
            <p>First Name: <?= htmlentities($row['first_name']) ?></p>
            <p>Last Name: <?= htmlentities($row['last_name']) ?></p>
            <p>Email: <?= htmlentities($row['email']) ?></p>
            <p>Headline: <?= htmlentities($row['headline']) ?></p>
            <p>Summary: <?= htmlentities($row['summary']) ?></p>
            <a href="index.php">Done</a>
        </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
