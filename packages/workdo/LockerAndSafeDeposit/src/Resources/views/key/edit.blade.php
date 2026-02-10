{{Form::model($lockerKey,array('route' => array('locker-key.update', $lockerKey->id), 'method' => 'PUT','class'=>'needs-validation','novalidate')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('locker_id', __('Locker Number'), ['class' => 'form-label']) }}<x-required></x-required>
                <select class="form-control" name="locker_id" id="locker_id" required>
                    <option value="">{{ __('Select Locker Number') }}</option>
                    @foreach ($lockers as $locker)
                        <option value="{{ $locker->id }}" {{ $lockerKey->locker_id == $locker->id ? 'selected' : ''}}>{{ '#LOC' . sprintf("%05d",$locker->locker_number) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('customer_id', $customers , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Customer')]) }}
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('issue_date', __('Issue Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('issue_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Issue Date') , 'required' => 'required' ,'min' => date('Y-m-d') , 'id' => 'issue_date']) }}
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('key_type', __('Key Type'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('key_type', null, ['class' => 'form-control', 'placeholder' => __('Enter Key Type') , 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        var locker_id = $('#locker_id').val();
        if(locker_id == '')
        {
            var locker_id = '{{ $lockerKey->locker_id }}';
            var locker = '{{ !empty($lockerKey->locker) ? "#LOC" . sprintf("%05d",$lockerKey->locker->locker_number) : "" }}';
            $('#locker_id').append('<option value="' + locker_id + '" selected>' + locker + '</option>');            
        }
        getcustomer(locker_id);

        $('#locker_id').change(function(e) {
            var locker_id = $(this).val();
            getcustomer(locker_id , true);
        });

        function getcustomer(locker_id , showToastr)
        {
            $.ajax({
                url: "{{ route('get.lockerkeycustomer') }}",
                type: 'POST',   
                data: {
                    locker_id : locker_id,
                    _token     : "{{ csrf_token() }}",
                },
                success: function(data) {
                    if(data.status == true)
                    {
                        $('#customer_id').empty();
                        $('#customer_id').append(
                            '<option value="">{{ __("Select Locker Number") }}</option>');
                        $('#customer_id').append('<option value="' + data.customer_id + '" selected>' + data.customer +
                            '</option>');

                        $('#issue_date').attr('min', data.start_date);
                        $('#issue_date').attr('max', data.end_date);
                    }
                    else {
                        $('#customer_id').empty();
                        $('#customer_id').append(
                        '<option value="">{{ __("Select Locker Number") }}</option>');
                        $('#customer_id').append('<option value="' + data.customer_id + '" selected>' + data.customer +
                        '</option>');
                        if (showToastr) { 
                            toastrs('Error', data.msg, 'error');
                        }
                    }
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.error){
                        toastrs('Error', __('Something went wrong.'), 'error');
                    } 
                }
            });    
        }
    });
</script>