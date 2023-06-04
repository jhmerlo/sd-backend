<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Computer;
use App\Models\Processor;
use App\Models\Motherboard;
use App\Models\RamMemory;
use App\Models\StorageDevice;
use App\Models\PowerSupply;
use App\Models\Gpu;
use App\Models\Monitor;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index () {

        // computers by steps
        $stepLabels = ['Etapa 1', 'Etapa 2', 'Etapa 3', 'Etapa 4', 'Etapa 5', 'Etapa 6'];
        $stepData = [
            count(Computer::where('current_step', 1)->get()), 
            count(Computer::where('current_step', 2)->get()), 
            count(Computer::where('current_step', 3)->get()), 
            count(Computer::where('current_step', 4)->get()), 
            count(Computer::where('current_step', 5)->get()), 
            count(Computer::where('current_step', 6)->get())
        ];


        // borrowed computers
        $totalComputers = count(Computer::all());
        $borrowedComputers = count(Computer::all()->filter(function ($item) {
            return $item->borrowed;
        }));

        $borrowedLabels = ['Emprestado', 'Disponível'];
        $borrowedData = [$borrowedComputers, $totalComputers - $borrowedComputers];


        // components distrib

        $componentLabels = ['Placa-Mãe', 'Processador', 'Memória RAM', 'Armazenamento', 'Alimentação', 'GPU', 'Monitor'];
        $componentsData = [
            count(Motherboard::all()),
            count(Processor::all()),
            count(RamMemory::all()),
            count(StorageDevice::all()),
            count(PowerSupply::all()),
            count(Gpu::all()),
            count(Monitor::all())
        ];


        //responsible computers

        $id = Auth::user()->institutional_id;

        $responsibleComputers = Computer::where('current_step_responsible_id', $id)->get();

        return response()->json([
            'computers_by_steps' => [ 'step_labels' => $stepLabels, 'step_data' => $stepData ],
            'borrowed_computers' => [ 'borrowed_labels' => $borrowedLabels, 'borrowed_data' => $borrowedData ],
            'components' => [ 'components_labels' => $componentLabels, 'components_data' => $componentsData ],
            'responsible_computers' => $responsibleComputers
        ], 200);
    }
}
