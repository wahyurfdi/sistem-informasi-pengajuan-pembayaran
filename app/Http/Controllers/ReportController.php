<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;

class ReportController extends Controller
{
    public function paymentRequestIndex(Request $request)
    {
        $userId = Auth::user()->id;
        $userRole = Auth::user()->role;

        $filters = (object) [
            'search' => $request->search ?? '',
            'status' => $request->status ?? ''
        ];

        $paymentRequests = PaymentRequest::with(['userRequested', 'userCreated', 'invoices'])
            ->when(in_array($userRole, ['SPV_EMPLOYEE', 'EMPLOYEE']), function($query) use($userId) {
                $query->where(function($query) use($userId) {
                    $query->where('user_requested', $userId)
                        ->orWhere('user_created', $userId);
                });
            })
            ->when(!empty($filters->search), function($query) use($filters) {
                $query->where('code', 'LIKE', '%'.$filters->search.'%');
            })
            ->when(!empty($filters->status), function($query) use($filters) {
                $query->where('status', $filters->status);
            })
            ->orderby('updated_at', 'DESC')
            ->get();

        return view('reports.reimbursement', compact('paymentRequests', 'filters'));
    }
}
