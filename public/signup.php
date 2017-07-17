<?php

// For nav
$currentPage = 'signup';

// J'inclus le fichier de config
require '../inc/config.php';

// VOTRE CODE
// Initialisations
$email = '';

if (!empty($_GET)) {
	  $id = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : '';

	  $_SESSION[] = $value;

	}
if (!empty($_POST)) {
	// Récupération & Traitement des données
	$email = isset($_POST['emailToto']) ? strip_tags(trim($_POST['emailToto'])) : '';
	$password1 = isset($_POST['passwordToto1']) ? trim($_POST['passwordToto1']) : '';
	$password2 = isset($_POST['passwordToto2']) ? trim($_POST['passwordToto2']) : '';

	// Validation des données
	$formValid = true;

	if (empty($email)) {
		$formValid = false;
		$errorList[] = 'L\'email est vide';
	}
	else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		$formValid = false;
		$errorList[] = 'L\'email est invalide';
	}

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

	// Vérifie si l'email est déjà en DB
	if ($formValid) {
		$userData = getUserByEmail($email);
		if ($userData !== false) {
			$formValid = false;
			$errorList[] = 'Un compte existe déjà pour cette adresse email';
		}
	}

	// Si tout est ok => on ajoute en DB
	if ($formValid) {
		$sql = "
			INSERT INTO user (usr_email, usr_password, usr_date_creation)
			VALUES (:email, :password, NOW())
		";
		// Prepare la requete
		$pdoStatement = $pdo->prepare($sql);
		// bindValues
		$pdoStatement->bindValue(':email', $email);
		$pdoStatement->bindValue(':password', password_hash($password1, PASSWORD_BCRYPT));

		// Execution
		if ($pdoStatement->execute() === false) {
			print_r($pdoStatement->errorInfo());

		}
		// Si aucun erreur SQL
		else {
			$successList[] = 'Votre inscription a bien été prise en compte';
			header('#?id='.$email);
		}
	}
}


// FIN CODE

// J'inclus les vues
require dirname(dirname(__FILE__)).'/view/header.php';
require dirname(dirname(__FILE__)).'/view/signup.php';
require dirname(dirname(__FILE__)).'/view/footer.php';
