<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">
    <?php if (!empty($studentInfos['stu_id'])) : ?>
    	Modification de <?= $studentInfos['stu_firstname'] ?> <?= $studentInfos['stu_lastname'] ?>
    <?php else : ?>
    	Ajout d'un étudiant
    <?php endif; ?>
    </h3>
  </div>
  <div class="panel-body">
  	<?php if (!empty($errorList)) : ?>
		<div class="alert alert-danger" role="alert">
		  <?php foreach ($errorList as $currentErrorText) : ?>
		  	<?= $currentErrorText ?><br>
		  <?php endforeach; ?>
		</div>
  	<?php endif; ?>
	<form action="" method="post" enctype="multipart/form-data">
		<?php if (!empty($studentInfos['stu_image'])) :?>
			<img src="files/<?= $studentInfos['stu_image'] ?>" alt="" height="140" style="display:block;margin:auto;" /><br>
		<?php endif; ?>
  		<div class="row">
	  		<div class="col-md-6 col-sm-6 col-xs-12">

				<div class="form-group">
					<label>Nom</label>
					<input type="text" class="form-control" name="stu_lastname" value="<?= $studentInfos['stu_lastname'] ?>" placeholder="Nom" />
				</div>
				
				<div class="form-group">
					<label>Prénom</label>
					<input type="text" class="form-control" name="stu_firstname" value="<?= $studentInfos['stu_firstname'] ?>" placeholder="Prénom" />
				</div>
				
				<div class="form-group">
					<label>Email</label>
					<input type="text" class="form-control" name="stu_email" value="<?= $studentInfos['stu_email'] ?>" placeholder="Email" />
				</div>
				
				<div class="form-group">
					<label>Date de naissance</label>
  					<input type="text" class="form-control" name="stu_birthdate" value="<?= $studentInfos['stu_birthdate'] ?>" placeholder="Date de naissance" />
  					<small class="form-text text-muted">au format YYYY-MM-DD (<?= date('Y-m-d') ?>)</small>
  				</div>
			</div>
	  		<div class="col-md-6 col-sm-6 col-xs-12">

				<div class="form-group">
					<label>Sympathie</label>
					<select name="stu_friendliness" class="form-control">
						<option value="">choisissez</option>
						<?php for($i=0;$i<=5;$i++) : ?>
							<option value="<?= $i ?>"<?= $studentInfos['stu_friendliness'] == $i ? ' selected=selected"' : '' ?>><?= getSympathieLabelFromId($i) ?></option>
						<?php endfor; ?>
					</select>
				</div>
				
				<div class="form-group">
					<label>Session</label>
					<select name="ses_id" class="form-control">
						<option value="">choisissez</option>
						<?php foreach ($sessionsList as $sesId=>$sesName) : ?>
							<option value="<?= $sesId ?>"<?= $studentInfos['session_ses_id'] == $sesId ? ' selected=selected"' : '' ?>><?= $sesName ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				
				<div class="form-group">
					<label>Ville</label>
					<select name="cit_id" class="form-control">
						<option value="">choisissez</option>
						<?php foreach ($citiesList as $citId=>$citName) : ?>
							<option value="<?= $citId ?>"<?= $studentInfos['city_cit_id'] == $citId ? ' selected=selected"' : '' ?>><?= $citName ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				
				<div class="form-group">
					<label for="fileForm">Image</label>
					<input type="file" name="stu_image" id="fileForm" />
				</div>
			</div>
		</div>
		<?php if (!empty($studentInfos['stu_id'])) : ?>
			<input type="submit" class="btn btn-success btn-block" value="Modifier" />
    	<?php else : ?>
			<input type="submit" class="btn btn-success btn-block" value="Ajouter" />
		<?php endif; ?>
	</form>
  </div>
</div>