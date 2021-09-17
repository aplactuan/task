@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Bin') }}</div>
                        <trash-tasks></trash-tasks>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
