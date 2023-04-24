<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorageDeviceRequest;
use App\Models\StorageDevice;
use App\Models\Computer;
use App\Models\TransferHistory;
use Auth;

class StorageDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = StorageDevice::query();

        $filters = ['computer_id', 'model', 'manufacturer', 'functional', 'type', 'connection_technology'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }

        return $query->simplePaginate($recordsPerPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorageDeviceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorageDeviceRequest $request)
    {
        $validatedData = $request->validated();

        $storage_device = new StorageDevice;

        $storage_device->fill($validatedData);

        $storage_device->save();

        return response()->json([
            'message' => "Dispositivo de armazenamento criado com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StorageDevice  $storage_device
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $storage_device = StorageDevice::findOrFail($request->id);

        return response()->json([
            'storage_device' => $storage_device
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StorageDeviceRequest  $request
     * @param  \App\Models\StorageDevice  $storage_device
     * @return \Illuminate\Http\Response
     */
    public function update(StorageDeviceRequest $request)
    {
        $storage_device = StorageDevice::findOrFail($request->id);
        
        $validatedData = $request->validated();

        $storage_device->fill($validatedData);

        $changedComputerId = $storage_device->isDirty('computer_id');
        $changedFunctionalFieldToFalse = $storage_device->isDirty('functional') && $storage_device['functional'] == false;

        if ($storage_device->isDirty()) {
            if (($changedComputerId || $changedFunctionalFieldToFalse) && !is_null($storage_device->getOriginal('computer_id'))) {
                $computer = Computer::findOrFail($storage_device->getOriginal('computer_id'));

                //check if exists other functional storagedevice
                $existsOtherFunctional = count($computer->storageDevices->where('functional', true)) >= 2;

                if ($computer['current_step'] > 2 && !$existsOtherFunctional) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }

            if ($changedComputerId) {
                $transfer_history = new TransferHistory;

                $transfer_history->fill([
                    'source_id' => $storage_device->getOriginal('computer_id'),
                    'target_id' => $storage_device['computer_id'],
                    'responsible_id' => Auth::user()->institutional_id,
                    'transferable_id' => $storage_device['id'],
                    'transferable_type' => 'App\\Models\\StorageDevice'
                ]);
                
                $transfer_history->save();
            }

            $storage_device->save();
        }
        
        return response()->json([
            'message' => 'Dispositivo de armazenamento editado com sucesso!',
            'storageDevice' => $storage_device
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StorageDevice  $storage_device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $storage_device = StorageDevice::findOrFail($request->id);

        $storage_device->delete();

        return response()->json([
            'message' => "Dispositivo de armazenamento deletado com sucesso!"
        ], 200);
    }
}
