<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-language" content="es">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $title;?></title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('/out/css/dropzone.min.css');?>">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('/out/css/style.css');?>">
		
	</head>
	<body>
		<div class='container-fluid'>
			<?php if(isset($_SESSION['logged_in'])): ?>
			<header class="menu-top">
				<a href="#" class="menu-link out-link pull-left btn btn-link button-sm">
					<i class="glyphicon glyphicon-menu-hamburger"></i>
				</a>
				<a href="<?echo base_url('login/destroy');?>" class="out-link pull-right btn btn-link button-sm">
					<i class="glyphicon glyphicon-log-out"></i>
				</a>
				<?php $this->load->view('template/sidebar'); ?>
			</header>
			<?php endif; ?>
			<?php if ($error_msg): ?>
			<div class="row">
				<div class="col-xs-12">
					<div class="alert alert-danger" role="alert"><?php echo $error_msg;?></div>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($success_msg): ?>
			<div class="row">
				<div class="col-xs-12">
					<div class="alert alert-success" role="alert"><?php echo $success_msg;?></div>
				</div>
			</div>
			<?php endif; ?>