@if($lockerBooking->getDue())    
    {{ Form::open(array('route' => array('locker-booking-payment.store', $lockerBooking->id),'method'=>'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate')) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{Form::date('date',null,array('class'=>'form-control','required'=>'required','placeholder'=> __('Enter Date')))}}
                </div>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('amount',$lockerBooking->getDue(), array('class' => 'form-control','required'=>'required','step'=>'0.01','max' => $lockerBooking->getDue())) }}
                </div>
            </div>
            <div class="form-group  col-md-12">
                {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
                {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3, 'placeholder'=> __('Enter Description'))) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
                <div class="choose-files ">
                    <label for="add_receipt">
                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                        <input type="file" class="form-control file" name="add_receipt" id="add_receipt"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            data-filename="add_receipt">
                        <img id="blah" width="100" src="" />
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary" id="submit">
    </div>
    <div class="border-bottom"></div>
    {{ Form::close() }}
@endif

<div class="card">
    <div class="card-header">
        <h5>{{__('Payment Summary')}}</h5>        
    </div>
    <div class="card-body table-border-style">
        <div class="table-responsive">
            <table class="table mb-0 pc-dt-simple" id="invoice-receipt-summary">
                <thead>
                    <tr>
                        <th class="text-dark">{{ __('Date') }}</th>
                        <th class="text-dark">{{ __('Amount') }}</th>
                        <th class="text-dark">{{ __('Description') }}</th>
                        <th class="text-dark">{{ __('Receipt') }}</th>
                    </tr>
                </thead>
                @if (!empty($payments))
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ company_datetime_formate($payment->date) }}</td>
                            <td class="text-right">{{ currency_format_with_sym($payment->amount) }}</td>
                            <td>
                                @if (!empty($payment->description))
                                <a  class="action-item" data-ajax-popup-over="true" data-size="md" data-title="{{ __('Description') }}" data-url="{{route('locker-payment.description' , $payment->id)}}" data-toggle="tooltip"  title="{{ __('Description') }}">
                                    <i class="fa fa-comment"></i>
                                    </a>
                                @else
                                    --
                                @endif
                            </td>
                            <td>
                                @if (!empty($payment->receipt))
                                    <a href="{{ get_file($payment->receipt) }}" download="" class="btn btn-sm btn-primary btn-icon me-2" target="_blank" data-toggle="tooltip" title="{{__('Download')}}"><span class="btn-inner--icon"><i class="ti ti-download"></i></span></a>
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    @include('layouts.nodatafound')
                @endif
            </table>
        </div>
    </div>
</div>