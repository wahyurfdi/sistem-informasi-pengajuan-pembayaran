<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Division;
use App\Models\Region;
use App\Models\CostCategory;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestInvoice;
use App\Models\PaymentRequestStatusLog;

class PaymentRequestController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $userRole = Auth::user()->role;
        $users = User::orderBy('name', 'ASC')->get();
        $divisions = Division::orderBy('name', 'ASC')->get();
        $regions = Region::orderby('name', 'ASC')->get();
        $costCategories = CostCategory::orderBy('name', 'ASC')->get();

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

        return view('reimbursement.index', compact('paymentRequests', 'filters', 'users', 'divisions', 'regions', 'costCategories'));
    }

    public function detail($payreqCode)
    {
        $users = User::orderBy('name', 'ASC')->get();
        $divisions = Division::orderBy('name', 'ASC')->get();
        $regions = Region::orderby('name', 'ASC')->get();
        $costCategories = CostCategory::orderBy('name', 'ASC')->get();

        $paymentRequest = PaymentRequest::where('code', $payreqCode)->first();
        
        $paymentRequestInvoices = PaymentRequestInvoice::where('payment_request_code', $payreqCode)->get();
        foreach($paymentRequestInvoices as $paymentRequestInvoice) {
            $paymentRequestInvoice->amount = floatval($paymentRequestInvoice->amount);
        }
        $paymentRequest['invoices'] = $paymentRequestInvoices;

        $paymentRequestStatusLogs = PaymentRequestStatusLog::with(['userCreated', 'userCreated.division'])
            ->where('payment_request_code', $payreqCode)
            ->orderBy('created_at', 'DESC')
            ->get();
        $paymentRequest['status_logs'] = $paymentRequestStatusLogs;

        return view('reimbursement.detail', compact('paymentRequest', 'users', 'divisions', 'regions', 'costCategories'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'employee_id' => 'required',
            'division_id' => 'required',
            'region_id' => 'required',
            'document' => 'required',
            'invoices' => 'required',
        ], [
            'employee_id.required' => 'Employee tidak boleh kosong',
            'division_id.required' => 'Division tidak boleh kosong',
            'region_id.required' => 'Region tidak boleh kosong',
            'document.required' => 'Document tidak boleh kosong',
            'invoices.required' => 'Invoices tidak boleh kosong',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->messages();
            foreach($errors as $error) {
                return $this->responseJson('FAIL', $error[0]);
            }
        }

        DB::beginTransaction();
        try {
            $prCode = 'PR'.date('ymd').rand(1000, 9999);
            $prDescription = !empty($request->description) ? $request->description : null;

            $document = null;
            if($request->file('document')) {
                $file = $request->file('document');
                $fileName = $prCode.'.'.$file->getClientOriginalExtension();
                $filePath = $request->file('document')->storeAs('public/'.config('app.pr_document_path'), $fileName);

                $document = $fileName;
            }

            PaymentRequest::create([
                    'code' => $prCode,
                    'status' => 'PENDING',
                    'user_requested' => $request->employee_id,
                    'requested_region_id' => $request->region_id,
                    'requested_division_id' => $request->division_id,
                    'description' => $prDescription,
                    'document' => $document,
                    'user_created' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            $invoices = json_decode($request->invoices);
            foreach($invoices as $invoice) {
                PaymentRequestInvoice::create([
                        'payment_request_code' => $prCode,
                        'date' => $invoice->date,
                        'cost_category_id' => $invoice->category_id,
                        'description' => !empty($invoice->description) ? $invoice->description : null,
                        'amount' => floatval($invoice->amount),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            PaymentRequestStatusLog::create([
                    'payment_request_code' => $prCode,
                    'status' => 'PENDING',
                    'description' => $prDescription,
                    'user_created' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            DB::commit();
            return $this->responseJson('OK', 'Berhasil Menambah Data');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseJson('FAIL', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'employee_id' => 'required',
            'division_id' => 'required',
            'region_id' => 'required',
            'document' => 'required',
            'invoices' => 'required',
        ], [
            'code.required' => 'Code tidak boleh kosong',
            'employee_id.required' => 'Employee tidak boleh kosong',
            'division_id.required' => 'Division tidak boleh kosong',
            'region_id.required' => 'Region tidak boleh kosong',
            'document.required' => 'Document tidak boleh kosong',
            'invoices.required' => 'Invoices tidak boleh kosong',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->messages();
            foreach($errors as $error) {
                return $this->responseJson('FAIL', $error[0]);
            }
        }

        DB::beginTransaction();
        try {
            $payreqCode = $request->code;
            $payreqUpdate = [
                'user_requested' => $request->employee_id,
                'requested_region_id' => $request->region_id,
                'requested_division_id' => $request->division_id,
                'description' => !empty($request->description) ? $request->description : null,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if($request->file('document')) {
                $file = $request->file('document');
                $fileName = $prCode.'.'.$file->getClientOriginalExtension();
                $filePath = $request->file('document')->storeAs('public/'.config('app.pr_document_path'), $fileName);

                $payreqUpdate['document'] = $fileName;
            }

            PaymentRequest::where('code', $payreqCode)->update($payreqUpdate);

            PaymentRequestInvoice::where('payment_request_code', $payreqCode)->delete();
            $invoices = json_decode($request->invoices);
            foreach($invoices as $invoice) {
                PaymentRequestInvoice::create([
                        'payment_request_code' => $payreqCode,
                        'date' => $invoice->date,
                        'cost_category_id' => $invoice->category_id,
                        'description' => !empty($invoice->description) ? $invoice->description : null,
                        'amount' => floatval($invoice->amount),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            
            DB::commit();
            return $this->responseJson('OK', 'Berhasil Mengubah Data');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseJson('FAIL', $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'status' => 'required',
            // 'statement' => 'required',
        ], [
            'code.required' => 'Code tidak boleh kosong',
            'status.required' => 'Status tidak boleh kosong',
            // 'statement.required' => 'Statement tidak boleh kosong',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->messages();
            foreach($errors as $error) {
                return $this->responseJson('FAIL', $error[0]);
            }
        }

        DB::beginTransaction();
        try {
            $payreqCode = $request->code;
            $status = $request->status;
            $statement = $request->statement;

            PaymentRequest::where('code', $payreqCode)
                ->update([
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            PaymentRequestStatusLog::create([
                    'payment_request_code' => $payreqCode,
                    'status' => $status,
                    'description' => !empty($statement) ? $statement : null,
                    'user_created' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            DB::commit();
            return $this->responseJson('OK', 'Berhasil Mengubah Data');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseJson('FAIL', $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'payment_request_code' => 'required',
        ], [
            'payment_request_code.required' => 'Code tidak boleh kosong',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->messages();
            foreach($errors as $error) {
                return $this->responseJson('FAIL', $error[0]);
            }
        }

        DB::beginTransaction();
        try {
            $paymentRequestCode = $request->payment_request_code;
            PaymentRequest::where('code', $paymentRequestCode)->delete();
            PaymentRequestInvoice::where('payment_request_code', $paymentRequestCode)->delete();
            PaymentRequestStatusLog::where('payment_request_code', $paymentRequestCode)->delete();

            DB::commit();
            return $this->responseJson('OK', 'Berhasil Menghapus Data');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseJson('FAIL', $e->getMessage());
        }
    }
}
