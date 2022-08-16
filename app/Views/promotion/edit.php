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
	<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-xs-12">
		<?php if(session()->getFlashdata('msg')):?>
      <div class="alert alert-warning">
         <?= session()->getFlashdata('msg') ?>
      </div>
      <?php endif;?>
	   <form method="POST" action="<?= url_to('promotion_update') ?>">
	   	<input type="hidden" name="_method" value="PUT" />
	   	<input type="hidden" name="promotion_id" value="<?= $promotion[0]['promotion_id'] ?>">
	    	<div class="mb-3">
	        	<label for="name" class="form-label">Judul Promo</label>
	        	<input type="text" class="form-control" id="name" name="name" value="<?= $promotion[0]['promotion_name'] ?>" placeholder="Promo Kemerdekaan">
	    	</div>
	    	<div class="mb-3">
	    		<label>Jenis Promo</label>
		    	<div class="form-check">
				  	<input class="form-check-input" type="radio" name="type" id="type1" value="DISCOUNT" <?= ($promotion[0]['promotion_type'] == 'DISCOUNT') ? 'checked' : ''  ?>>
				  	<label class="form-check-label" for="type1">
				   	Diskon
				  	</label>
				</div>
				<div class="form-check">
				  	<input class="form-check-input" type="radio" name="type" id="type2" value="CASHBACK" <?= ($promotion[0]['promotion_type'] == 'CASHBACK') ? 'checked' : ''  ?>>
				  	<label class="form-check-label" for="type2">
				   	Cashback
				  	</label>
				</div>
			</div>
	    	<label for="amount" class="form-label">Persentase Diskon</label>
			<div class="input-group mb-3">
			  <input type="number" step="any" min="0" max="100" name="amount" class="form-control" id="amount" aria-describedby="amount-addon3" value="<?= $promotion[0]['promotion_amount']*10 ?>" placeholder="20">
			  <span class="input-group-text" id="amount-addon3">%</span>
			</div>
	    	<label for="max_amount" class="form-label">Maksimal Diskon</label>
			<div class="input-group mb-3">
				<span class="input-group-text" id="max_amount-addon3">Rp</span>
			  <input type="text" name="max_amount" class="form-control" id="max_amount" aria-describedby="max_amount-addon3" value="<?= $promotion[0]['promotion_max_amount'] ?>" placeholder="20000">
			</div>
	    	<div class="mb-3">
	        	<label for="publish_date" class="form-label">Periode Publikasi</label>
	        	<input type="text" class="form-control" id="publish_date" name="publish_date">
	    	</div>
	    	<div class="mb-3">
	        	<label for="booking_date" class="form-label">Periode Reservasi</label>
	        	<input type="text" class="form-control" id="booking_date" name="booking_date">
	    	</div>
	    	<div class="mb-3">
	        	<label for="stay_date" class="form-label">Periode Inap</label>
	        	<input type="text" class="form-control" id="stay_date" name="stay_date">
	    	</div>

	    	<div class="mb-3">
	        	<label for="hotel_filter" class="form-label">Pilih Hotel & Kamar</label><br>
	        	<select name="hotel_filter[]" class="form-select" id="hotel_filter" multiple="multiple">
	        		<?php foreach($hotels as $hotel): ?>
	        			<optgroup label="<?= $hotel['name'] ?>">
	        			<?php foreach($rooms as $room): ?>
	        				<?php if($hotel['id'] == $room['property_id']): ?>
	        					<option class="room" value="<?= $hotel['id'] ?>-<?= $room['id'] ?>" <?= (in_array($room['id'], $room_promotion) ? 'selected' : '') ?>><?= $room['name'] ?></option>
	        				<?php endif ?>
	        			<?php endforeach ?>
	        			</optgroup>
	        		<?php endforeach ?>
					
				</select>
	    	</div>

	    	<button type="submit" class="btn btn-primary">Simpan</button>
	   </form>
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
			startDate: moment('<?= $promotion[0]['promotion_publish_start'] ?>'),
    		endDate: moment('<?= $promotion[0]['promotion_publish_end'] ?>'),
			locale: {
				format: 'Y-MM-DD'
			}
		});
		$('input[name="booking_date"]').daterangepicker({
			startDate: moment('<?= $promotion[0]['promotion_booking_start'] ?>'),
    		endDate: moment('<?= $promotion[0]['promotion_booking_end'] ?>'),
			locale: {
				format: 'Y-MM-DD'
			}
		});
		$('input[name="stay_date"]').daterangepicker({
			startDate: moment('<?= $promotion[0]['promotion_stay_start'] ?>'),
    		endDate: moment('<?= $promotion[0]['promotion_stay_end'] ?>'),
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