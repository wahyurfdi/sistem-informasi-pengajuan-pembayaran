<!DOCTYPE html>
<html>
    <head>
        <title>SIAPP - Telkom Akses</title>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
        <style>
            body { font-size: 10px; }
        </style>
    </head>
    <body>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Code</th>
                    <th>Employee</th>
                    <th>User Created</th>
                    <th>Created At</th>
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
                    <td>{{ $paymentRequest->userCreated->name }}</td>
                    <td>{{ date('d M Y, H:i:s', strtotime($paymentRequest->created_at)) }}</td>
                    <td class="text-end">
                        @php $invoiceTotal += $paymentRequest->invoices->sum('amount'); @endphp
                        
                        Rp {{ number_format($paymentRequest->invoices->sum('amount'), 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($paymentRequest->status == 'PENDING')
                            Pending
                        @elseif($paymentRequest->status == 'REVIEW')
                            Review
                        @elseif($paymentRequest->status == 'RETURNED')
                            Returned
                        @elseif($paymentRequest->status == 'REJECTED')
                            Rejected
                        @elseif($paymentRequest->status == 'APPROVED')
                            Approved
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">Total</th>
                    <th class="text-end">Rp {{ number_format($invoiceTotal, 0, ',', '.') }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </tbody>
</html>