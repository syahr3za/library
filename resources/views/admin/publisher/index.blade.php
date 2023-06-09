@extends('layouts.admin')
@section('header','Publisher')

@section('content')
<div class="card">
  <div class="card-header">
    <a href="{{ url('publishers/create') }}" class="btn btn-sm btn-primary pull-right">Create New Publisher</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          	<th style="width: 10px">No</th>
			<th class="text-center">Name</th>
			<th class="text-center">Email</th>
			<th class="text-center">Phone Number</th>
			<th class="text-center">Address</th>	
			<th class="text-center">Created At</th>	
			<th class="text-center">Action</th>			
        </tr>
      </thead>
      <tbody>
        @foreach($publishers as $key => $publisher)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $publisher->name }}</td>
				<td>{{ $publisher->email }}</td>
				<td>{{ $publisher->phone_number }}</td>
				<td>{{ $publisher->address }}</td>
				<td class="text-center">{{ date('d M Y - H:i:s', strtotime($publisher->created_at))  }}</td>
				<td class="text-center">
					<a href="{{ url('publishers/'.$publisher->id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>

					<form action="{{ route('publishers.destroy',$publisher->id) }}" method="POST">
						<input class="btn btn-danger btn-sm" type="submit" value="Delete" onclick="return confirm('Are you sure?')">
						@method('delete')
						@csrf 
					</form>
				</td>
			</tr>
			@endforeach
      </tbody>
    </table>
  </div>
  
@endsection