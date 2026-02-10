<?php

namespace Workdo\LockerAndSafeDeposit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\LockerAndSafeDeposit\DataTables\LockerDataTable;
use Workdo\LockerAndSafeDeposit\Entities\Locker;
use Workdo\LockerAndSafeDeposit\Entities\LockerLocation;
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
        if (Auth::user()->isAbleTo('locker manage')) {
            return $dataTable->render('locker-and-safe-deposit::locker.index');
        }
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('locker create')) {
            $status    = Locker::$status;
            $sizes     = Locker::$sizes;
            $locationsQuery = LockerLocation::query();
            if (function_exists('getActiveWorkSpace')) {
                $locationsQuery->where('workspace', getActiveWorkSpace());
            }
            $locations = $locationsQuery->get()->pluck('building', 'id')->prepend(__('Select Location'), '');
            $suggestedNumber = $this->suggestLockerNumber();

            return view('locker-and-safe-deposit::locker.create', compact('status', 'sizes', 'locations', 'suggestedNumber'));
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
        if (Auth::user()->isAbleTo('locker create')) {
            $validator = Validator::make($request->all(), [
                'locker_number' => 'required|string|max:20|unique:lockers,locker_number',
                'location_id'   => 'nullable|exists:locker_locations,id',
                'size'          => 'required|in:small,medium,large,extra_large',
                'status'        => 'required|in:active,inactive,reserved,maintenance',
                'monthly_rate'  => 'required|numeric|min:0',
                'is_available'  => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->route('locker.index')->with('error', $validator->getMessageBag()->first());
            }

            $locker = new Locker();
            $locker->locker_number = $request->locker_number;
            $locker->location_id   = $request->location_id ?: null;
            $locker->size          = $request->size;
            $locker->status       = $request->status;
            $locker->monthly_rate = $request->monthly_rate;
            $locker->is_available = $request->boolean('is_available', true);
            if (function_exists('getActiveWorkSpace')) {
                $locker->workspace = getActiveWorkSpace();
            }
            if (function_exists('creatorId')) {
                $locker->created_by = creatorId();
            }
            $locker->save();

            event(new CreateLocker($request, $locker));

            return redirect()->back()->with('success', __('The locker has been created successfully.'));
        }
        return redirect()->back()->with('error', __('Permission Denied.'));
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
        if (Auth::user()->isAbleTo('locker edit')) {
            $locker = Locker::find($id);
            if (!$locker) {
                return response()->json(['error' => __('Locker not found.')], 404);
            }
            $status    = Locker::$status;
            $sizes     = Locker::$sizes;
            $locationsQuery = LockerLocation::query();
            if (function_exists('getActiveWorkSpace')) {
                $locationsQuery->where('workspace', getActiveWorkSpace());
            }
            $locations = $locationsQuery->get()->pluck('building', 'id')->prepend(__('Select Location'), '');

            return view('locker-and-safe-deposit::locker.edit', compact('locker', 'status', 'sizes', 'locations'));
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
        if (Auth::user()->isAbleTo('locker edit')) {
            $locker = Locker::find($id);
            if (!$locker) {
                return redirect()->back()->with('error', __('Locker not found.'));
            }
            $validator = Validator::make($request->all(), [
                'locker_number' => 'required|string|max:20|unique:lockers,locker_number,' . (int) $id,
                'location_id'   => 'nullable|exists:locker_locations,id',
                'size'          => 'required|in:small,medium,large,extra_large',
                'status'        => 'required|in:active,inactive,reserved,maintenance',
                'monthly_rate'  => 'required|numeric|min:0',
                'is_available'  => 'nullable|boolean',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }

            $locker->locker_number = $request->locker_number;
            $locker->location_id  = $request->location_id ?: null;
            $locker->size         = $request->size;
            $locker->status       = $request->status;
            $locker->monthly_rate = $request->monthly_rate;
            $locker->is_available = $request->boolean('is_available', true);
            $locker->save();

            event(new UpdateLocker($request, $locker));

            return redirect()->back()->with('success', __('The locker details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('locker delete')) {
            $locker = Locker::find($id);
            if (!empty($locker)) {
                $locker->delete();
            }
            event(new DestroyLocker($locker ?? new Locker()));
            return redirect()->route('locker.index')->with('success', __('The locker has been deleted.'));
        }
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Suggest next locker number (unique identifier), e.g. LOC-00001.
     */
    protected function suggestLockerNumber(): string
    {
        $q = Locker::query();
        if (function_exists('getActiveWorkSpace')) {
            $q->where('workspace', getActiveWorkSpace());
        }
        $count = $q->count();
        return 'LOC-' . sprintf('%05d', $count + 1);
    }
}
