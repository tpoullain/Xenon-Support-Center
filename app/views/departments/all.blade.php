@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Departments <small>Control panel.</small></h3>
	</div>
</div>
<!-- /page header -->
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
	<ul class="breadcrumb">
		<li>
			<a href="/">Home</a>
		</li>
		<li class="active">
			Departments
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">

	<div style="margin:5px;" class="alert alert-info">
		If you delete department all department tickets , conversations , operators will also be deleted .
	</div>

	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-users"></i> All Departments</h6>
		<div class="table-controls pull-right">
			<a href="/departments/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Department"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="department_list" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Company</th>
					<th>Department Admin</th>
					<th>Permissions</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($departments as $department)
				<tr>
					<td>{{$department->id}}</td>
					<td>{{{$department->name}}}</td>
					<td>{{{$department->company->name}}}</td>
					<td>{{isset($department->admin)?"<label class='label label-success'>".htmlentities($department->admin->name)."</label>":"<label class='label label-warning'>NONE</label>"}}</td>
					<td><label>{{implode("</br>",explode(",",$department->permissions))}}</label></td>
					<td>
					    <a href="/departments/update/{{$department->id}}" class="btn btn-success btn-sm">
						    <i class="icon-pencil4"></i> Edit
					    </a>
					</td>
					<td><a href="/departments/delete/{{$department->id}}" class="btn btn-danger btn-sm"> <i class="icon-remove2"></i> Delete </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>
@stop

@section('scripts')
{{HTML::style("/assets/plugins/jquery-multi-select/css/multi-select.css")}}
{{HTML::script("/assets/plugins/jquery-multi-select/js/jquery.multi-select.js")}}
<script type="text/javascript">
	$(document).ready(function() {

		$('#permissions_create').multiSelect();
		$('#department_edit_permissions').multiSelect();

		$('.edit_department').on('click', function() {

			var department_id = $(this).data('id');

			//Get request to get department with department_id and set that to edit fields
			$.ajax({
				url : "/departments/get/" + department_id,
				success : function(department) {
					$('#department_edit_id').val(department.id);
					$('#department_edit_name').val(department.name);
					//$('#department_edit_permissions').val(department.permissions);

					var $select = $('#department_edit_permissions');

					//Remove old options
					$select.find('option').remove();

					console.log(department.permission_keys);

					//Populate new options
					$.each(department.permissions_all, function(key, value) {
						$select.append('<option value=' + value.key + '>' + value.text + '</option>');
					});

					//Rerun multiselect
					$select.multiSelect('refresh');

					$select.multiSelect('select', department.permission_keys);

				},
				error : function(response) {

				}
			});
		});

	}); 
</script>
@stop