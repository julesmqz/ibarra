<?php $this->load->view('template/header'); ?>
<div class="row">
	<div class="col-xs-12">
		<div class="addbox box box-success">
			<h1>Añadir una noticia</h1>
			<div class="row">
				<div class="col-xs-12">
					<div>
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">Información general</a></li>
							<li role="presentation"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Imágenes</a></li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="general">
								<form action="{post_url}" method="POST">
									<div class="form-group">
										<label for="title">Título</label>
										<input type="text" class="form-control" name="title" id="title" placeholder="Título" value="<?php echo $title_field;?>">
									</div>
									<div class="form-group">
										<label for="body">Contenido</label>
										<textarea name="body" class='wysiwyg'><?php echo $content_field;?></textarea>
									</div>
									<div class="buttons">
										<a href="<?php echo base_url('news/list');?>" class="btn btn-danger">Cancelar</a>
										<button type="submit" class="btn btn-success btn-lg">Guardar</button>
									</div>
								</form>
							</div>
							<div role="tabpanel" class="tab-pane" id="images">
								<?php if ($can_add_images): ?>
								<?php $this->load->view('template/add_image'); ?>
								<?php else: ?>
								<p class="">No puedes subir imágenes hasta que guardes información general </p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer'); ?>