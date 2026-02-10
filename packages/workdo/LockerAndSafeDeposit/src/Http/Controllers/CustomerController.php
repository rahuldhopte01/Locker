<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\CustomerDataTable;
use Workdo\LockerAndSafeDeposit\Entities\LockerCustomer;
use Workdo\LockerAndSafeDeposit\Events\CreateLockerCustomer;
use Workdo\LockerAndSafeDeposit\Events\DestroyLockerCustomer;
use Workdo\LockerAndSafeDeposit\Events\UpdateLockerCustomer;

class CustomerController extends Controller
{
          /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CustomerDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('locker_customer manage'))
        {
            return $dataTable->render('locker-and-safe-deposit::customer.index');
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
        if(Auth::user()->isAbleTo('locker_customer create'))
        {
            return view('locker-and-safe-deposit::customer.create');
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
        if(Auth::user()->isAbleTo('locker_customer create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'name'  => 'required',
                                   'email' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker-customer.index')->with('error', $messages->first());
            }

            $data      = [];
            $latestKey = 0;

            if ($request->hasfile('id_proof')) {
                foreach ($request->file('id_proof') as $file) {
                    $file_name = time() . "_" . $file->getClientOriginalName();
                    $path      = multi_upload_file($file, 'id_proof', $file_name, 'customer_proof', []);

                    if ($path['flag'] == 0) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    $latestKey++;
                    $data[$latestKey] = ['image' => $path['url']];
                }
            }

            $lockerCustomer             = new LockerCustomer();
            $lockerCustomer->name       = $request->name;
            $lockerCustomer->email      = $request->email;
            $lockerCustomer->contact_no = $request->contact_no;
            $lockerCustomer->address    = $request->address;
            $lockerCustomer->id_proof   = json_encode($data) ;
            $lockerCustomer->workspace  = getActiveWorkSpace();
            $lockerCustomer->created_by = creatorId();
            $lockerCustomer->save();

            event(new CreateLockerCustomer($request , $lockerCustomer));
            
            return redirect()->back()->with('success', __('The customer has been created successfully.'));
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
        if (Auth::user()->isAbleTo('locker_customer edit'))
        {
            $lockerCustomer = LockerCustomer::find($id);
            $images         = json_decode($lockerCustomer->id_proof);

            return view('locker-and-safe-deposit::customer.edit', compact('lockerCustomer' , 'images'));
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
        if (Auth::user()->isAbleTo('locker_customer edit'))
        {
            $validator = Validator::make(
                    $request->all(), [
                        'name'  => 'required',
                        'email' => 'required',
                    ]
                );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $lockerCustomer = LockerCustomer::find($id);

            $data = json_decode($lockerCustomer->id_proof, true);
            if (!is_array($data)) {
                $data = [];
            }

            $latestKey = !empty($data) ? max(array_keys($data)) : 0;

            if ($request->hasfile('id_proof')) {
                foreach ($request->file('id_proof') as $file) {
                    $file_name = time() . "_" . $file->getClientOriginalName();
                    $path      = multi_upload_file($file, 'id_proof', $file_name, 'customer_proof', []);

                    if ($path['flag'] == 0) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    $latestKey++;
                    $data[$latestKey] = ['image' => $path['url']];
                }
            }
            $lockerCustomer->name       = $request->name;
            $lockerCustomer->email      = $request->email;
            $lockerCustomer->contact_no = $request->contact_no;
            $lockerCustomer->address    = $request->address;
            $lockerCustomer->id_proof   = json_encode($data) ;
            $lockerCustomer->save();

            event(new UpdateLockerCustomer($request , $lockerCustomer));

            return redirect()->back()->with('success', __('The customer details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('locker_customer delete'))
        {
            $lockerCustomer = LockerCustomer::find($id);
            if (!empty($lockerCustomer)) {
                $images        = json_decode($lockerCustomer->id_proof, true);
                foreach($images as $image)
                {
                    delete_file($image['image']);
                }
                $lockerCustomer->delete();
            }

            event(new DestroyLockerCustomer($lockerCustomer));

            return redirect()->route('locker-customer.index')->with('success', __('The customer has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
