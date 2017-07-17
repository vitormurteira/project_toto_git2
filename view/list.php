<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Liste des étudiants</h3>
  </div>
  <div class="panel-body">
    <?= $introductionText ?><br><br>
    <div class="col-md-3 text-left">
    <?php if ($page > 1) : ?>
    	<a href="list.php?page=<?= ($page-1) ?>&ses_id=<?=$sessionId?>" class="btn btn-sm btn-info">précédent</a>
	<?php endif; ?>
    </div>
    <div class="col-md-6 text-center">
    <?php for ($i=1;$i<=$maxPageNum;$i++) : ?>
		<a href="?page=<?= $i ?>&ses_id=<?=$sessionId?>" class="btn btn-info btn-xs"><?= $i ?></a>
    <?php endfor; ?>
    </div>
    <div class="col-md-3 text-right">
    <?php if ($page < $maxPageNum) : ?>
    	<a href="list.php?page=<?= ($page+1) ?>&ses_id=<?=$sessionId?>" class="btn btn-sm btn-info">suivant</a>
	<?php endif; ?>
    </div>
  </div>
<?php if (isset($studentListe) && sizeof($studentListe) > 0) : ?>
	<table class="table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Email</th>
				<th>Date de naissance</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($studentListe as $currentEtudiant) : ?>
			<tr>
				<td><?= $currentEtudiant['stu_id'] ?></td>
				<td><?= $currentEtudiant['stu_lname'] ?></td>
				<td><?= $currentEtudiant['stu_fname'] ?></td>
				<td><?= $currentEtudiant['stu_email'] ?></td>
				<td><?= $currentEtudiant['birthdate'] ?></td>
				<td>
					<a href="student.php?id=<?= $currentEtudiant['stu_id'] ?>" class="btn btn-success btn-sm">détails</a>
				</td>
				<td>
					<!-- Single button -->
					<div class="btn-group">
						<button type="button" <?= !isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'admin' ? 'class="hidden"':''?> class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="list.php?deleteStudentId=<?= $currentEtudiant['stu_id'] ?>">Supprimer</a></li>
							<li><a href="#">Annuler</a></li>
						</ul>
					</div>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php else :?>
	aucun étudiant
<?php endif; ?>
</div>
