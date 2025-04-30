<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClinicController extends Controller
{
    //
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_number' => 'required|unique:users,document_number',
            ]);

            $paciente = new User();
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->password = bcrypt($request->password);
            $paciente->document_type = 'cnpj';
            $paciente->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $paciente->type = 'clinic';
            $paciente->status = 'active';
            $paciente->situation = 'pending';
            $paciente->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Solicitação de conta de clínica enviada com sucesso! Aguarde a validação do administrador.')
                ->with('success_redirect', route('home'));

        } catch (\Throwable $th) {
            return redirect()->back()
            ->withErrors($th->getMessage())
            ->withInput();
        }
    }


    public function clinics()
    {
        $clinics = User::where('type', 'clinic')->get();
        return view('Dashboard.admin.index', ['clinics' => $clinics]);
    }

    public function updateClinic(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
                'situation' => 'required|in:valid,invalid,pending', // Corrigido para match com o form
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'nullable|min:6', // Adicionado nullable
                'status' => 'required|in:active,inactive', // Adicionado required
                'document_number' => [
                    'required',
                    Rule::unique('users')->ignore($request->id)
                ],
            ]);


            $clinic = User::findOrFail($request->id);

             $clinic->update([
                 'situation' => $request->situation, // Ou renomeie no banco para 'situacao'
                 'email' => $request->email,
                 'name' => $request->name,
                 'status' => $request->status,
                 'password' => $request->password ? bcrypt($request->password) : $clinic->password,
                 'document_number' => preg_replace('/[^0-9]/', '', $request->document_number),
             ]);

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Clínica atualizada com sucesso!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }

    }
}
