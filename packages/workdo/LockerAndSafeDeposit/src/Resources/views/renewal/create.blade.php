{{ Form::open(['route' => 'locker-renewal.store', 'method' => 'post', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('booking_id', __('Booking Id'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('booking_id', $bookings , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Booking Id') , 'id' => 'booking_id']) }}
                @if (empty($bookings->count()))
                    <div class=" text-xs">
                        {{ __('Please add booking. ') }}<a
                            href="{{ route('locker-booking.index') }}"><b>{{ __('Add Booking') }}</b></a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('customer_id', $customers , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Customer') , 'id' =>'customer_id']) }}
                @if (empty($customers->count()))
                    <div class=" text-xs">
                        {{ __('Please add customer. ') }}<a
                            href="{{ route('locker-customer.index') }}"><b>{{ __('Add Customer') }}</b></a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('renewal_date', __('Renewal Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('renewal_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Renewal Date') , 'required' => 'required' , 'id' => 'renewal_date','min' => date('Y-m-d')]) }}
            </div>
        </div>

        <input type="hidden" value="0" id="locker_id">
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary" id="submit">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $('#booking_id').change(function(e) {
            var booking_id = $(this).val();

            $.ajax({
                url: "{{ route('get.lockercustomer') }}",
                type: 'POST',   
                data: {
                    booking_id : booking_id,
                    _token     : "{{ csrf_token() }}",
                },
                success: function(data) {
                    if(data.status == true)
                    {
                        $('#customer_id').val(data.customer).trigger('change');
                        $('#locker_id').val(data.locker);
                    }                    
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.error){
                        toastrs('Error', __('Something went wrong.'), 'error');
                    } 
                }
            });            
        });

        $('#renewal_date').change(function(e) {
            var renewal_date = $(this).val();
            var locker_id = $('#locker_id').val();     
            var booking_id = $('#booking_id').val();            

            $.ajax({
                url: "{{ route('get.lockerbooking') }}",
                type: 'POST',   
                data: {
                    date       : renewal_date,
                    locker_id: locker_id,
                    booking_id: booking_id,
                    _token   : "{{ csrf_token() }}",
                },
                success: function(data) {
                    if(data.status == 'false')
                    {
                        toastrs('Error', data.message, 'error');
                        $('#submit').prop("disabled", true);
                    }  
                    else
                    {
                        $('#submit').prop("disabled", false);
                    }                  
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