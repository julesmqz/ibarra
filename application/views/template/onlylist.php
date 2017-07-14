<div class="row">
	<div class="col-xs-12">
		<div class="pull-right">
			<a class="btn btn-link btn-sm" href="{url_add}"><span class="glyphicon glyphicon-plus"></span> Nuevo </a>
		</div>
		<div class="clearfix"></div>
		<table class="table">
			<tr>
				<th>Título</th>
				<th>{dynamic_field}</th>
				<th>Fecha de Creación</th>
				<th>Acciones</th>
			</tr>
			{items}
			<tr>
				<td>{title}</td>
				<td>{dynamic_field_value}</td>
				<td>{date_created}</td>
				<td><a href="{url_edit}" class="btn btn-link btn-sm" ><span class="glyphicon glyphicon-edit"></span> Editar</a>  <a class="btn btn-link btn-sm" href="{url_delete}"><span class="glyphicon glyphicon-trash"></span> Borrar</a></td>
			</tr>
			{/items}
			
		</table>
		<div class="pagination-cnt">
			{pagination}
		</div>
	</div>
</div>