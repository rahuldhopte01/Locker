@extends('layouts.main')
@section('page-title')
    {{ __('Location') }}
@endsection
@section('page-breadcrumb')
    {{ __('Locations') }}, {{ __('Details') }}
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <p class="text-muted">{{ __('View location details. Use edit to modify.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
