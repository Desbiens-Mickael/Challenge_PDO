<?php
require_once './connec.php';
$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST)) {
    $data = array_map('trim', $_POST);
    $data = array_map('htmlentities', $data);
    $errors = [];
    
    if (empty($data['firstname'])) {
        $errors['firstname_empty'] = "Si vous plaît, veuillez renseignez tous les champs";
    }

    if (empty($data['lastname'])) {
        $errors['lastname_empty'] = "Si vous plaît, veuillez renseignez tous les champs";
    }

    if (strlen($data['firstname']) > 45) {
        $errors['firstname_length'] = "Trop long, 45 caractères maximum";
    }

    if (strlen($data['lastname']) > 45) {
        $errors['lastname_length'] = "Trop long, 45 caractères maximum";
    }

    if (empty($errors)) {
        $query = "INSERT INTO `friend` (firstname, lastname) VALUES (:firstname, :lastname)";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $data['firstname']);
        $statement->bindValue(':lastname', $data['lastname']);
        $statement->execute();
        header("Location: /");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
</head>

<body>
    <h1>Liste des friends</h1>
    <ul>
        <?php foreach ($friends as $friend) { ?>
            <li><?Php echo $friend['firstname'] . ' ' . $friend['lastname']; ?></li>
        <?php } ?>
    </ul>

    <form action="" method="POST">
        <p>
            <?php if (isset($errors['firstname_empty'])) : ?>
                <p><?= $errors['firstname_empty'] ?></p>
            <?php endif; ?>

            <?php if (isset($errors['firstname_length'])) : ?>
                <p><?= $errors['firstname_length'] ?></p>
            <?php endif; ?>

            <label for="firstname">Firstname</label>
            <input type="text" name="firstname" id="firstname" required maxlength="45">
        </p>

        <p>
            <?php if (isset($errors['lastname_empty'])) : ?>
                <p><?= $errors['lastname_empty'] ?></p>
            <?php endif; ?>

            <?php if (isset($errors['lastname_length'])) : ?>
                <p><?= $errors['lastname_length'] ?></p>
            <?php endif; ?>

            <label for="lastname">Lastname</label>
            <input type="text" name="lastname" id="lastname" required maxlength="45">
        </p>

        <button type="submit">Submit</button>

    </form>
</body>

</html>