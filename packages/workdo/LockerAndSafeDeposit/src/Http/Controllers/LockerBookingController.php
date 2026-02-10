<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LockerBookingDataTable;
use Workdo\LockerAndSafeDeposit\Entities\Locker;
use Workdo\LockerAndSafeDeposit\Entities\LockerBooking;
use Workdo\LockerAndSafeDeposit\Entities\LockerBookingPayment;
use Workdo\LockerAndSafeDeposit\Entities\LockerCustomer;
use Workdo\LockerAndSafeDeposit\Entities\LockerRenewal;
use Workdo\LockerAndSafeDeposit\Events\CreateLockerBooking;
use Workdo\LockerAndSafeDeposit\Events\CreateLockerBookingPayment;
use Workdo\LockerAndSafeDeposit\Events\DestroyLockerBooking;
use Workdo\LockerAndSafeDeposit\Events\UpdateLockerBooking;

class LockerBookingController extends Controller
{
        /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LockerBookingDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('locker_booking manage'))
        {
            return $dataTable->render('locker-and-safe-deposit::booking.index');
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
        if(Auth::user()->isAbleTo('locker_booking create'))
        {
            $lockers   = Locker::where('status','Available')->where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get();
            $customers = LockerCustomer::where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get()->pluck('name','id');

            return view('locker-and-safe-deposit::booking.create' , compact('lockers' , 'customers'));
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
        if(Auth::user()->isAbleTo('locker_booking create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'locker_id'   => 'required',
                                   'customer_id' => 'required',
                                   'start_date'  => 'required',
                                   'amount'      => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker-booking.index')->with('error', $messages->first());
            }

            $lockerBooking              = new LockerBooking();
            $lockerBooking->booking_id  = $this->bookingNumber();
            $lockerBooking->locker_id   = $request->locker_id;
            $lockerBooking->customer_id = $request->customer_id;
            $lockerBooking->start_date  = $request->start_date;
            $lockerBooking->duration    = $request->duration;
            $lockerBooking->amount      = $request->amount;
            $lockerBooking->workspace   = getActiveWorkSpace();
            $lockerBooking->created_by  = creatorId();
            $lockerBooking->save();

            event(new CreateLockerBooking($request , $lockerBooking));
            
            return redirect()->back()->with('success', __('The booking has been created successfully.'));
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
        if (Auth::user()->isAbleTo('locker_booking show'))
        {
            $lockerBooking = LockerBooking::find($id);

            return view('locker-and-safe-deposit::booking.show', compact('lockerBooking'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

        /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('locker_booking edit'))
        {
            $lockerBooking = LockerBooking::find($id);
            $lockers       = Locker::where('status','Available')->where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get();
            $customers     = LockerCustomer::where('workspace' , getActiveWorkSpace())->where('created_by' , creatorId())->get()->pluck('name','id');

            return view('locker-and-safe-deposit::booking.edit', compact('lockerBooking' , 'lockers' , 'customers'));
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
        if (Auth::user()->isAbleTo('locker_booking edit'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'locker_id'   => 'required',
                                   'customer_id' => 'required',
                                   'start_date'  => 'required',
                                   'amount'      => 'required',
                               ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $lockerBooking              = LockerBooking::find($id);
            $lockerBooking->locker_id   = $request->locker_id;
            $lockerBooking->customer_id = $request->customer_id;
            $lockerBooking->start_date  = $request->start_date;
            $lockerBooking->duration    = $request->duration;
            $lockerBooking->amount      = $request->amount;
            $lockerBooking->save();

            event(new UpdateLockerBooking($request , $lockerBooking));

            return redirect()->back()->with('success', __('The booking details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('locker_booking delete'))
        {
            $lockerBooking = LockerBooking::find($id);
            if (!empty($lockerBooking)) {
                LockerBookingPayment::where('booking_id', $id)->delete();
                LockerRenewal::where('booking_id', $id)->delete();
                $lockerBooking->delete();
            }
              event(new DestroyLockerBooking($lockerBooking));

            return redirect()->route('locker-booking.index')->with('success', __('The booking has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function payment($id)
    {
        if (Auth::user()->isAbleTo('locker_booking_payment create'))
        {
            $lockerBooking = LockerBooking::where('id', $id)->first();
            $payments      = LockerBookingPayment::where('booking_id', $id)->get();

            return view('locker-and-safe-deposit::booking.payment', compact('lockerBooking' , 'payments'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function addPayment(Request $request , $id)
    {
        if (Auth::user()->isAbleTo('locker_booking_payment create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'date'   => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bookingPayment              = new LockerBookingPayment();
            $bookingPayment->booking_id  = $id;
            $bookingPayment->date        = $request->date;
            $bookingPayment->amount      = $request->amount;
            $bookingPayment->description = $request->description;

            if (!empty($request->add_receipt)) {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $uplaod   = upload_file($request, 'add_receipt', $fileName, 'locker_booking_payment');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
                $bookingPayment->receipt = $url;
            }
            $bookingPayment->save();

            event(new CreateLockerBookingPayment($request, $bookingPayment));

            return redirect()->back()->with('success', __('The Payment successfully added.'));
        }
    }
    
    public function description($id)
    {
        $bookingPayment = LockerBookingPayment::find($id);
        return view('locker-and-safe-deposit::booking.description', compact('bookingPayment'));
    }

    public function getBooking(Request $request)
    {
        $bookings = LockerBooking::where('locker_id',$request->locker_id)->get();

        if (!$bookings) {
            return response()->json([
                'status'  => 'true',
                'message' => __('This locker is available.')
            ]);
        }
        
        $durationMap = [
            'monthly' => '+1 month',
            'yearly'  => '+1 year'
        ];
    
        foreach ($bookings as $booking) {
            if (isset($durationMap[$booking->duration])) {
                $newDate = date('Y-m-d', strtotime($booking->start_date . ' ' . $durationMap[$booking->duration]));
                $newReqDate = date('Y-m-d', strtotime($request->date . ' ' . $durationMap[$booking->duration]));
                $start = max($request->date, $booking->start_date);
                $end   = min($newReqDate, $newDate);

                if ($request->date >= $booking->start_date && $request->date < $newDate || ($start < $end)) {
                    return response()->json([
                        'status'  => 'false',
                        'message' => __('The selected locker is already booked, please select another.')
                    ]);
                }
            }
        }

        return response()->json([
            'status'  => 'true',
            'message' => __('This locker is available.')
        ]);
    }

    public static function bookingNumber()
    {
        $latest = LockerBooking::where('workspace' , getActiveWorkSpace())->where('created_by', creatorId())->latest()->first();
        if ($latest == null) {
            return 1;
        } else {
            return $latest->booking_id + 1;
        }
    }
}
