<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LockerMembershipDataTable;
use Workdo\LockerAndSafeDeposit\Entities\Locker;
use Workdo\LockerAndSafeDeposit\Entities\LockerBooking;
use Workdo\LockerAndSafeDeposit\Entities\LockerCustomer;
use Workdo\LockerAndSafeDeposit\Entities\LockerMembership;
use Workdo\LockerAndSafeDeposit\Events\CreateLockerMembership;
use Workdo\LockerAndSafeDeposit\Events\DestroyLockerMembership;
use Workdo\LockerAndSafeDeposit\Events\UpdateLockerMembership;

class LockerMembershipController extends Controller
{
        /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LockerMembershipDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('locker_membership manage'))
        {
            return $dataTable->render('locker-and-safe-deposit::membership.index');
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
        if(Auth::user()->isAbleTo('locker_membership create'))
        {
            $lockers   = Locker::where('status','Available')->where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get();
            $customers = LockerCustomer::where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get()->pluck('name','id');

            return view('locker-and-safe-deposit::membership.create' , compact('lockers' , 'customers'));
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
        if(Auth::user()->isAbleTo('locker_membership create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'locker_id'       => 'required',
                                   'customer_id'     => 'required',
                                   'start_date'      => 'required',
                                   'membership_type' => 'required',
                                   'duration'        => 'required',
                                   'membership_fee'  => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker-membership.index')->with('error', $messages->first());
            }

            $lockerMembership                  = new LockerMembership();
            $lockerMembership->locker_id       = $request->locker_id;
            $lockerMembership->customer_id     = $request->customer_id;
            $lockerMembership->start_date      = $request->start_date;
            $lockerMembership->membership_type = $request->membership_type;
            $lockerMembership->duration        = $request->duration;
            $lockerMembership->membership_fee  = $request->membership_fee;
            $lockerMembership->workspace       = getActiveWorkSpace();
            $lockerMembership->created_by      = creatorId();
            $lockerMembership->save();

            $bookingNumber = LockerBookingController::bookingNumber();

            $lockerBooking              = new LockerBooking();
            $lockerBooking->booking_id  = $bookingNumber;
            $lockerBooking->locker_id   = $request->locker_id;
            $lockerBooking->customer_id = $request->customer_id;
            $lockerBooking->start_date  = $request->start_date;
            $lockerBooking->duration    = $request->duration;
            $lockerBooking->amount      = $request->membership_fee;
            $lockerBooking->workspace   = getActiveWorkSpace();
            $lockerBooking->created_by  = creatorId();
            $lockerBooking->save();

            event(new CreateLockerMembership($request , $lockerMembership));
            
            return redirect()->back()->with('success', __('The membership has been created successfully.'));
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
        if (Auth::user()->isAbleTo('locker_membership edit'))
        {
            $lockerMembership = LockerMembership::find($id);
            $lockers          = Locker::where('status','Available')->where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get();
            $customers        = LockerCustomer::where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get()->pluck('name','id');

            return view('locker-and-safe-deposit::membership.edit', compact('lockerMembership' , 'lockers' , 'customers'));
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
        if (Auth::user()->isAbleTo('locker_membership edit'))
        {
            $validator = Validator::make(
                $request->all(), [
                    'locker_id'       => 'required',
                    'customer_id'     => 'required',
                    'start_date'      => 'required',
                    'membership_type' => 'required',
                    'duration'        => 'required',
                    'membership_fee'  => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $lockerMembership                  = LockerMembership::find($id);
            $lockerMembership->locker_id       = $request->locker_id;
            $lockerMembership->customer_id     = $request->customer_id;
            $lockerMembership->start_date      = $request->start_date;
            $lockerMembership->membership_type = $request->membership_type;
            $lockerMembership->duration        = $request->duration;
            $lockerMembership->membership_fee  = $request->membership_fee;
            $lockerMembership->save();

            $lockerBooking              = LockerBooking::where('locker_id' , $request->locker_id)->where('start_date' , $request->start_date)->first();
            $lockerBooking->locker_id   = $request->locker_id;
            $lockerBooking->customer_id = $request->customer_id;
            $lockerBooking->start_date  = $request->start_date;
            $lockerBooking->duration    = $request->duration;
            $lockerBooking->amount      = $request->membership_fee;
            $lockerBooking->save();

            event(new UpdateLockerMembership($request , $lockerMembership));

            return redirect()->back()->with('success', __('The membership details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('locker_membership delete'))
        {
            $lockerMembership = LockerMembership::find($id);
            if (!empty($lockerMembership)) {
                $lockerMembership->delete();
            }
            event(new DestroyLockerMembership($lockerMembership));

            return redirect()->route('locker-membership.index')->with('success', __('The membership has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getLocker(Request $request)
    {
        $bookings = LockerBooking::where('workspace',getActiveWorkSpace())->get();

        $durationMap = [
            'monthly' => '+1 month',
            'yearly'  => '+1 year'
        ];

        $data = [];
        foreach ($bookings as $booking) {
            if (isset($durationMap[$booking->duration])) {
                $newDate = date('Y-m-d', strtotime($booking->start_date . ' ' . $durationMap[$booking->duration]));
        
                $newReqDate = date('Y-m-d', strtotime($request->date . ' ' . $durationMap[$booking->duration]));
                $start      = max($request->date, $booking->start_date);
                $end        = min($newReqDate, $newDate);

                if ($request->date >= $booking->start_date && $request->date < $newDate || ($start < $end)) {
                    $data[] = $booking->locker_id;
                }
            }
        }
        
        $lockers = Locker::whereNotIn('id' , $data)->where('status' ,'Available')->get()->mapWithKeys(function ($locker) {
            return [$locker->id => '#LOC' . sprintf("%05d",$locker->locker_number)];
        });
        return response()->json($lockers);
    }
}
