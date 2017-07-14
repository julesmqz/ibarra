<?php $this->load->view('template/header'); ?>
<div class="row">
	<div class="col-xs-12">
		<div class="login box">
			<h1>Ibarra Admin Panel</h1>
			<div class='login-form'>
				<form action="<?php echo base_url(); ?>login/auth" method="POST">
					<div class="form-group">
						<label for="user-input">Usuario</label>
						<input type="text" class="form-control" id="user-input" placeholder="Usuario" name='user'>
					</div>
					<div class="form-group">
						<label for="pass-input">Contraseña</label>
						<input type="password" class="form-control" id="pass-input" placeholder="Contraseña" name='pass'>
					</div>
					<button type="submit" class="btn btn-default">Entrar</button>
				</form>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer'); ?>