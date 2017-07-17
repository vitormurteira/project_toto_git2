<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Projet TOTO</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<style type="text/css">
		body { padding-top: 70px; }
	</style>
</head>
<body>

	<div class="container">

		<nav class="navbar navbar-default navbar-fixed-top">
		  <div class="container">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <a class="navbar-brand" href="./">Home</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li<?= $currentPage == 'home' ? ' class="active"' : '' ?>><a href="index.php">Toutes les sessions</a></li>
		        <li<?= !isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'admin' ? ' class="hidden"':''?>
            <?= $currentPage == 'list' ? ' class="active"' : '' ?>><a href="list.php">Tous les étudiants</a></li>
		        <li<?= !isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'admin' ? ' class="hidden"':''?>
            <?= $currentPage == 'add' ? ' class="active"' : '' ?>><a href="add.php">Ajouter un étudiant</a></li>
		        <li<?= $currentPage == 'signin' ? ' class="active"' : '' ?>><a href="signin.php">Sign in</a></li>
		        <li<?= $currentPage == 'signup' ? ' class="active"' : '' ?>><a href="signup.php">Sign up</a></li>
		      </ul>
		      <form action="list.php" class="navbar-form navbar-right">
		        <div class="form-group">
		          <input type="text" name="s" class="form-control" placeholder="Recherche">
		        </div>
		        <button type="submit" class="btn btn-default">Rechercher</button>
		      </form>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>

		<?php print_r($_SESSION); if (!empty($errorList)) : ?>
		<div class="alert alert-danger">
			<?php foreach ($errorList as $currentError) : ?>
				<?= $currentError ?><br>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if (!empty($successList)) : ?>
		<div class="alert alert-success">
			<?php foreach ($successList as $currentError) : ?>
				<?= $currentError ?><br>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
