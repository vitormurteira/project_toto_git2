<?php

// For nav
$currentPage = 'add';

// J'inclus le fichier de config
require '../inc/config.php';

if(!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'admin'){
  header('Location: 403.php');
	exit;
}
else{

	// Formulaire soumis, comme toujours
	if (!empty($_POST)) {
		//print_r($_POST);exit;
		// Je récupère les données en GET
		$lastname = filterStringInputPost('stu_lastname');
		$firstname = filterStringInputPost('stu_firstname');
		$email = filterStringInputPost('stu_email');
		$birthdate = filterStringInputPost('stu_birthdate');
		$friendliness = filterIntInputPost('stu_friendliness', -1);
		$sessionId = filterIntInputPost('ses_id');
		$cityId = filterIntInputPost('cit_id');

		// Le tableau contenant toutes les valeurs
		$errorList = array();

		// Je teste toutes les erreurs, autre modif
		if (empty($lastname)) {
			$errorList[] = 'Le nom est vide';
		}
		else if (strlen($lastname) < 2) {
			$errorList[] = 'Le nom est incorrect';
		}
		if (empty($firstname)) {
			$errorList[] = 'Le prénom est vide';
		}
		else if (strlen($firstname) < 2) {
			$errorList[] = 'Le prénom est incorrect';
		}
		if (empty($email)) {
			$errorList[] = 'L\'email est vide';
		}
		else if (filter_var($email,  FILTER_VALIDATE_EMAIL) === false) {
			$errorList[] = 'L\'email est incorrect';
		}
		if (empty($birthdate)) {
			$errorList[] = 'La date de naissance est vide';
		}
		if ($friendliness < 0 || $friendliness > 5) {
			$errorList[] = 'La sympathie est incorrecte';
		}
		if (empty($sessionId)) {
			$errorList[] = 'La session n\'est pas renseignée';
		}
		if (empty($cityId)) {
			$errorList[] = 'La ville n\'est pas renseignée';
		}

		// Si aucune erreur
		if (empty($errorList)) {
			// J'insère en DB
			$studentId = 0;

			$sql = '
				INSERT INTO student (stu_lastname, stu_firstname, stu_email, stu_birthdate, stu_friendliness, session_ses_id, city_cit_id)
				VALUES (:lastname, :firstname, :email, :birthdate, :friendliness, :ses_id, :cit_id)
			';
			$sth = $pdo->prepare($sql);
			$sth->bindValue(':lastname', $lastname);
			$sth->bindValue(':firstname', $firstname);
			$sth->bindValue(':email', $email);
			$sth->bindValue(':birthdate', $birthdate);
			$sth->bindValue(':friendliness', $friendliness, PDO::PARAM_INT);
			$sth->bindValue(':ses_id', $sessionId, PDO::PARAM_INT);
			$sth->bindValue(':cit_id', $cityId, PDO::PARAM_INT);

			if ($sth->execute() === false) {
				print_r($sth->errorInfo());
			}
			else {
				// Je récupère l'ID auto-incrémenté
				$studentId = $pdo->lastInsertId();
			}

			if (empty($errorList)) {
				// Je redirige sur sa page
				header('Location: edit.php?id='.$studentId);
				exit;
			}
		}
	}

	// Je récupère toutes les villes
	$citiesList = getAllCities();

	// Je récupère toutes les sessions
	$sessionsList = getAllSessions();

	// Pour éviter les notices dans la vue, j'initialise mon tableau de données
	$studentInfos = array(
		'stu_id' => 0,
		'stu_lastname' => '',
		'stu_firstname' => '',
		'stu_email' => '',
		'stu_birthdate' => '',
		'stu_friendliness' => '',
		'session_ses_id' => '',
		'city_cit_id' => '',
		'cit_name' => '',
		'stu_age' => '',
	);

	// J'inclus les vues
	require dirname(dirname(__FILE__)).'/view/header.php';
	require dirname(dirname(__FILE__)).'/view/add.php';
	require dirname(dirname(__FILE__)).'/view/footer.php';
}
