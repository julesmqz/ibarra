<form id="my-dropzone" action="{image_post_url}" method="POST" enctype="multipart/form-data" class="dropzone"></form>
<div class="row all-images">
{images}
<div class="col-sm-3">
	<div class="thumbnail">
		<img src="{src}" alt="{title}" />
		<div class="caption">
			<strong>{title}</strong>
			<a href="{url_delete}" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-remove-circle"></span></a>
		</div>
	</div>
</div>
{/images}
</div>