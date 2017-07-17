<?php

// For nav
$currentPage = 'signin';

// J'inclus le fichier de config
require '../inc/config.php';

// VOTRE CODE
// Initialisations
$errorList = array();

// Si formulaire soumis
if (!empty($_POST)) {
	//print_r($_POST);exit;
	// Récupération & traitement des données
	$email = isset($_POST['emailToto']) ? strip_tags(trim($_POST['emailToto'])) : '';
	$password = isset($_POST['passwordToto1']) ? trim($_POST['passwordToto1']) : '';

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

	if (empty($password)) {
		$formValid = false;
		$errorList[] = 'Le password est vide';
	}
	if (strlen($password) < 6) {
		$formValid = false;
		$errorList[] = 'Le password doit faire au moins 6 caractères';
	}

	// Si tout est ok
	if ($formValid) {
		// Récupère les données d'un user à partir de son email
		$userData = getUserByEmail($email);

		if ($userData !== false) {
			// Vérification du mot de passe
			if (password_verify($password, $userData['usr_password'])) {
				$successList[] = 'Utilisateur connecté';
				// TODO connect the user
				$_SESSION['userId'] = $userData['usr_id'];
				$_SESSION['userRole'] = $userData['usr_role'];
			}
			else {
				$errorList[] = 'Mot de passe incorrect';
			}
		}
		else {
			$errorList[] = 'Email non reconnu';
		}
	}
}
// FIN CODE

// J'inclus les vues
require dirname(dirname(__FILE__)).'/view/header.php';
require dirname(dirname(__FILE__)).'/view/signin.php';
require dirname(dirname(__FILE__)).'/view/footer.php';
