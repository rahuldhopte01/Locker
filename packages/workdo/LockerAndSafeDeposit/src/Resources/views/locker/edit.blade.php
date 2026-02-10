{{Form::model($locker,array('route' => array('locker.update', $locker->id), 'method' => 'PUT','class'=>'needs-validation','novalidate')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('locker_type', __('Locker Type'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('locker_type' , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Locker Type')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('locker_size', __('Locker Size'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('locker_size' , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Locker Size')]) }}
            </div>
        </div>        
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('max_capacity', __('Max Capacity'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('max_capacity' , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Max Capacity')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('price_of_month', __('Price Of Month'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('price_of_month' , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Price Of Month') , 'step' => '0.01']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('price_of_year', __('Price Of Year'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('price_of_year' , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Price Of Year') , 'step' => '0.01']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('status', $status , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Status')]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
