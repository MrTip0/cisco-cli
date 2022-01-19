<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cisco Cli</title>
    <style>
        body {
            display: grid;
            place-items: center;
            width: 100vw;
            height: 100vh;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    <?php
        $r = "";
        if (!empty($_POST)) {
            include 'cli.php';
            $r = extend($_POST["command"]);
        }
    ?>
    <form action="" method="post">
        <textarea name="command" cols="30" rows="10"><?php echo $r;?></textarea>
        <input type="submit" value="Submit">
    </form>
</body>
</html>