<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LocationDataTable;
use Workdo\LockerAndSafeDeposit\Entities\LockerLocation;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LocationDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('locker_location manage')) {
            return $dataTable->render('locker-and-safe-deposit::location.index');
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('locker_location create')) {
            return view('locker-and-safe-deposit::location.create');
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('locker_location create')) {
            $validator = Validator::make($request->all(), [
                'building' => 'required|string|max:255',
                'floor'    => 'nullable|string|max:255',
                'section'  => 'nullable|string|max:255',
                'address'  => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('locker-location.index')->with('error', $messages->first());
            }

            $location = new LockerLocation();
            $location->building   = $request->building;
            $location->floor     = $request->floor;
            $location->section   = $request->section;
            $location->address   = $request->address;
            $location->workspace = getActiveWorkSpace();
            $location->created_by = creatorId();
            $location->save();

            return redirect()->back()->with('success', __('Location has been created successfully.'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('locker-and-safe-deposit::location.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('locker_location edit')) {
            $location = LockerLocation::find($id);

            return view('locker-and-safe-deposit::location.edit', compact('location'));
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('locker_location edit')) {
            $validator = Validator::make($request->all(), [
                'building' => 'required|string|max:255',
                'floor'    => 'nullable|string|max:255',
                'section'  => 'nullable|string|max:255',
                'address'  => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $location = LockerLocation::find($id);
            $location->building = $request->building;
            $location->floor    = $request->floor;
            $location->section  = $request->section;
            $location->address  = $request->address;
            $location->save();

            return redirect()->back()->with('success', __('Location has been updated successfully.'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('locker_location delete')) {
            $location = LockerLocation::find($id);
            if (!empty($location)) {
                $location->delete();
            }

            return redirect()->route('locker-location.index')->with('success', __('Location has been deleted.'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }
}
