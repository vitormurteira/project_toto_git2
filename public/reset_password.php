<?php

//6- Suppression du token en DB

session_start();

if (!empty($_POST)) {
	// Récupération & Traitement des données
  $password1 = isset($_POST['pass1']) ? trim($_POST['pass1']) : '';
  $password2 = isset($_POST['pass2']) ? trim($_POST['pass2']) : '';

  // Validation des données
  $formValid = true;

if (empty($password1) || empty($password2)) {
  $formValid = false;
  $errorList[] = 'Le password est vide';
}
if ($password1 !== $password2) {
  $formValid = false;
  $errorList[] = 'Les password sont différents';
}
if (strlen($password1) < 6 || strlen($password2) < 6) {
  $formValid = false;
  $errorList[] = 'Le password doit faire au moins 6 caractères';
}

// Si tout est ok => on ajoute en DB
if ($formValid) {
  $sql = "
    UPDATE user
    SET usr_password = :password, usr_token = ''
    WHERE usr_id = :id
  ";
  // Prepare la requete
  $pdoStatement = $pdo->prepare($sql);
  // bindValues
  $pdoStatement->bindValue(':id', $_SESSION['id']);
  $pdoStatement->bindValue(':password', password_hash($password1, PASSWORD_BCRYPT));

  // Execution
  if ($pdoStatement->execute() === false) {
    print_r($pdoStatement->errorInfo());

  }
  // Si aucun erreur SQL
  else {
    $successList[] = 'Password updated';
    header('signin.php');
  }
}

//3- Client clique qui sur lien => page modification du mot de passe (form)

if(!empty($_GET)){

  $token = isset($_GET['token']) ? strip_tags(trim($_GET['token'])) : '';

  $sql = '
    SELECT *
    FROM user
    WHERE usr_token = :token
  ';

  $user = $pdo->prepare($sql);

  $user->bindValue(':token', $token);

  //4- Vérification du token

  if ($user->execute() === false) {
    print_r($user->errorInfo());
  }
  else {
    // Je récupère l'ID auto-incrémenté
    $_SESSION['id']=$user['usr_id'];
    $_SESSION['email']=$user['usr_email'];

    //5- Modification du password en DB

    <form action="" method="post">
    	<fieldset>
    		<legend>Reset password</legend>
    		<input type="text" name="pass1" value="" placeholder="password" /><br />
    		<input type="text" name="pass2" value="" placeholder="repeat password"/><br />
        <input type="submit" value="" />
    	</fieldset>
    </form>
  }
}
 ?>
