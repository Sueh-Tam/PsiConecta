<?php

namespace App\Http\Controllers;

use App\Models\Avaliability;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliabilityController extends Controller
{

    private const diasSemanaMap = [
        'domingo' => 0,
        'segunda' => 1,
        'terca' => 2,
        'terça' => 2,
        'quarta' => 3,
        'quinta' => 4,
        'sexta' => 5,
        'sabado' => 6,
        'sábado' => 6,
    ];
    public function index()
    {
    }


    public function create()
    {
    }


    public function store(Request $request)
{
    try {
        $dayOfWeek = strtolower($request->input('day_of_week')); // ex: "segunda"
        $dataInicio = $request->input('dt_start');
        $dataFim = $request->input('dt_end');
        $startTimes = $request->input('start_time', []);
        $endTimes = $request->input('end_time', []);
        $psychologistId = Auth::user()->id;

        $request->validate([
            'day_of_week' => 'required',
            'dt_start' => 'required|date',
            'dt_end' => 'required|date|after_or_equal:dt_start',
            'start_time' => 'required|array',
            'end_time' => 'required|array'
        ], [
            'day_of_week.required' => 'O dia da semana é obrigatório.',
            'dt_start.required' => 'A data inicial é obrigatória.',
            'dt_start.date' => 'A data inicial deve ser uma data válida.',
            'dt_end.required' => 'A data final é obrigatória.',
            'dt_end.date' => 'A data final deve ser uma data válida.',
            'dt_end.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
            'start_time.required' => 'O horário inicial é obrigatório.',
            'start_time.array' => 'O formato do horário inicial é inválido.',
            'end_time.required' => 'O horário final é obrigatório.',
            'end_time.array' => 'O formato do horário final é inválido.'
        ]);

        $datasHorarios = $this->getDayBetweenDates($dayOfWeek, $dataInicio, $dataFim, $startTimes, $endTimes);

        foreach ($datasHorarios as $item) {
            $exists = Avaliability::where('id_psychologist', $psychologistId)
                ->where('dt_avaliability', $item['dt_avaliability'])
                ->where('hr_avaliability', $item['hr_avaliability'])
                ->exists();

            if (!$exists) {
                Avaliability::create([
                    'id_psychologist' => $psychologistId,
                    'dt_avaliability' => $item['dt_avaliability'],
                    'hr_avaliability' => $item['hr_avaliability'],
                ]);
            }

            
        }

        return redirect()->back()->with('success', 'Disponibilidades salvas com sucesso!');
    } catch (\Throwable $th) {
        return redirect()->back()
            ->withErrors($th->getMessage())
            ->withInput();
    }
}


    private function getDayBetweenDates($dayOfWeek, $dataInicio, $dataFim, array $startTimes, array $endTimes)
    {
        $periodo = CarbonPeriod::create($dataInicio, $dataFim);
        $datas = [];
        $diaNumero = self::diasSemanaMap[$dayOfWeek] ?? null;

        if ($diaNumero === null) {
            throw new \InvalidArgumentException("Dia da semana inválido.");
        }

        foreach ($periodo as $date) {
            if ($date->dayOfWeek === $diaNumero) {
                foreach ($startTimes as $index => $startTime) {
                    $endTime = $endTimes[$index] ?? null;
                    if ($endTime === null) continue; // ignora se não houver par

                    $horario = $startTime . '-' . $endTime;
                    $datas[] = [
                        'dt_avaliability' => $date->toDateString(),
                        'hr_avaliability' => $horario
                    ];
                }
            }
        }

        return $datas;
    }

    public function getDisponibility()
    {
        $disponibilidade = Avaliability::where('id_psychologist', Auth::user()->id)
            ->orderBy('dt_avaliability')
            ->orderBy('hr_avaliability')
            ->get();

        return view('Dashboard.Psychologists.disponibility', [
            'disponibilidade' => $disponibilidade,
            'timeBlocks' => Avaliability::TIME_BLOCKS
        ]);
    }


    public function show(Avaliability $avaliability)
    {
        $user = Auth::user(); // psicólogo autenticado

        $now = Carbon::now();
    $availabilities = Avaliability::where('id_psychologist', $user->id)
        ->whereNull('deleted_at')
        ->where('dt_avaliability', '>=', $now)
        ->orderBy('dt_avaliability')
        ->get()
        ->groupBy(function ($item) {
            return Carbon::parse($item->dt_avaliability)->locale('pt_BR')->dayName;
        });

        return view('Dashboard.Psychologists.disponibility', [
            'groupedAvailabilities' => $availabilities,
            'timeBlocks' => Avaliability::TIME_BLOCKS
        ]);
    }


    public function edit(Avaliability $avaliability)
    {
    }


    public function update(Request $request, Avaliability $avaliability)
    {
    }


    public function destroy($id)
{
        $avaliability = Avaliability::where('id', $id)
            ->where('id_psychologist', Auth::id())
            ->firstOrFail();

        $avaliability->delete();

        return redirect()->back()
            ->with('success_message', 'Horário excluído com sucesso!');
    }
    public function deactivate(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fim' => 'nullable|date_format:H:i|after_or_equal:hora_inicio',
            'dia_semana' => 'nullable|integer|min:0|max:6',
        ], [
            'data_inicio.required' => 'A data inicial é obrigatória.',
            'data_inicio.date' => 'A data inicial deve ser uma data válida.',
            'data_fim.required' => 'A data final é obrigatória.',
            'data_fim.date' => 'A data final deve ser uma data válida.',
            'data_fim.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
            'hora_inicio.date_format' => 'O formato do horário inicial deve ser HH:MM.',
            'hora_fim.date_format' => 'O formato do horário final deve ser HH:MM.',
            'hora_fim.after_or_equal' => 'O horário final deve ser igual ou posterior ao horário inicial.',
            'dia_semana.integer' => 'O dia da semana deve ser um número inteiro.',
            'dia_semana.min' => 'O dia da semana deve ser entre 0 (domingo) e 6 (sábado).',
            'dia_semana.max' => 'O dia da semana deve ser entre 0 (domingo) e 6 (sábado).'
        ]);

        $query = Avaliability::where('id_psychologist', Auth::id())
            ->whereBetween('dt_avaliability', [
                Carbon::parse($request->data_inicio)->startOfDay(),
                Carbon::parse($request->data_fim)->endOfDay()
            ])
            ->whereNull('deleted_at');

        if ($request->filled('dia_semana')) {
            $query->whereRaw('WEEKDAY(dt_avaliability) = ?', [(int) $request->dia_semana - 1]);
        }

        if ($request->filled('hora_inicio') && $request->filled('hora_fim')) {
            $query->whereBetween('hr_avaliability', [$request->hora_inicio, $request->hora_fim]);
        }

        $horariosAfetados = $query->count();

        $query->update([
            'status' => 'unvailable',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success_message', "$horariosAfetados horário(s) inativado(s) com sucesso.");
    }
    public function restore(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'dia_semana' => 'nullable|integer|between:0,6',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fim' => 'nullable|date_format:H:i|after:hora_inicio',
        ], [
            'data_inicio.required' => 'A data inicial é obrigatória.',
            'data_inicio.date' => 'A data inicial deve ser uma data válida.',
            'data_fim.required' => 'A data final é obrigatória.',
            'data_fim.date' => 'A data final deve ser uma data válida.',
            'data_fim.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
            'dia_semana.integer' => 'O dia da semana deve ser um número inteiro.',
            'dia_semana.between' => 'O dia da semana deve ser entre 0 (domingo) e 6 (sábado).',
            'hora_inicio.date_format' => 'O formato do horário inicial deve ser HH:MM.',
            'hora_fim.date_format' => 'O formato do horário final deve ser HH:MM.',
            'hora_fim.after' => 'O horário final deve ser posterior ao horário inicial.'
        ]);

        $userId = Auth::id();

        $query = Avaliability::onlyTrashed() // <-- recupera apenas os que foram soft deleted
            ->where('id_psychologist', $userId)
            ->whereBetween('dt_avaliability', [$request->data_inicio, $request->data_fim]);

        if ($request->filled('dia_semana')) {
            $query->whereRaw('WEEKDAY(dt_avaliability) = ?', [$request->dia_semana == 0 ? 6 : $request->dia_semana - 1]);
        }

        if ($request->filled('hora_inicio') && $request->filled('hora_fim')) {
            $query->whereBetween('hr_avaliability', [$request->hora_inicio, $request->hora_fim]);
        }

        $restauradas = $query->restore();

        return back()->with('success', "$restauradas disponibilidades restauradas com sucesso.");
    }

}
