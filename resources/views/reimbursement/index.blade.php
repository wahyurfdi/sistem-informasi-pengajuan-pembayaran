@extends('layouts.app', [
	'title' => 'Reimbursement',
	'breadcrumbs' => [
		['url' => '', 'name' => 'Reimbursement'],
		['url' => '/cms/reimbursement', 'name' => 'Reimbursement']
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
                    <div class="row mb-3">
                        <div class="col-7">
                            @if(in_array(Auth::user()->role, ['SUPER_ADMIN', 'SPV_EMPLOYEE']))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reimbursementModal">
                                <i class="bi bi-plus"></i> Reimbursement
                            </button>
                            @endif
                        </div>
                        <div class="col-5">
                            <form class="w-100">
                                <div class="d-flex float-end">
                                    <input type="text" class="form-control" placeholder="Search..." name="search" value="{{ $filters->search }}">
                                    <select class="form-control ms-2" name="status">
                                        <option value="">-</option>
                                        @foreach(config('app.pr_status') as $code => $status)
                                        <option value="{{ $code }}" {{ $filters->status == $code ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <div class="d-flex ms-2">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <a href="/cms/reimbursement" class="btn btn-secondary ms-2">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
					<table class="table table-bordered" id="table">
						<thead>
							<tr>
								<th width="30">No</th>
								<th>Code</th>
								<th>Employee</th>
								<th>User Created</th>
                                <th>Amount</th>
                                <th width="70" class="text-center">Status</th>
								<th width="100" class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
                            @php $invoiceTotal = 0; @endphp
                            @foreach($paymentRequests as $idx => $paymentRequest)
							<tr>
								<th>{{ $idx+1 }}</th>
								<td>{{ $paymentRequest->code }}</td>
								<td>{{ $paymentRequest->userRequested->name }}</td>
								<td>{{ $paymentRequest->userCreated->name }} <br> <small>{{ date('d M Y, H:i:s', strtotime($paymentRequest->created_at)) }}</small></td>
                                <td class="text-end">
                                    @php $invoiceTotal += $paymentRequest->invoices->sum('amount'); @endphp
                                    
                                    Rp {{ number_format($paymentRequest->invoices->sum('amount'), 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($paymentRequest->status == 'PENDING')
                                        <span class="badge w-100 bg-light-secondary px-2">Pending</span>
                                    @elseif($paymentRequest->status == 'REVIEW')
                                        <span class="badge w-100 bg-light-primary px-2">Review</span>
                                    @elseif($paymentRequest->status == 'RETURNED')
                                        <span class="badge w-100 bg-light-warning px-2">Returned</span>
                                    @elseif($paymentRequest->status == 'REJECTED')
                                        <span class="badge w-100 bg-light-danger px-2">Rejected</span>
                                    @elseif($paymentRequest->status == 'APPROVED')
                                        <span class="badge w-100 bg-light-success px-2">Approved</span>
                                    @endif
                                </td>
								<td class="text-center">
									<a href="/cms/reimbursement/{{ $paymentRequest->code }}" class="btn btn-info btn-sm">
										<i class="bi bi-file-earmark-text-fill"></i>
									</a>
									<button class="btn btn-primary btn-sm" onclick="deletePayreq('{{ $paymentRequest->code }}')" {{ $paymentRequest->user_created != Auth::user()->id ? 'disabled' : '' }}>
										<i class="bi bi-trash-fill"></i>
									</button>
								</td>
							</tr>
                            @endforeach
						</tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th class="text-end">Rp {{ number_format($invoiceTotal, 0, ',', '.') }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
					</table>
                </div>
            </div>
		</div>
	</div>
</section>

<div class="modal fade" id="reimbursementModal" tabindex="-1" role="dialog" aria-labelledby="reimbursementModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="reimbursementModalLabel">FORM REIMBURSEMENT</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
                    <div class="col-12 mb-2">
                        <div class="form-group row">
                            <label class="col-4 col-form-label" for="employee">
                                Employee<span class="text-danger">*</span>
                            </label>
                            <div class="col-8">
                                <select class="form-control" id="employee">
                                    <option value="">-</option>
                                    @foreach($users as $user)
                                    <option
                                        value="{{ $user->id }}"
                                        data-division="{{ $user->division_id }}"
                                        data-region="{{ $user->region_id }}"
                                        {{ Auth::user()->id == $user->id ? 'disabled' : '' }}
                                    >
                                        {{ $user->name }} ({{ $user->code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label" for="division">
                                Division & Region<span class="text-danger">*</span>
                            </label>
                            <div class="col-8">
                                <div class="input-group">
                                    <select class="form-control w-25 bg-white" id="division" disabled>
                                        <option value="">-</option>
                                        @foreach($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->name }} ({{ $division->code }})</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text bg-white">&</span>
                                    <select class="form-control w-25 bg-white" id="region" disabled>
                                        <option value="">-</option>
                                        @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }} ({{ $region->code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label" for="document">
                                Document<span class="text-danger">*</span>
                            </label>
                            <div class="col-8">
                                <input type="file" class="form-control" id="document" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label" for="description">Description</label>
                            <div class="col-8">
                                <textarea class="form-control" id="description" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered" id="invoiceTable">
                            <thead>
                                <tr class="bg-light-secondary">
                                    <th colspan="5" class="text-center">List of Invoices</th>
                                </tr>
                                <tr>
                                    <th width="50">Date</th>
                                    <th width="150">Category</th>
                                    <th>Description</th>
                                    <th width="150">Amount (Rp)</th>
                                    <th width="70"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceFields">
                                <tr id="invoiceField0">
                                    <td>
                                        <input type="date" class="form-control date">
                                    </td>
                                    <td>
                                        <select class="form-control category">
                                            <option value="">-</option>
                                            @foreach($costCategories as $costCategorie)
                                            <option value="{{ $costCategorie->id }}">{{ $costCategorie->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <textarea class="form-control description" rows="1"></textarea>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control amount" value="0">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-light-success" onclick="addInvoiceField()">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="addPayreq()">Submit</button>
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

    let invoiceTableIdx = 0
    const addInvoiceField = () => {
        let modal = $('#reimbursementModal')

        invoiceTableIdx += 1

        modal.find('#invoiceTable tbody').append(`
            <tr id="invoiceField${invoiceTableIdx}">
                <td>
                    <input type="date" class="form-control date">
                </td>
                <td>
                    <select class="form-control category">
                        <option value="">-</option>
                        @foreach($costCategories as $costCategorie)
                        <option value="{{ $costCategorie->id }}">{{ $costCategorie->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <textarea class="form-control description" rows="1"></textarea>
                </td>
                <td>
                    <input type="number" class="form-control amount" value="0">
                </td>
                <td class="text-center">
                    <button class="btn btn-light-danger" onclick="removeInvoiceField('${invoiceTableIdx}')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </td>
            </tr>
        `)
    }

    const removeInvoiceField = (idx) => {
        let modal = $('#reimbursementModal')

        modal.find(`#invoiceField${idx}`).remove()
    }

    $(document).on('change', '#employee', function() {
        let option = $(this).find(':selected'),
            division = option.attr('data-division'),
            region = option.attr('data-region')

        $('#division').val(division)
        $('#region').val(region)
    })

    const addPayreq = () => {
        let modal = $('#reimbursementModal'),
            employee = modal.find('#employee').val(),
            division = modal.find('#division').val(),
            region = modal.find('#region').val(),
            document = modal.find('#document'),
            description = modal.find('#description').val(),
            invoices = []

        modal.find('#invoiceFields > tr').each(function() {
            let elm = $(this),
                date = elm.find('.date').val(),
                category = elm.find('.category').val(),
                description = elm.find('.description').val(),
                amount = elm.find('.amount').val()

            invoices.push({
                date: date,
                category_id: category,
                description: description,
                amount: amount
            })
        })

        let formData = new FormData()
        formData.append('_token', '{{ csrf_token() }}')
        formData.append('employee_id', employee)
        formData.append('division_id', division)
        formData.append('region_id', region)
        formData.append('document', document[0].files[0])
        formData.append('description', description)
        formData.append('invoices', JSON.stringify(invoices))

        $('#loading').removeClass('d-none')

        $.ajax({
            method: 'POST',
            url: '{{ route("reimbursement.store") }}',
            contentType: false, 
            processData: false,
            data: formData, 
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

    const deletePayreq = (code) => {
        Swal.fire({
            title: 'Confirm Delete',
            text: 'Yakin hapus Pengajuan ini? data akan hilang secara permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D11317',
            confirmButtonText: 'Yes',
            cancelButtonColor: '#C0C0C0',
        }).then((result) => {
            if(result.isConfirmed) {
                $('#loading').removeClass('d-none')

                $.ajax({
                    method: 'DELETE',
                    url: '{{ route("reimbursement.destroy") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payment_request_code: code
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
        })
    }
</script>
@endsection