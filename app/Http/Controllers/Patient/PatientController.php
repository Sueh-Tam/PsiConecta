<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->merge([
                'document_number' => preg_replace('/[^0-9]/', '', $request->document_number)
            ]);
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_type' => 'required|in:cpf,rg',
                'document_number' => 'required|unique:users,document_number',
            ]);


            $paciente = new User();
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->password = bcrypt($request->password);
            $paciente->document_type = strtolower($request->document_type);
            $paciente->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $paciente->type = 'patient';
            $paciente->status = 'active';
            $paciente->save();
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Paciente cadastrado com sucesso!')
                ->with('success_redirect', route('home'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }

    }
}
