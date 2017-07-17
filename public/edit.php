<?php

// For nav
$currentPage = 'edit';

// J'inclus le fichier de config
require '../inc/config.php';

if(!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'admin'){
  header('Location: 403.php');
	exit;
}
else{

	// Je récupère le paramètre dans l'URL
	$studentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

	// Formulaire soumis
	if (!empty($_POST)) {
		//print_r($_FILES);exit;
		// Je récupère les données en GET
		$lastname = filterStringInputPost('stu_lastname');
		$firstname = filterStringInputPost('stu_firstname');
		$email = filterStringInputPost('stu_email');
		$birthdate = filterStringInputPost('stu_birthdate');
		$friendliness = filterIntInputPost('stu_friendliness', -1);
		$sessionId = filterIntInputPost('ses_id');
		$cityId = filterIntInputPost('cit_id');
		$imageFilename = '';

		// Le tableau contenant toutes les valeurs
		$errorList = array();

		// Je teste toutes les erreurs
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
			// Si des fichiers ont été uploadés
			if (!empty($_FILES)) {
				// Je parcours tous les uploads
				foreach ($_FILES as $inputName => $fileData) {
					// Crée un tableau de string, à partir du string du nom du fichier
					$tmpExplode = explode('.', $fileData['name']);
					// Renvoi la valeur du dernier élément du tableau
					$extension = strtolower(end($tmpExplode));

					// Je check si l'extension est incorrecte
					if (!in_array($extension, array('svg', 'jpg', 'jpeg', 'png', 'gif'))) {
						echo 'Fichier invalide<br>';
						exit;
					}
					// Vérification du poids max
					else if ($fileData['size'] > 200*1024) {
						echo 'Fichier trop lourd<br>';
						exit;
					}
					else {
						// Nom fichier uploadé
						$imageFilename = md5('projet@Toto'.time().$studentId).'.'.$extension;

						//echo 'copy from '.$fileData['tmp_name'].'<br>';
						//echo 'to '.$uploadedFilename.'<br>';
						if (move_uploaded_file($fileData['tmp_name'], __UPLOAD_DIR__.$imageFilename)) {
							// Voir pour afficher un message "success"
						}
						else {
							//echo 'Error in upload<br>';
							$imageFilename = '';
						}
					}
				}
			}
			else {
				echo 'aucun fichier uploadé<br>';
			}
			// Je mets à jour la DB
			$updateSuccess = false;
			$sql = '
				UPDATE student
				SET stu_lastname = :lastname,
				stu_firstname = :firstname,
				stu_email = :email,
				stu_birthdate = :birthdate,
				stu_friendliness = :friendliness,
				stu_image = :image,
				session_ses_id = :ses_id,
				city_cit_id = :cit_id
				WHERE stu_id = :id
			';
			$sth = $pdo->prepare($sql);
			$sth->bindValue(':id', $studentId);
			$sth->bindValue(':lastname', $lastname);
			$sth->bindValue(':firstname', $firstname);
			$sth->bindValue(':email', $email);
			$sth->bindValue(':birthdate', $birthdate);
			$sth->bindValue(':image', $imageFilename);
			$sth->bindValue(':friendliness', $friendliness, PDO::PARAM_INT);
			$sth->bindValue(':ses_id', $sessionId, PDO::PARAM_INT);
			$sth->bindValue(':cit_id', $cityId, PDO::PARAM_INT);

			if ($sth->execute() === false) {
				print_r($sth->errorInfo());
				$errorList[] = 'Erreur dans la requête de mise à jour';
			}
			else {
				$updateSuccess = true;
			}

			if (empty($errorList)) {
				// Je redirige sur la meme page
				header('Location: edit.php?id='.$studentId);
				exit;
			}
		}
	}

	$studentInfos = array();
	$sql = '
		SELECT stu_id, stu_lastname, stu_firstname, stu_email, stu_birthdate, stu_friendliness, stu_image, cit_name, session_ses_id, city_cit_id
		FROM student
		INNER JOIN city ON city.cit_id = student.city_cit_id
		WHERE stu_id = :studentId
	';
	$sth = $pdo->prepare($sql);
	$sth->bindValue(':studentId', $studentId,  PDO::PARAM_INT);

	if ($sth->execute() === false) {
		print_r($sth->errorInfo());
	}
	else {
		$studentInfos = $sth->fetch(PDO::FETCH_ASSOC);
		// Calcule l'age avec la fonction "getAgeFromTimestamp"
		$studentInfos['age'] = calculAge($studentInfos['stu_birthdate']);
	}

	// Je récupère toutes les villes
	$citiesList = getAllCities();

	// Je récupère toutes les sessions
	$sessionsList = getAllSessions();

	// J'inclus les vues
	require dirname(dirname(__FILE__)).'/view/header.php';
	require dirname(dirname(__FILE__)).'/view/add.php';
	require dirname(dirname(__FILE__)).'/view/footer.php';
}
