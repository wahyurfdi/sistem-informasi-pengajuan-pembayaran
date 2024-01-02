@extends('layouts.app', [
	'title' => 'User Account',
	'breadcrumbs' => [
		['url' => '', 'name' => 'Master Data'],
		['url' => '/cms/master-data/user-account', 'name' => 'User Account']
	]
])

@section('style')

@endsection

@section('content')
<section class="section">
	<div class="row">
		<div class="col-12">
			<div class="card mb-3 border h-100">
                <div class="card-body">
					<button class="btn btn-primary mb-3" onclick="showForm('ADD')">
						<i class="bi bi-plus"></i> User Account
					</button>
					<table class="table table-bordered" id="table">
						<thead>
							<tr>
								<th width="30">No</th>
                                <th>Code</th>
								<th>Name</th>
								<th>Role</th>
                                <th>Division</th>
								<th>Region</th>
								<th width="100" class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $idx => $user)
							<tr>
								<th>{{ $idx+1 }}</th>
                                <td>{{ $user->code }}</td>
								<td>{{ $user->name }}</td>
								<td>{{ $user->roleName }}</td>
								<td>{{ $user->division->name }}</td>
                                <td>{{ $user->region->name }}</td>
								<td class="text-center">
									<button class="btn btn-warning btn-sm" onclick="showForm('EDIT')">
										<i class="bi bi-pencil-square"></i>
									</button>
									<button class="btn btn-primary btn-sm">
										<i class="bi bi-trash-fill"></i>
									</button>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
		</div>
	</div>
</section>

<div class="modal fade" id="userAccountModal" tabindex="-1" role="dialog" aria-labelledby="userAccountModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="userAccountModalLabel">FORM USER ACCOUNT</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row mb-3">
					<div class="col-4">
						<label for="code" class="form-label">Code<small class="text-danger">*</small></label>
					</div>
					<div class="col-8">
						<input type="text" class="form-control" id="code">
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-4">
						<label for="name" class="form-label">Name<small class="text-danger">*</small></label>
					</div>
					<div class="col-8">
						<input type="text" class="form-control" id="name">
					</div>
				</div>
                <div class="row mb-3">
					<div class="col-4">
						<label for="division" class="form-label">Division<small class="text-danger">*</small></label>
					</div>
					<div class="col-8">
						<select class="form-control" id="division">
                            <option value="">-</option>
							@foreach($divisions as $division)
							<option value="{{ $division->id }}">{{ $division->name.' ('.$division->code.')' }}</option>
							@endforeach
                        </select>
					</div>
				</div>
                <div class="row mb-3">
					<div class="col-4">
						<label for="region" class="form-label">Region<small class="text-danger">*</small></label>
					</div>
					<div class="col-8">
						<select class="form-control" id="region">
                            <option value="">-</option>
							@foreach($regions as $region)
							<option value="{{ $region->id }}">{{ $region->name.' ('.$region->code.')' }}</option>
							@endforeach
                        </select>
					</div>
				</div>
                <hr>
                <div class="row mb-3">
					<div class="col-4">
						<label for="username" class="form-label">Username<small class="text-danger">*</small></label>
					</div>
					<div class="col-8">
						<input type="text" class="form-control" id="username">
					</div>
				</div>
                <div class="row mb-3">
					<div class="col-4">
						<label for="password" class="form-label">Password<small class="text-danger">*</small></label>
					</div>
					<div class="col-8">
						<input type="password" class="form-control" id="password">
					</div>
				</div>
                <div class="row mb-3">
					<div class="col-4">
						<label for="role" class="form-label">Role<small class="text-danger">*</small></label>
					</div>
					<div class="col-8">
						<select class="form-control" id="role">
                            <option value="SUPER_ADMIN">Super Admin</option>
                            <option value="FINANCE_STAFF">Finance Staff</option>
                            <option value="SPV_EMPLOYEE">SPV Employee</option>
                            <option value="EMPLOYEE">Employee</option>
                        </select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="submitForm()">Submit</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>	
	new DataTable('#table', {
		paging: false,
		searching: false,
		bInfo: false
	})

	const userAccountModal = new bootstrap.Modal('#userAccountModal', {})

	let formType = ''
	const showForm = (type='ADD') => {
		let modal = $('#userAccountModal')

		modal.find('#userAccountModalLabel').text('ADD USER ACCOUNT')
		if(type == 'EDIT') modal.find('#userAccountModalLabel').text('EDIT USER ACCOUNT')

		formType = type
		
		userAccountModal.show()
	}

	const submitForm = () => {
		let modal = $('#userAccountModal'),
			url = ''

		$('#loading').removeClass('d-none')

		$.ajax({
			method: 'POST',
			url: '{{ route("masterData.userAccount.store") }}',
			data: {
				_token: '{{ csrf_token() }}',
				code: modal.find('#code').val(),
				name: modal.find('#name').val(),
				username: modal.find('#username').val(),
				password: modal.find('#password').val(),
				division_id: modal.find('#division').val(),
				region_id: modal.find('#region').val(),
				role: modal.find('#role').val()
			},
			success: function(response) {
				$('#loading').addClass('d-none')

				if(response.status == 'OK') {
					location.reload()
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oppss..',
						text: response.message,
					})
				}
			},
			error: function(xhr, status, error) {
				$('#loading').addClass('d-none')

				Swal.fire({
					icon: 'error',
					title: 'Oppss..',
					text: xhr.responseJSON.message,
				})
			}
		})
	}
</script>
@endsection