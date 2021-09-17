@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Tasks') }}</div>

                <div class="card-body">
                    <div class="w-1/2">
                        <a id="exportDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                           Export
                        </a>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="exportDropdown">
                           <ul>
                               <li class="bg-white shadow overflow-hidden rounded-md px-2 py-1">
                                   <a href="{{ route('export.csv') }}">Export as CSV</a>
                               </li>
                               <li class="bg-white shadow overflow-hidden rounded-md px-2 py-1">
                                   <a href="{{ route('export.excel') }}">Export as XLS</a>
                               </li>
                           </ul>
                        </div>
                    </div>
                   <tasks-component></tasks-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
