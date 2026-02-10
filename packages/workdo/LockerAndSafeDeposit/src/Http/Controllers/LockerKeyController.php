<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LockerKeyDataTable;
use Workdo\LockerAndSafeDeposit\Entities\Locker;
use Workdo\LockerAndSafeDeposit\Entities\LockerBooking;
use Workdo\LockerAndSafeDeposit\Entities\LockerKey;
use Workdo\LockerAndSafeDeposit\Events\CreateLockerKey;
use Workdo\LockerAndSafeDeposit\Events\DestroyLockerKey;
use Workdo\LockerAndSafeDeposit\Events\UpdateLockerKey;

class LockerKeyController extends Controller
{
                /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index(LockerKeyDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('locker_keys manage'))
        {
            return $dataTable->render('locker-and-safe-deposit::key.index');
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
        if(Auth::user()->isAbleTo('locker_keys create'))
        {
            $lockers = Locker::where('status', 'Available')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())
            ->whereHas('bookings', function ($query) {
                $query->whereRaw('
                    CASE 
                        WHEN locker_bookings.duration = "monthly" THEN DATE_ADD(locker_bookings.start_date, INTERVAL 1 MONTH)
                        WHEN locker_bookings.duration = "yearly" THEN DATE_ADD(locker_bookings.start_date, INTERVAL 1 YEAR)
                        ELSE locker_bookings.start_date 
                    END > CURDATE()'
                );
            })
            ->get();
    
            $customers = [];

            return view('locker-and-safe-deposit::key.create' , compact('lockers' , 'customers'));
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
        if(Auth::user()->isAbleTo('locker_keys create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                'locker_id'   => 'required',
                                'customer_id' => 'required',
                                'issue_date'  => 'required',
                                'key_type'    => 'required',
                            ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker-key.index')->with('error', $messages->first());
            }

            $lockerKey              = new LockerKey();
            $lockerKey->locker_id   = $request->locker_id;
            $lockerKey->customer_id = $request->customer_id;
            $lockerKey->issue_date  = $request->issue_date;
            $lockerKey->key_type    = $request->key_type;
            $lockerKey->workspace   = getActiveWorkSpace();
            $lockerKey->created_by  = creatorId();
            $lockerKey->save();

            event(new CreateLockerKey($request , $lockerKey));
            
            return redirect()->back()->with('success', __('The key has been created successfully.'));
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
                //
    }

                /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('locker_keys edit'))
        {
            $lockerKey = LockerKey::find($id);
            $lockers   = Locker::where('status', 'Available')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())
            ->whereHas('bookings', function ($query) {
                $query->whereRaw('
                    CASE 
                        WHEN locker_bookings.duration = "monthly" THEN DATE_ADD(locker_bookings.start_date, INTERVAL 1 MONTH)
                        WHEN locker_bookings.duration = "yearly" THEN DATE_ADD(locker_bookings.start_date, INTERVAL 1 YEAR)
                        ELSE locker_bookings.start_date 
                    END > CURDATE()'
                );
            })
            ->get();
            $customers = [];

            return view('locker-and-safe-deposit::key.edit', compact('lockerKey' , 'lockers' , 'customers'));
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
        if (Auth::user()->isAbleTo('locker_keys edit'))
        {
            $validator = Validator::make(
                $request->all(), [
                                    'locker_id'   => 'required',
                                    'customer_id' => 'required',
                                    'issue_date'  => 'required',
                                    'key_type'    => 'required',
                            ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $lockerKey              = LockerKey::find($id);
            $lockerKey->locker_id   = $request->locker_id;
            $lockerKey->customer_id = $request->customer_id;
            $lockerKey->issue_date  = $request->issue_date;
            $lockerKey->key_type    = $request->key_type;
            $lockerKey->save();

            event(new UpdateLockerKey($request , $lockerKey));

            return redirect()->back()->with('success', __('The key details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('locker_keys delete'))
        {
            $lockerKey = LockerKey::find($id);
            if (!empty($lockerKey)) {
                $lockerKey->delete();
            }

            event(new DestroyLockerKey($lockerKey));

            return redirect()->route('locker-key.index')->with('success', __('The key has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getCustomer(Request $request) 
    {
        $lockerBooking = LockerBooking::where('locker_id' , $request->locker_id)->first();

        $durationMap = [
            'monthly' => '+1 month',
            'yearly'  => '+1 year'
        ];
        
        $lockerKey = LockerKey::where('locker_id', $request->locker_id)->first();
        
        if(!empty($lockerKey)) {
            return response()->json([
                'status'      => false,
                'customer'    => !empty($lockerBooking->customer) ? $lockerBooking->customer->name : '',
                'customer_id' => $lockerBooking->customer_id,
                'msg'         => __('This locker already has a key assigned.')
            ]);
        }
        
        if(!empty($lockerBooking))
        {
            return response()->json([
                'status'      => true,
                'customer'    => !empty($lockerBooking->customer) ? $lockerBooking->customer->name : '',
                'customer_id' => $lockerBooking->customer_id,
                'start_date'  => $lockerBooking->start_date,
                'end_date'    => date('Y-m-d', strtotime($lockerBooking->start_date . ' ' . $durationMap[$lockerBooking->duration]) -1)
            ]);
        }
        return response()->json([
            'status'      => false,
            'customer'    => '',
            'customer_id' => '',
            'msg'         => __('This locker already has a key assigned.')
        ]);
    }
}
