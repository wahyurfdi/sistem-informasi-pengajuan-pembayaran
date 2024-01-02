@extends('layouts.app', [
	'title' => 'Report: Reimbursement',
	'breadcrumbs' => [
		['url' => '', 'name' => 'Report'],
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
                        <div class="col-5">
                            <form class="w-100">
                                <div class="d-flex">
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
                                        <a href="/cms/report/reimbursement" class="btn btn-secondary ms-2">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-7">
                            <div class="float-end">
                                <button class="btn btn-success">
                                    <i class="bi bi-file-earmark-excel"></i> Export
                                </button>
                            </div>
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
							</tr>
                            @endforeach
						</tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th class="text-end">Rp {{ number_format($invoiceTotal, 0, ',', '.') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
					</table>
                </div>
            </div>
		</div>
	</div>
</section>
@endsection

@section('script')
<script>	
	new DataTable('#table', {
		paging: false,
		searching: false,
		bInfo: false
	})
</script>
@endsection