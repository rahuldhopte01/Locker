{{ Form::open(['route' => 'locker-membership.store', 'method' => 'post', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('membership_type', __('Membership Type'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('membership_type', null, ['class' => 'form-control', 'placeholder' => __('Enter Membership Type') , 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('duration', __('Duration'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="d-flex gap-3"> 
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="duration" id="monthly_price_option"
                            value="monthly" required checked>
                        <label class="form-check-label" for="monthly_price_option">
                            {{__('Monthly')}}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="duration" id="yearly_price_option"
                            value="yearly" required>
                        <label class="form-check-label" for="yearly_price_option">
                            {{ __('Yearly') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('start_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Start Date') , 'required' => 'required' , 'id' => 'start_date','min' => date('Y-m-d')]) }}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('membership_fee', __('Membership Fee'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('membership_fee', null, ['class' => 'form-control', 'placeholder' => __('Enter Membership Fee') , 'required' => 'required'  , 'step' => '0.01']) }}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('locker_id', __('Locker Number'), ['class' => 'form-label']) }}<x-required></x-required>
                <select class="form-control" name="locker_id" id="locker_id" required>
                    <option value="">{{ __('Select Locker Number') }}</option>
                </select>
                @if (empty($lockers->count()))
                    <div class=" text-xs">
                        {{ __('Please add locker number. ') }}<a
                            href="{{ route('locker.index') }}"><b>{{ __('Add Locker') }}</b></a>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('customer_id', $customers , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Customer')]) }}
                @if (empty($customers->count()))
                    <div class=" text-xs">
                        {{ __('Please add customer. ') }}<a
                            href="{{ route('locker-customer.index') }}"><b>{{ __('Add Customer') }}</b></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $('#start_date , input[name="duration"]').change(function(e) {
            var date = $('#start_date').val();            
            var duration = $('input[name="duration"]:checked').val();
            $.ajax({
                url: "{{ route('get.locker') }}",
                type: 'POST',   
                data: {
                    date     : date,
                    duration : duration,
                    _token   : "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#locker_id').empty();
                    $('#locker_id').append(
                        '<option value="">{{ __("Select Locker Number") }}</option>');
                    $.each(data, function(key, value) {
                        $('#locker_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.error){
                        toastrs('Error', __('Something went wrong.'), 'error');
                    } 
                }
            });            
        });
    });
</script>