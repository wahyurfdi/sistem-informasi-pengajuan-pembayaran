@extends('layouts.app', [
	'title' => 'Cost Category',
	'breadcrumbs' => [
		['url' => '', 'name' => 'Master Data'],
		['url' => '/cms/master-data/cost-category', 'name' => 'Cost Category']
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
						<i class="bi bi-plus"></i> Cost Category
					</button>
					<table class="table table-bordered" id="table">
						<thead>
							<tr>
								<th width="30">No</th>
								<th>Name</th>
								<th>Description</th>
								<th width="100" class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($costCategories as $idx => $costCategory)
							<tr>
								<th>{{ $idx+1 }}</th>
								<td>{{ $costCategory->name }}</td>
								<td>{{ $costCategory->description ?? '-' }}</td>
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

<div class="modal fade" id="costCategoryModal" tabindex="-1" role="dialog" aria-labelledby="costCategoryModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="costCategoryModalLabel">FORM COST CATEGORY</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
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

	const costCategoryModal = new bootstrap.Modal('#costCategoryModal', {})

	const showForm = (type='ADD') => {
		let modal = $('#costCategoryModal')

		modal.find('#costCategoryModalLabel').text('ADD COST CATEGORY')
		if(type == 'EDIT') modal.find('#costCategoryModalLabel').text('EDIT COST CATEGORY')
		
		costCategoryModal.show()
	}
</script>
@endsection