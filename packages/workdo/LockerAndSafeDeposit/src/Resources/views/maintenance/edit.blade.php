{{Form::model($lockerMaintenance,array('route' => array('locker-maintenance.update', $lockerMaintenance->id), 'method' => 'PUT','class'=>'needs-validation','novalidate')) }}
<div class="modal-body">
    <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::label('locker_id', __('Locker'), ['class' => 'form-label']) }}<x-required></x-required>
                    <select class="form-control select" required id="locker_id" name="locker_id">
                        <option value>{{__('Select Locker')}}</option>
                        @foreach($lockers as $key => $locker)
                            <option value="{{$key}}" {{ $key == $lockerMaintenance->locker_id ? 'selected' : ''}}>{{ '#LOC' . sprintf("%05d",$locker) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('technician_name', __('Technician Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('technician_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Technician Name')]) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('repair_status', __('Repair Status'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::select('repair_status', $status ,  null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Repair Status')]) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('reported_date', __('Reported Date'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::date('reported_date' ,  null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Reported Date')]) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('repair_date', __('Repair Date'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::date('repair_date',  null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Repair Date')]) }}
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description') , 'rows' => 3]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
