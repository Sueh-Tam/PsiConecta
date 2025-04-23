<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{
    //

    public function store(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_number' => 'required|unique:users,document_number',
                'appointment_price' => 'required|numeric',
                'status' => 'required|in:active,inactive',
            ]);
            $psychologist = new User();
            $psychologist->id_clinic = Auth::user()->id;
            $psychologist->name = $request->name;
            $psychologist->email = $request->email;
            $psychologist->password = bcrypt($request->password);
            $psychologist->document_type = 'crp';
            $psychologist->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $psychologist->type = 'psychologist';
            $psychologist->status = $request->status;
            $psychologist->appointment_price = $request->appointment_price;
            $psychologist->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Psicólogo cadastrado com sucesso!!');

        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }


    }

    public function update(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'document_number' => 'required|unique:users,document_number,'.$request->id,
                'appointment_price' => 'required|numeric',
                'status' => 'required|in:active,inactive',
            ]);
            $psychologist = User::find($request->id);
            $psychologist->name = $request->name;
            $psychologist->email = $request->email;
            $psychologist->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $psychologist->status = $request->status;
            $psychologist->appointment_price = $request->appointment_price;
            $psychologist->document_type = 'crp';
            if($request->password){
                $psychologist->password = bcrypt($request->password);
            }
            $psychologist->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Psicólogo atualizado com sucesso!!');

        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }

    public function psychologistByClinic(Request $request){

        $user = Auth::user();
        $psychologists = $user
            ->psychologists()
            ->paginate(10);
        // $psychologists = User::where('id_clinic', Auth::user()->id)
        //     ->where('type', 'psychologist')
        //     ->get();
        return view('Dashboard.clinic.psychologist.index', ['psychologists' => $psychologists]);

    }
}
