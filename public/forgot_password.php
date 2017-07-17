<?php

require dirname(dirname(__FILE__)).'/inc/functions.php';

//2- Si existe :

if(!empty($_POST)){

  $email = isset($_POST['email']) ? strip_tags(trim($_POST['email'])) : '';
}

$sql = '
  SELECT *
  FROM user
  WHERE usr_email = :email
';

$user = $pdo->prepare($sql);

$user->bindValue(':email', $email);

if ($user->execute() === false) {
  print_r($user->errorInfo());
}
else {
  // Je récupère l'ID auto-incrémenté
  $userId = $user['usr_id'];
  $email = $user['usr_email'];
}

//* on génère un token

$salt = 'mysalt';
$token = md5($id.$salt));
$expir = time()+

//* on sauvegarde en DB (+lifetime)

$sql = '
  UPDATE user
  SET usr_token = '$token', usr_tokenExpir = '$expir')
  WHERE usr_id = '$studentId'
';

$pdo->exec($sql);

//* on envoie un email au client avec un lien (avec token)

passwordRecoveryEmail($email, $email, $token);

//1- Demande de saisie de l'email (form)
<form action="" method="post">
	<fieldset>
		<legend>Request password reset</legend>
		<input type="text" name="email" value="" placeholder="email" /><br />
		<input type="submit" value="" />
	</fieldset>
</form>
 ?>
