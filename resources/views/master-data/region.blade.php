@extends('layouts.app', [
	'title' => 'Region',
	'breadcrumbs' => [
		['url' => '', 'name' => 'Master Data'],
		['url' => '/cms/master-data/region', 'name' => 'Region']
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
						<i class="bi bi-plus"></i> Region
					</button>
					<table class="table table-bordered" id="table">
						<thead>
							<tr>
								<th width="30">No</th>
								<th>Code</th>
								<th>Name</th>
								<th>Description</th>
								<th width="100" class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($regions as $idx => $region)
							<tr>
								<th>{{ $idx+1 }}</th>
								<td>{{ $region->code }}</td>
								<td>{{ $region->name }}</td>
								<td>{{ $region->description ?? '-' }}</td>
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

<div class="modal fade" id="regionModal" tabindex="-1" role="dialog" aria-labelledby="regionModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="regionModalLabel">FORM REGION</h1>
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
						<label for="description" class="form-label">Description</label>
					</div>
					<div class="col-8">
						<textarea class="form-control" id="description"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Submit</button>
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

	const regionModal = new bootstrap.Modal('#regionModal', {})

	const showForm = (type='ADD') => {
		let modal = $('#regionModal')

		modal.find('#regionModalLabel').text('ADD REGION')
		if(type == 'EDIT') modal.find('#regionModalLabel').text('EDIT REGION')
		
		regionModal.show()
	}
</script>
@endsection