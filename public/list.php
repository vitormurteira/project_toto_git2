<?php

// For nav
$currentPage = 'list';

// J'inclus le fichier de config
require dirname(dirname(__FILE__)).'/inc/config.php';

	// Option 2 : suppression student
	if (!empty($_GET['deleteStudentId'])) {
		$studentIdToDelete = intval($_GET['deleteStudentId']);
    if(!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'admin'){
      header('Location: 403.php');
    	exit;
    }
    else{
  		if ($studentIdToDelete > 0) {
  			$sql = '
  				DELETE FROM student WHERE stu_id = :studentId
  			';
  			$sth = $pdo->prepare($sql);
  			$sth->bindValue(':studentId', $studentIdToDelete, PDO::PARAM_INT);
  			if ($sth->execute() == false) {
  				print_r($sth->errorInfo());
  			}
  			else {
  				header('Location: list.php');
  				exit;
  			}
  		}
    }
	}

	// Je récupère la donnée (page) de l'URL (list.php?page=xxx)
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	// Je récupère la donnée (recherche) de l'URL (list.php?s=xxx)
	$search = isset($_GET['s']) ? trim($_GET['s']) : '';
	// Je récupère la donnée (session) de l'URL (list.php?ses_id=xxx)
	$sessionId = isset($_GET['ses_id']) ? intval($_GET['ses_id']) : 0;

	// Je calcule l'offset correspondant à la page
	$offset = $page * 5 - 5;

	// Si recherche
	if (!empty($search)) {
		$sql = '
			SELECT stu_id, stu_lastname AS stu_lname, stu_firstname AS stu_fname, stu_email, stu_birthdate AS birthdate,
				0 AS total
			FROM student
			LEFT OUTER JOIN city ON city.cit_id = student.city_cit_id
			WHERE stu_lastname LIKE :search
			OR stu_firstname LIKE :search
			OR stu_email LIKE :search
			OR cit_name LIKE :search
		';
	}
	else {
		// L'offset est changé dans la requête selon la page
		$sql = '
			SELECT stu_id, stu_lastname AS stu_lname, stu_firstname AS stu_fname, stu_email, stu_birthdate AS birthdate,
				T.total
			FROM student
		';

		// Si filtre par SESSION_ID
		if ($sessionId > 0)  {
			$sql .= '
				LEFT OUTER JOIN (
					SELECT count(*) AS total
					FROM student
					WHERE student.session_ses_id = :sessionId
				) AS T ON T.total > 0
				WHERE student.session_ses_id = :sessionId
			';
		}
		else {
			$sql .= '
				LEFT OUTER JOIN (
					SELECT count(*) AS total
					FROM student
				) AS T ON T.total > 0
			';
		}
		$sql .= '
			LIMIT 5 OFFSET '.$offset.'
		';
	}
	$pdoStatement = $pdo->prepare($sql); //=>prepare car donnée "externe" si recherche

	if (!empty($search)) {
		// Je mets les % dans le bindValue, sinon ça ne marche pas !
		$pdoStatement->bindValue(':search', "%{$search}%");
	}
	if ($sessionId > 0)  {
		$pdoStatement->bindValue(':sessionId', $sessionId, PDO::PARAM_INT);
	}

	if ($pdoStatement->execute() === false) {
		print_r($pdo->errorInfo());
	}
	else {
		$studentListe = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
		//print_r($studentListe);
		$maxPageNum = ceil($studentListe[0]['total'] / 5);
	}

	// Texte affiché en haut du <table> des étudiants
	// Change selon le contexte : liste complète, liste pour 1 session, recherche
	if (!empty($search)) {
		$introductionText = count($studentListe).' résultat(s) pour le mot "'.htmlentities($search).'"';
	}
	else if ($sessionId > 0) {
		$introductionText = 'Etudiants pour la session';
	}
	else {
		$introductionText = 'Les étudiants des formations Webforce3 dans le monde entier !';
	}

	// J'inclus les vues
	require dirname(dirname(__FILE__)).'/view/header.php';
	require dirname(dirname(__FILE__)).'/view/list.php';
	require dirname(dirname(__FILE__)).'/view/footer.php';
