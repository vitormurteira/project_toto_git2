
<!--modification-->


<?php

// J'inclus le fichier de config
require '../inc/config.php';

// VOTRE CODE

// Part 7
$sessionsList = array();
$sql = '
	SELECT ses_id, ses_number, ses_start_date, ses_end_date, tra_name, loc_id, loc_name
	FROM session
	INNER JOIN training ON training.tra_id = session.training_tra_id
	INNER JOIN location ON location.loc_id = session.location_loc_id
';
$sth = $pdo->query($sql);

// Si erreur
if ($sth === false) {
	print_r($sth->errorInfo());
}
else {
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
		if (!array_key_exists($row['loc_id'], $sessionsList)) {
			$sessionsList[$row['loc_id']] = array(
				'location' => $row['loc_name'],
				'training' => $row['tra_name'],
				'sessions' => array()
			);
		}

		$sessionsList[$row['loc_id']]['sessions'][$row['ses_id']] = array(
			'start' => $row['ses_start_date'],
			'end' => $row['ses_end_date'],
			'number' => $row['ses_number'],
		);
	}
}

// FIN CODE

// J'inclus les vues
require dirname(dirname(__FILE__)).'/view/header.php';
require dirname(dirname(__FILE__)).'/view/home.php';
require dirname(dirname(__FILE__)).'/view/footer.php';
