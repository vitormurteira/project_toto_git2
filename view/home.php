<?php foreach ($sessionsList as $locId=>$locationInfos) : ?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?= $locationInfos['location'] ?> - <?= $locationInfos['training'] ?></h3>
	</div>
	<table class="table">
	<thead>
	<tr>
		<th>Session</th>
		<th>Début</th>
		<th>Fin</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($locationInfos['sessions'] as $sesId=>$sessionInfos) : ?>
	<tr>
		<td>Session #<?= $sessionInfos['number'] ?></td>
		<td><?= $sessionInfos['start'] ?></td>
		<td><?= $sessionInfos['end'] ?></td>
		<td><a href="list.php?ses_id=<?= $sesId ?>" class="btn btn-xs btn-success">Détails</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
<?php endforeach; ?>