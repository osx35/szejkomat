
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podaruj</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="form">
    <h1>Podaruj potrząśnięcie</h1>
    <?php
    if(isset($_COOKIE['name'])){
        echo "<p>Witaj, " . htmlspecialchars($_COOKIE['name']) . "!</p>";
    } else {
        header('Location: index.php');
    }
    ?>
    <form action="increment_shakes.php" method="post">
        <button type="submit" name="give_shake">Podaruj potrząśnięcie</button>
    </form>
</div>
</body>
</html>
