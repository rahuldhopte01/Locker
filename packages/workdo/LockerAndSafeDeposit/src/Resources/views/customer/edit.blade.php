{{ Form::model($lockerCustomer, ['route' => ['locker-customer.update', $lockerCustomer->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('first_name', __('First Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter First Name')]) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('last_name', __('Last Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Last Name')]) }}
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email')]) }}
            </div>
        </div>
        <x-mobile name="phone" placeholder="{{ __('Enter Phone') }}" label="{{ __('Phone') }}"></x-mobile>
        <div class="col-lg-12">
            <div class="form-group">
                <div class="form-check form-switch">
                    {{ Form::checkbox('is_active', 1, $lockerCustomer->is_active ?? true, ['class' => 'form-check-input', 'id' => 'is_active']) }}
                    {{ Form::label('is_active', __('Is Active'), ['class' => 'form-check-label']) }}
                </div>
            </div>
        </div>
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
                    @if(isset($images) && is_array($images))
                        @foreach($images as $image)
                            <img class="mt-3" id="blah" src="{{ get_file(is_object($image) ? $image->image : ($image['image'] ?? '')) }}" alt="your image" width="50" height="50" />
                        @endforeach
                    @endif
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
