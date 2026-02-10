{{ Form::open(['route' => 'locker-location.store', 'method' => 'post', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('building', __('Building'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('building', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Building')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('floor', __('Floor'), ['class' => 'form-label']) }}
                {{ Form::text('floor', null, ['class' => 'form-control', 'placeholder' => __('Enter Floor')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('section', __('Section'), ['class' => 'form-label']) }}
                {{ Form::text('section', null, ['class' => 'form-control', 'placeholder' => __('Enter Section')]) }}
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                {{ Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => __('Enter Address'), 'rows' => 2]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
