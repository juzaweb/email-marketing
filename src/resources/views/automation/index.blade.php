@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group float-right">
                <a href="{{ $createUrl }}" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> {{ __('core::translation.create') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            {{ $dataTable->table(['class' => 'table table-hover']) }}
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
