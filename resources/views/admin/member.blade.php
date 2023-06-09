@extends('layouts.admin')
@section('header','Member')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div id="controller">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
	   	 			<a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right">Create New Member</a>
				</div>
		  			<div class="card-body">
	    				<table id="datatable" class="table table-bordered">
      						<thead>
						        <tr>
						          	<th style="width: 10px">No</th>
									<th class="text-center">Name</th>
									<th class="text-center">Gender</th>
									<th class="text-center">Phone Number</th>
									<th class="text-center">Address</th>
									<th class="text-center">Email</th>
									<th class="text-center">Created At</th>
									<th class="text-center">Action</th>			
						        </tr>
				      		</thead>
					      						
    					</table>
		  			</div>
			</div>
		</div>
	</div>

		<div class="modal fade" id="modal-default">
		  	<div class="modal-dialog">
	    		<div class="modal-content">
    				<form method="POST" :action="actionUrl" autocomplete="off" @submit="submitForm($event, data.id)">
	      				<div class="modal-header">
        					<h4 class="modal-title">Member</h4>
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          					<span aria-hidden="true">&times;</span>
			        		</button>
	      				</div>
					    <div class="modal-body">
				        @csrf	
					        <input type="hidden" name="_method" value="PUT" v-if="editStatus">					

					        <div class="form-group">
				        		<label>Name</label>
				        		<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name" :value="data.name">
				        		@error('name')
	            					<div class="alert alert-danger">{{$message}}</div>
		            			@enderror
					        </div>
					        <div class="form-group">
					        	<label>Gender :</label>
				        		<input name="gender" type="radio" value="m" /> Male
							    <input name="gender" type="radio" value="f" /> Female
					        </div>
					        <div class="form-group">
				        		<label>Phone Number</label>
				        		<input type="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Enter phone number" :value="data.phone_number">
				        		@error('phone_number')
	            					<div class="alert alert-danger">{{$message}}</div>
		            			@enderror
					        </div>
					        <div class="form-group">
				        		<label>Alamat</label>
				        		<input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter address" :value="data.address">
				        		@error('address')
	            					<div class="alert alert-danger">{{$message}}</div>
		            			@enderror
					        </div>
					        <div class="form-group">
				        		<label>Email</label>
				        		<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email" :value="data.email">
				        		@error('email')
	            					<div class="alert alert-danger">{{$message}}</div>
		            			@enderror
					        </div>
			      		</div>
					    <div class="modal-footer justify-content-between">
						    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						    <button type="submit" class="btn btn-primary">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
</div>
@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script type="text/javascript">
		var	actionUrl = '{{ url('members') }}';
		var apiUrl = '{{ url('api/members') }}';

		var columns = [
				{data: 'DT_RowIndex', class: 'text-center', orderable: true},
				{data: 'name', class: 'text-center', orderable: true},
				{data: 'gender', class: 'text-center', orderable: true},
				{data: 'phone_number', class: 'text-center', orderable: true},
				{data: 'address', class: 'text-center', orderable: false},
				{data: 'email', class: 'text-center', orderable: true},
				{data: 'date', class: 'text-center', orderable: true},
				{render: function (index,row,data,meta) {
						return `
								<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event,${meta.row})">
										Edit
								</a>
								<a class="btn btn-danger btn-sm" onclick="controller.deleteData(event,${data.id})">
										Delete
								</a>`;
				}, orderable: false, width: '200px', class: 'text-center'},
		];
</script>
<script type="text/javascript">
    function validate(form) {
        var gender = form.querySelectorAll('input[name="gender"]:checked');
        if (!gender.length) {
            alert('You must select male or female');
            return false;
        }
    }
</script>
<script src="{{ asset('js/data.js') }}"></script>
@endsection
