<?php

namespace App\Http\Controllers\Attendant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AttendantController extends Controller
{
    //
    public function attendantByClinic(){
        $user = Auth::user();
        $attendants = $user
            ->attendants()
            ->paginate(10);
        return view('Dashboard.clinic.attendant.index', compact('attendants'));
    }
    public function store(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_number' => 'required|unique:users,document_number',
                'status' => 'required|in:active,inactive',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'email.required' => 'O campo email é obrigatório.',
                'email.email' => 'O email deve ter um formato válido.',
                'email.unique' => 'Este email já está sendo usado por outro usuário.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
                'document_number.required' => 'O campo número do documento é obrigatório.',
                'document_number.unique' => 'Este número de documento já está sendo usado por outro usuário.',
                'status.required' => 'O campo status é obrigatório.',
                'status.in' => 'O status deve ser ativo ou inativo.',
            ]);
            $attendant = new User();
            $attendant->id_clinic = Auth::user()->id;
            $attendant->name = $request->name;
            $attendant->email = $request->email;
            $attendant->password = bcrypt($request->password);
            $attendant->document_type = 'cpf';
            $attendant->document_number = preg_replace('/[^0-9]/', '', $request->document_number);
            $attendant->type = 'attendant';
            $attendant->status = $request->status;
            $attendant->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Atendente cadastrado com sucesso!!');

        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $attendant = User::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $attendant->id,
                'password' => 'nullable|string|min:6',
                'document_number' => 'required|string|unique:users,document_number,' . $attendant->id,
                'status' => 'required|in:active,inactive',
            ]);

            $attendant->update([
                'name' => $request->name,
                'email' => $request->email,
                'document_number' => preg_replace('/[^0-9]/', '', $request->document_number),
                'status' => $request->status,
                'password' => $request->password ? bcrypt($request->password) : $attendant->password,
            ]);

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Atendente cadastrado com sucesso!!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }

    }
}
