@permission('locker_booking_payment create')
<div class="action-btn  me-2">
    <a class="mx-3 btn bg-warning btn-sm  align-items-center" data-url="{{ route('locker-booking-payment.create', $booking->id) }}"
        data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
        data-title="{{ __('Create Payment') }}" data-bs-original-title="{{ __('Add Payment') }}">
        <i class="ti ti-caret-right text-white"></i>
    </a>
</div>
@endpermission

@permission('locker_booking edit')
    @if($booking->getDue() > 0)
        <div class="action-btn  me-2">
            <a class="mx-3 btn bg-info btn-sm  align-items-center" data-url="{{ route('locker-booking.edit', $booking->id) }}"
                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                data-title="{{ __('Edit Booking & Assignment') }}" data-bs-original-title="{{ __('Edit') }}">
                <i class="ti ti-pencil text-white"></i>
            </a>
        </div>
    @endif
@endpermission

@permission('locker_booking delete')
<div class="action-btn">
    {{ Form::open(['route' => ['locker-booking.destroy', $booking->id], 'class' => 'm-0']) }}
    @method('DELETE')
    <a class="mx-3 btn bg-danger btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
        title="" data-bs-original-title="Delete" aria-label="Delete"
        data-confirm="{{ __('Are You Sure?') }}"
        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
        data-confirm-yes="delete-form-{{ $booking->id }}"><i
            class="ti ti-trash text-white text-white"></i></a>
    {{ Form::close() }}
</div>
@endpermission