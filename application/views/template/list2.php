<?php $this->load->view('template/header'); ?>
<div class="row">
	<div class="col-xs-12">
	<div class="listbox box box-info">
		<h1>{list_title}</h1>
		<?php $this->load->view('template/onlylist_noactions');?>
	</div>
	</div>
</div>
<?php $this->load->view('template/footer'); ?>