<?php

// J'inclus le fichier de config
require '../inc/config.php';

if(!isset($_SESSION['userRole'])){
  header('Location: signin.php');
	exit;
}
else{

	$studentId = isset($_GET['id']) ? intval(trim($_GET['id'])) : 0;
	$studentLastname = '';
	$studentFirstname = '';
	$studentEmail = '';
	$studentBirthdate = '';
	$studentAge = '';
	$studentFriendliness = '';
	$studentCity = '';
	$studentCountry = '';

	$sql = '
		SELECT stu_id, stu_lastname, stu_firstname, cou_name, cit_name, stu_friendliness, stu_email, stu_birthdate
		FROM student
		LEFT OUTER JOIN city ON city.cit_id = student.city_cit_id
		LEFT OUTER JOIN country ON country.cou_id = city.country_cou_id
		WHERE stu_id = :idStudent
	';
	$pdoStatement = $pdo->prepare($sql); //=>prepare car donnée "externe"
	$pdoStatement->bindValue(':idStudent', $studentId, PDO::PARAM_INT);

	if ($pdoStatement->execute() === false) {
		print_r($pdo->errorInfo());
	}
	else {
		$studentInfos = $pdoStatement->fetch(PDO::FETCH_ASSOC);
		//print_r($studentListe);
		$studentLastname = $studentInfos['stu_lastname'];
		$studentFirstname = $studentInfos['stu_firstname'];
		$studentEmail = $studentInfos['stu_email'];
		$studentBirthdate = $studentInfos['stu_birthdate'];
		$studentFriendliness = getSympathieLabelFromId($studentInfos['stu_friendliness']);
		$studentAge = calculAge($studentBirthdate);
		$studentCity = $studentInfos['cit_name'];
		$studentCountry = $studentInfos['cou_name'];
	}

	// J'inclus les vues
	require dirname(dirname(__FILE__)).'/view/header.php';
	require dirname(dirname(__FILE__)).'/view/student.php';
	require dirname(dirname(__FILE__)).'/view/footer.php';
}
