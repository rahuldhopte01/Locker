<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LockerRenewalDataTable;
use Workdo\LockerAndSafeDeposit\Entities\LockerBooking;
use Workdo\LockerAndSafeDeposit\Entities\LockerCustomer;
use Workdo\LockerAndSafeDeposit\Entities\LockerRenewal;
use Workdo\LockerAndSafeDeposit\Events\CreateLockerRenewal;
use Workdo\LockerAndSafeDeposit\Events\DestroyLockerRenewal;

class LockerRenewalController extends Controller
{
      /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LockerRenewalDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('locker_renewal manage'))
        {
            return $dataTable->render('locker-and-safe-deposit::renewal.index');
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
        if(Auth::user()->isAbleTo('locker_renewal create'))
        {
            $durationMap = [
                'monthly' => '+1 month',
                'yearly'  => '+1 year'
            ];

            $bookings = LockerBooking::where('workspace', getActiveWorkSpace())
            ->where('created_by', creatorId())
            ->get()
            ->filter(function ($booking) use ($durationMap) {
                       $newDate        = date('Y-m-d', strtotime($booking->start_date . ' ' . $durationMap[$booking->duration] ?? ''));
                return date('Y-m-d') >= $newDate;
            })
            ->pluck('booking_id', 'id')
            ->map(fn($value) => "#BOOK" . sprintf('%05d', $value));

            $customers = LockerCustomer::where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get()->pluck('name','id');

            return view('locker-and-safe-deposit::renewal.create' , compact('customers' , 'bookings'));
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
        if(Auth::user()->isAbleTo('locker_renewal create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'booking_id'   => 'required',
                                   'customer_id'  => 'required',
                                   'renewal_date' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker-renewal.index')->with('error', $messages->first());
            }

            $lockerRenewal               = new LockerRenewal();
            $lockerRenewal->booking_id   = $request->booking_id;
            $lockerRenewal->customer_id  = $request->customer_id;
            $lockerRenewal->renewal_date = $request->renewal_date;
            $lockerRenewal->workspace    = getActiveWorkSpace();
            $lockerRenewal->created_by   = creatorId();
            $lockerRenewal->save();

            $lockerBooking              = LockerBooking::find($lockerRenewal->booking_id);
            $oldAmount                  = $lockerBooking->amount;
            $lockerBooking->customer_id = $lockerRenewal->customer_id;
            $lockerBooking->start_date  = $lockerRenewal->renewal_date;
            $lockerBooking->amount      = $oldAmount + $lockerBooking->amount;
            $lockerBooking->save();

            event(new CreateLockerRenewal($request , $lockerRenewal));
            
            return redirect()->back()->with('success', __('The renewal has been created successfully.'));
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
        return view('locker-and-safe-deposit::edit');
    }

      /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
          //
    }

      /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('locker_renewal delete'))
        {
            $lockerRenewal = LockerRenewal::find($id);
            if (!empty($lockerRenewal)) {
                $lockerRenewal->delete();
            }
            event(new DestroyLockerRenewal($lockerRenewal));

            return redirect()->route('locker-renewal.index')->with('success', __('The renewal has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getCustomer(Request $request) 
    {
        $lockerBooking              = LockerBooking::find($request->booking_id);
        if(!empty($lockerBooking))
        {
            return response()->json([
                'status'   => true,
                'customer' => $lockerBooking->customer_id,
                'locker'   => $lockerBooking->locker_id
            ]);
        }
        return response()->json([
            'status'   => false,
        ]);
    }
}
