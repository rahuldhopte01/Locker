{{ Form::open(['route' => 'locker-booking.store', 'method' => 'post', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('start_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Start Date') , 'required' => 'required' , 'id' => 'start_date','min' => date('Y-m-d')]) }}
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('locker_id', __('Locker Number'), ['class' => 'form-label']) }}<x-required></x-required>
                <select class="form-control" name="locker_id" id="locker_id" required>
                    <option value="">{{ __('Select Locker Number') }}</option>
                    @foreach ($lockers as $locker)
                        <option value="{{ $locker->id }}"
                            data-monthly-amount="{{ $locker->monthly_rate }}"
                            data-yearly-amount="{{ $locker->yearly_rate ?? ($locker->monthly_rate * 12) }}">
                            {{ $locker->locker_number }}
                        </option>
                    @endforeach
                </select>
                @if (empty($lockers->count()))
                    <div class=" text-xs">
                        {{ __('Please add locker number. ') }}<a
                            href="{{ route('locker.index') }}"><b>{{ __('Add Locker') }}</b></a>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-12">
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
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('locker_type', __('Select Amount Type'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="monthly_amount_option"
                        value="monthly" required>
                    <label class="form-check-label" for="monthly_amount_option">
                        {{ __('Monthly Amount') }}: <span id="monthly_amount_label">0</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="yearly_amount_option"
                        value="yearly" required>
                    <label class="form-check-label" for="yearly_amount_option">
                        {{ __('Yearly Amount') }}: <span id="yearly_amount_label">0</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Amount') , 'required' => 'required' , 'readonly' , 'id' => 'amount']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary" id="submit">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $('#locker_id').change(function() {
            var selectedOption = $(this).find(':selected');
            var monthlyAmount = selectedOption.data('monthly-amount') || 0;
            var yearlyAmount = selectedOption.data('yearly-amount') || 0;

            $('#monthly_amount_label').text(monthlyAmount);
            $('#yearly_amount_label').text(yearlyAmount);

            $('input[name="duration"]').prop('checked', false);
            $('#amount').val(0);
        });

        $('input[name="duration"]').change(function() {
            var selectedOption = $('#locker_id').find(':selected');
            var monthlyAmount = selectedOption.data('monthly-amount') || 0;
            var yearlyAmount = selectedOption.data('yearly-amount') || 0;

            if ($(this).val() === 'monthly') {
                $('#amount').val(monthlyAmount);
            } else {
                $('#amount').val(yearlyAmount);
            }
        });

        $('#start_date , #locker_id').change(function(e) {
            var start_date = $('#start_date').val();
            var locker_id = $('#locker_id').val();            
            
            $.ajax({
                url: "{{ route('get.lockerbooking') }}",
                type: 'POST',   
                data: {
                    date     : start_date,
                    locker_id: locker_id,
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