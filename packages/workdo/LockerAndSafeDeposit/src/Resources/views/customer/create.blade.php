{{ Form::open(['route' => 'locker-customer.store', 'method' => 'post', 'class'=>'needs-validation','novalidate' , 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email')]) }}
            </div>
        </div>
        <x-mobile name="contact_no" placeholder="{{__('Enter Contact Number')}}" label="{{__('Contact Number')}}"></x-mobile>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                {{ Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => __('Enter Address') , 'rows' => 3]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('id_proof', __('Id Proof'), ['class' => 'form-label']) }}
                <div class="choose-file form-group">
                    <label for="image" class="form-label">
                        <input type="file" name="id_proof[]" id="image" class="form-control" multiple>
                    </label>
                    <p class="upload_file"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
