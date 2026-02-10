<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-bordered ">
            <tr role="row">
                <th>{{ __('Locker Number') }}</th>
                <td>{{ !empty($lockerBooking->locker) ? '#LOC' . $lockerBooking->locker->locker_number : '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Customer') }}</th>
                <td>{{ !empty($lockerBooking->customer) ? $lockerBooking->customer->name : '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Start Date') }}</th>
                <td>{{ company_date_formate($lockerBooking->start_date) }}</td>
            </tr>
            <tr>
                <th>{{ __('Duration') }}</th>
                <td>{{ !empty($lockerBooking->duration) ? ucwords($lockerBooking->duration) : '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Amount') }}</th>
                <td>{{ !empty($lockerBooking->amount) ? currency_format_with_sym($lockerBooking->amount) : '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Due Amount') }}</th>
                <td>{{ currency_format_with_sym($lockerBooking->getDue()) }}</td>
            </tr>
            <tr>
                <th>{{ __('Payment Status') }}</th>
                <td>
                    @if($lockerBooking->getDue() == 0)
                        <span class="badge fix_badges  bg-primary p-2 px-3">{{ __('Paid') }}</span>
                    @elseif($lockerBooking->getDue() == $lockerBooking->amount)
                        <span class="badge fix_badges  bg-danger p-2 px-3">{{ __('Unpaid') }}</span>
                    @else
                        <span class="badge fix_badges  bg-info p-2 px-3">{{ __('Partialy Paid') }}</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
