<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LockerDataTable;
use Workdo\LockerAndSafeDeposit\Entities\Locker;
use Workdo\LockerAndSafeDeposit\Events\CreateLocker;
use Workdo\LockerAndSafeDeposit\Events\DestroyLocker;
use Workdo\LockerAndSafeDeposit\Events\UpdateLocker;

class LockerController extends Controller
{
          /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LockerDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('locker manage'))
        {
            return $dataTable->render('locker-and-safe-deposit::locker.index');
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
        if(Auth::user()->isAbleTo('locker create'))
        {
            $status       = Locker::$status;
            $lockerNumber = '#LOC' . sprintf("%05d",($this->lockerNumber()));

            return view('locker-and-safe-deposit::locker.create' , compact('status' , 'lockerNumber'));
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
        if(Auth::user()->isAbleTo('locker create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'locker_type'    => 'required',
                                   'locker_size'    => 'required',
                                   'max_capacity'   => 'required',
                                   'price_of_month' => 'required',
                                   'price_of_year'  => 'required',
                                   'status'         => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker.index')->with('error', $messages->first());
            }

            $locker                 = new Locker();
            $locker->locker_number  = $request->locker_number;
            $locker->locker_type    = $request->locker_type;
            $locker->locker_size    = $request->locker_size;
            $locker->max_capacity   = $request->max_capacity;
            $locker->price_of_month = $request->price_of_month;
            $locker->price_of_year  = $request->price_of_year;
            $locker->status         = $request->status;
            $locker->workspace      = getActiveWorkSpace();
            $locker->created_by     = creatorId();
            $locker->save();

            event(new CreateLocker($request , $locker));
            
            return redirect()->back()->with('success', __('The locker has been created successfully.'));
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
        if (Auth::user()->isAbleTo('locker edit'))
        {
            $locker = Locker::find($id);
            $status = Locker::$status;

            return view('locker-and-safe-deposit::locker.edit', compact('locker' , 'status'));
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
        if (Auth::user()->isAbleTo('locker edit'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'locker_type'    => 'required',
                                   'locker_size'    => 'required',
                                   'max_capacity'   => 'required',
                                   'price_of_month' => 'required',
                                   'price_of_year'  => 'required',
                                   'status'         => 'required',
                               ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $locker                 = Locker::find($id);
            $locker->locker_type    = $request->locker_type;
            $locker->locker_size    = $request->locker_size;
            $locker->max_capacity   = $request->max_capacity;
            $locker->price_of_month = $request->price_of_month;
            $locker->price_of_year  = $request->price_of_year;
            $locker->status         = $request->status;
            $locker->save();

            event(new UpdateLocker($request , $locker));

            return redirect()->back()->with('success', __('The locker details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('locker delete'))
        {
            $locker = Locker::find($id);
            if (!empty($locker)) {
                $locker->delete();
            }

              event(new DestroyLocker($locker));

            return redirect()->route('locker.index')->with('success', __('The locker has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function lockerNumber()
    {
        $latest = Locker::where('workspace' , getActiveWorkSpace())->where('created_by', creatorId())->latest()->first();
        if ($latest == null) {
            return 1;
        } else {
            return $latest->locker_number + 1;
        }
    }
}
