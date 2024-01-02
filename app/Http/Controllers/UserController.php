<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Division;
use App\Models\Region;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['division', 'region'])
            ->orderBy('updated_at', 'DESC')
            ->orderBy('name', 'ASC')
            ->get();

        $divisions = Division::orderBy('name', 'ASC')->get();
        $regions = Region::orderBy('name', 'ASC')->get();

        return view('master-data.user-account', compact('users', 'divisions', 'regions'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'division_id' => 'required',
            'region_id' => 'required',
            'role' => 'required'
        ], [
            'code.required' => 'Code tidak boleh kosong',
            'name.required' => 'Name tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'division_id.required' => 'Division tidak boleh kosong',
            'region_id.required' => 'Region tidak boleh kosong',
            'role.required' => 'Role tidak boleh kosong'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->messages();
            foreach($errors as $error) {
                return $this->responseJson('FAIL', $error[0]);
            }
        }

        $store = User::create([
                'code' => $request->code,
                'name' => $request->name,
                'email' => $request->username,
                'password' => bcrypt($request->password),
                'division_id' => $request->division_id,
                'region_id' => $request->region_id,
                'role' => $request->role,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if(!$store) return $this->responseJson('FAIL', 'Terjadi Kesalahan Saat Menambah Data');
        return $this->responseJson('OK', 'Berhasil Menambah Data');
    }
}
