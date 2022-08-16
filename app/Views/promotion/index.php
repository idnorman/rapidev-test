<?= $this->extend('app') ?>
<?= $this->section('style')  ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('bootstrap-multiselect/dist/css/bootstrap-multiselect.min.css') ?>" />
<?= $this->endSection() ?>
<?= $this->section('customStyle')  ?>
	.container{
		min-height: 1000px;
	}
<?= $this->endSection()  ?>
<?= $this->section('content') ?>

<div class="container mt-4 d-flex justify-content-center">


      <div class="col-12">
	      <?php if(session()->getFlashdata('msg')):?>
	      <div class="alert alert-warning">
	         <?= session()->getFlashdata('msg') ?>
	      </div>
	      <?php endif;?>

	      <?php if(session()->getFlashdata('success')):?>
	      <div class="alert alert-success">
	         <?= session()->getFlashdata('success') ?>
	      </div>
	      <?php endif;?>
      	<a href="<?= url_to('promotion_create') ?>" class="btn btn-success mb-3">Tambah</a>
		   <table class="table table-responsive-xs table-responsive-sm table-striped">
			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Nama Promo</th>
			      <th scope="col">Jenis Promo</th>
			      <th scope="col">Persentase Promo</th>
			      <th scope="col">Maks. Nominal</th>
			      <th scope="col">Aksi</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $i = 1; ?>
			  	<?php foreach($promotions as $promotion): ?>
			    <tr>
			      <th scope="row"><?= $i++ ?></th>
			      <td><?= $promotion['name'] ?></td>
			      <td><?= $promotion['type'] ?></td>
			      <td><?= $promotion['amount']*10 ?>%</td>
			      <td><?= $promotion['max_amount'] ?></td>
			      <td>
			      	<a href="<?= url_to('promotion_edit', $promotion['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
			      	<a href="<?= url_to('promotion_delete', $promotion['id']) ?>" onclick="return confirm('Apakah anda yakin menghapus data ini?')" class="btn btn-danger btn-sm">Hapus</a>
			      </td>
			    </tr>
			   <?php endforeach ?>
			    
			  </tbody>
			</table>
		</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
	<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script type="text/javascript" src="<?= base_url('bootstrap-multiselect/dist/js/bootstrap-multiselect.min.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('customScript')  ?>

	$(function() {
		$('input[name="publish_date"]').daterangepicker({
			<!-- timePicker: true, -->
			<!-- timePicker24Hour: true, -->
			<!-- startDate: moment().startOf('hour'), -->
    		<!-- endDate: moment().startOf('hour').add(6, 'hour'), -->
			locale: {
				format: 'Y-MM-DD'
			}
		});
		$('input[name="booking_date"]').daterangepicker({
			locale: {
				format: 'Y-MM-DD'
			}
		});
		$('input[name="stay_date"]').daterangepicker({
			locale: {
				format: 'Y-MM-DD'
			}
		});
	});
	$(document).ready(function() {
        $('#hotel_filter').multiselect({
            <!-- enableClickableOptGroups: true, -->
            enableCollapsibleOptGroups: true,
            <!-- enableFiltering: true, -->
            includeSelectAllOption: true,
            buttonWidth: '100%'
        });
   });


<?= $this->endSection() ?>