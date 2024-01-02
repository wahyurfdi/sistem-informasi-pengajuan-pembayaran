@extends('layouts.app', [
	'title' => 'Reimbursement: '.$paymentRequest->code,
	'breadcrumbs' => [
		['url' => '', 'name' => 'Reimbursement'],
		['url' => '/cms/reimbursement', 'name' => 'Reimbursement'],
        ['url' => '', 'name' => $paymentRequest->code]
	]
])

@section('style')

@endsection

@section('content')
<section class="section">
	<div class="row">
		<div class="col-12">
			<div class="card mb-3 border h-100">
                <div class="card-header py-3 border-bottom">
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100 d-flex">
                                <h5 class="text-dark my-auto">{{ $paymentRequest->code }}</h5>
                                <input type="hidden" class="form-control" id="code" value="{{ $paymentRequest->code }}">
                                <div class="ms-auto">
                                    <div class="input-group ms-2">
                                        @if($paymentRequest->status == 'PENDING')
                                            <span class="badge bg-light-secondary px-3 pt-2">PENDING</span>
                                        @elseif($paymentRequest->status == 'REVIEW')
                                            <span class="badge bg-light-primary px-3 pt-2">REVIEW</span>
                                        @elseif($paymentRequest->status == 'RETURNED')
                                            <span class="badge bg-light-warning px-3 pt-2">RETURNED</span>
                                        @elseif($paymentRequest->status == 'REJECTED')
                                            <span class="badge bg-light-danger px-3 pt-2">REJECTED</span>
                                        @elseif($paymentRequest->status == 'APPROVED')
                                            <span class="badge bg-light-success px-3 pt-2">APPROVED</span>
                                        @endif

                                        @if(in_array(Auth::user()->role, ['SUPER_ADMIN', 'FINANCE_STAFF', 'SPV_EMPLOYEE']) && !in_array($paymentRequest->status, ['REJECTED', 'APPROVED']))
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-8">
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
                                            {{ $paymentRequest->user_requested == $user->id ? 'selected' : '' }}
                                            data-division="{{ $user->division_id }}"
                                            data-region="{{ $user->region_id }}"
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
                                            <option value="{{ $division->id }}" {{ $paymentRequest->requested_division_id == $division->id ? 'selected' : '' }}>{{ $division->name }} ({{ $division->code }})</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-text bg-white">&</span>
                                        <select class="form-control w-25 bg-white" id="region" disabled>
                                            <option value="">-</option>
                                            @foreach($regions as $region)
                                            <option value="{{ $region->id }}" {{ $paymentRequest->requested_region_id == $region->id ? 'selected' : '' }}>{{ $region->name }} ({{ $region->code }})</option>
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
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="document" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf">
                                        <button class="btn btn-light-success btn-sm px-2">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label" for="description">Description</label>
                                <div class="col-8">
                                    <textarea class="form-control" id="description" rows="2">{{ $paymentRequest->description }}</textarea>
                                </div>
                            </div>
                            <div>
                                <table class="table table-bordered mb-0" id="invoiceTable">
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
                        <div class="col-4">
                            <div class="card border ms-3">
                                <div class="card-header bg-light-secondary p-3 border-bottom">
                                    <h6 class="my-auto">Status Log</h6>
                                </div>
                                <div class="card-body p-3 overflow-auto" style="max-height: 400px;">
                                    @foreach($paymentRequest->status_logs as $idx => $statusLog)
                                        @if($idx != 0)
                                        <div class="w-100 d-flex my-2">
                                            <i class="bi bi-three-dots-vertical text-muted"></i>
                                        </div>
                                        @endif
                                        <div class="w-100 d-flex">
                                            <i class="bi bi-circle-fill {{ $idx == 0 ? 'text-danger' : 'text-muted' }} my-auto" style="font-size: 14px;"></i>
                                            <div class="w-100 d-flex flex-column ms-3">
                                                <div class="w-100 d-flex">
                                                    @if($statusLog->status == 'PENDING')
                                                        <small class="text-secondary my-auto fw-bolder">PENDING</small>
                                                    @elseif($statusLog->status == 'REVIEW')
                                                        <small class="text-primary my-auto fw-bolder">REVIEW</small>
                                                    @elseif($statusLog->status == 'RETURNED')
                                                        <small class="text-warning my-auto fw-bolder">RETURNED</small>
                                                    @elseif($statusLog->status == 'REJECTED')
                                                        <small class="text-danger my-auto fw-bolder">REJECTED</small>
                                                    @elseif($statusLog->status == 'APPROVED')
                                                        <small class="text-success my-auto fw-bolder">APPROVED</small>
                                                    @endif
                                                    <small class="text-secondary my-auto ms-auto">{{ date('d M Y, H:i', strtotime($statusLog->created_at)) }}</small>
                                                </div>
                                                <div class="d-flex">
                                                    <small class="text-black text-truncate">{{ $statusLog->userCreated->name }} ({{ $statusLog->userCreated->division->name }})</small>
                                                </div>
                                                <div class="d-flex">
                                                    <i class="bi bi-chat-left-dots me-2"></i>
                                                    <small>{{ $statusLog->description ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-3">
                    <div class="w-100 d-flex">
                        <a href="/cms/reimbursement" class="btn btn-secondary px-3 ms-auto">Back</a>
                        @if($paymentRequest->user_created == Auth::user()->id && in_array($paymentRequest->status, ['PENDING', 'RETURNED']))
                        <button class="btn btn-primary px-3 ms-2" onclick="updatePayreq()">Edit</button>
                        @endif
                    </div>
                </div>
            </div>
		</div>
	</div>
</section>

<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="changeStatusModalLabel">UPDATE STATUS</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
                    <div class="col-12 mb-2">
                        <div class="form-group row">
                            <label class="col-4 col-form-label" for="status">
                                Status<span class="text-danger">*</span>
                            </label>
                            <div class="col-8">
                                <select class="form-control" id="status">
                                    <option value="">-</option>
                                    @foreach(config('app.pr_status') as $code => $status)
                                        @if(in_array(Auth::user()->role, ['SPV_EMPLOYEE']))
                                            @if(in_array($code, ['PENDING']))
                                            <option value="{{ $code }}" {{ $paymentRequest->status == $code ? 'disabled' : '' }}>{{ $status }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $code }}" {{ $paymentRequest->status == $code ? 'disabled' : '' }}>{{ $status }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label" for="statement">Statement</label>
                            <div class="col-8">
                                <textarea class="form-control" id="statement" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="updatePayreqStatus()">Submit</button>
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
        invoiceTableIdx += 1
        $('#invoiceTable tbody').append(`
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
    
    const removeInvoiceField = (idx) => $(`#invoiceField${idx}`).remove()
    
    let invoices = (@json($paymentRequest->invoices))
    invoices.forEach((invoice, idx) => {
        if(idx != 0) addInvoiceField()

        let invoiceField = $(`#invoiceField${idx}`)
        invoiceField.find('.date').val(invoice.date)
        invoiceField.find('.category').val(invoice.cost_category_id)
        invoiceField.find('.description').val(invoice.description)
        invoiceField.find('.amount').val(invoice.amount)
    })
    
    $(document).on('change', '#employee', function() {
        let option = $(this).find(':selected'),
            division = option.attr('data-division'),
            region = option.attr('data-region')

        $('#division').val(division)
        $('#region').val(region)
    })

    const updatePayreq = () => {
        let code = $('#code').val(),
            employee = $('#employee').val(),
            division = $('#division').val(),
            region = $('#region').val(),
            document = $('#document'),
            description = $('#description').val(),
            invoices = []

        $('#invoiceFields > tr').each(function() {
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
        formData.append('code', code)
        formData.append('employee_id', employee)
        formData.append('division_id', division)
        formData.append('region_id', region)
        formData.append('document', document[0].files[0])
        formData.append('description', description)
        formData.append('invoices', JSON.stringify(invoices))

        $('#loading').removeClass('d-none')

        $.ajax({
            method: 'POST',
            url: '{{ route("reimbursement.update") }}',
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

    const updatePayreqStatus = () => {
        let modal = $('#changeStatusModal'),
            code = $('#code').val(),
            status = modal.find('#status').val(),
            statement = modal.find('#statement').val()

        $('#loading').removeClass('d-none')

        $.ajax({
            method: 'POST',
            url: '{{ route("reimbursement.status.update") }}',
            data: {
                _token: '{{ csrf_token() }}',
                code: code,
                status: status,
                statement: statement
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