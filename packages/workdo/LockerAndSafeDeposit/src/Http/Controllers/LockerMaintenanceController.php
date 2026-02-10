<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LockerMaintenanceDataTable;
use Workdo\LockerAndSafeDeposit\Entities\Locker;
use Workdo\LockerAndSafeDeposit\Entities\LockerMaintenance;
use Workdo\LockerAndSafeDeposit\Events\CreateLockerMaintenance;
use Workdo\LockerAndSafeDeposit\Events\DestroyLockerMaintenance;
use Workdo\LockerAndSafeDeposit\Events\UpdateLockerMaintenance;

class LockerMaintenanceController extends Controller
{
      /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LockerMaintenanceDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('locker_maintenance manage'))
        {
            return $dataTable->render('locker-and-safe-deposit::maintenance.index');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

      /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('locker_maintenance create'))
        {
            $lockers = Locker::where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get()->pluck('locker_number','id');
            $status  = LockerMaintenance::$status;

            return view('locker-and-safe-deposit::maintenance.create' , compact('lockers' , 'status'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

      /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('locker_maintenance create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'locker_id'       => 'required',
                                   'technician_name' => 'required',
                                   'repair_status'   => 'required',
                                   'reported_date'   => 'required',
                                   'repair_date'     => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker-maintenance.index')->with('error', $messages->first());
            }

            $lockerMaintenance                  = new LockerMaintenance();
            $lockerMaintenance->locker_id       = $request->locker_id;
            $lockerMaintenance->technician_name = $request->technician_name;
            $lockerMaintenance->repair_status   = $request->repair_status;
            $lockerMaintenance->reported_date   = $request->reported_date;
            $lockerMaintenance->repair_date     = $request->repair_date;
            $lockerMaintenance->description     = $request->description;
            $lockerMaintenance->workspace       = getActiveWorkSpace();
            $lockerMaintenance->created_by      = creatorId();
            $lockerMaintenance->save();

            event(new CreateLockerMaintenance($request , $lockerMaintenance));
            
            return redirect()->back()->with('success', __('The maintenance & repair has been created successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

      /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('locker-and-safe-deposit::show');
    }

      /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('locker_maintenance edit'))
        {
            $lockerMaintenance = lockerMaintenance::find($id);
            $lockers           = Locker::where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get()->pluck('locker_number','id');
            $status            = LockerMaintenance::$status;

            return view('locker-and-safe-deposit::maintenance.edit', compact('lockerMaintenance' , 'lockers' , 'status'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

      /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('locker_maintenance edit'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'locker_id'       => 'required',
                                   'technician_name' => 'required',
                                   'repair_status'   => 'required',
                                   'reported_date'   => 'required',
                                   'repair_date'     => 'required',
                               ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $lockerMaintenance                  = lockerMaintenance::find($id);
            $lockerMaintenance->locker_id       = $request->locker_id;
            $lockerMaintenance->technician_name = $request->technician_name;
            $lockerMaintenance->repair_status   = $request->repair_status;
            $lockerMaintenance->reported_date   = $request->reported_date;
            $lockerMaintenance->repair_date     = $request->repair_date;
            $lockerMaintenance->description     = $request->description;
            $lockerMaintenance->save();

            event(new UpdateLockerMaintenance($request , $lockerMaintenance));

            return redirect()->back()->with('success', __('The maintenance & repair details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

      /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('locker_maintenance delete'))
        {
            $lockerMaintenance = lockerMaintenance::find($id);
            if (!empty($lockerMaintenance)) {
                $lockerMaintenance->delete();
            }

            event(new DestroyLockerMaintenance($lockerMaintenance));

            return redirect()->route('locker-maintenance.index')->with('success', __('The maintenance & repair has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function description($id)
    {
        $lockerMaintenance = lockerMaintenance::find($id);
        return view('locker-and-safe-deposit::maintenance.description', compact('lockerMaintenance'));
    }
}
