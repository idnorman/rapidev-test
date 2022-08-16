<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="container mt-4 d-flex justify-content-center">
	<div class="col-4">
		<?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning">
           <?= session()->getFlashdata('msg') ?>
        </div>
        <?php endif;?>
	    <form method="POST" action="<?php url_to('login_process') ?>">
	        <div class="mb-3">
	            <label for="exampleInputEmail1" class="form-label">Email address</label>
	            <input type="email" class="form-control" id="exampleInputEmail1" name="email">
	        </div>
	        <div class="mb-3">
	            <label for="exampleInputPassword1" class="form-label">Password</label>
	            <input type="password" class="form-control" id="exampleInputPassword1" name="password">
	        </div>
	        <button type="submit" class="btn btn-primary">Login</button>
	   	</form>
   	</div>
</div>

<?= $this->endSection() ?>
