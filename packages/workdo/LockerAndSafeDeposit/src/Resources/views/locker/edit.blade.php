{{ Form::model($locker, ['route' => ['locker.update', $locker->id], 'method' => 'PUT', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('locker_number', __('Locker Number'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('locker_number', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('e.g. LOC-00001'), 'maxlength' => '20']) }}
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('location_id', __('Location'), ['class' => 'form-label']) }}
                {{ Form::select('location_id', $locations ?? [], null, ['class' => 'form-control', 'placeholder' => __('Select Location')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('size', __('Size'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('size', $sizes ?? [], null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Size')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('status', $status ?? [], null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Status')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('monthly_rate', __('Monthly Rate') . ' (EUR)', ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('monthly_rate', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('0.00'), 'step' => '0.01', 'min' => '0']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <div class="form-label">{{ __('Available') }}</div>
                <div class="form-check form-switch mt-2">
                    {{ Form::checkbox('is_available', 1, $locker->is_available ?? true, ['class' => 'form-check-input', 'id' => 'is_available']) }}
                    {{ Form::label('is_available', __('Is available for rental'), ['class' => 'form-check-label']) }}
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
