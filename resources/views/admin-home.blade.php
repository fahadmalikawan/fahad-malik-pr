@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Home') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Welcome Admin!
                    <br />
                    <br />
                    <a class="btn btn-dark" target="_blank" href="{{ route('store_external_api_data') }}">Store External API Data</a>
                    <a class="btn btn-primary" target="_blank" href="{{ route('mail_active_users') }}">Dispatch Email to Active Users</a>
                    <br />
                    <br />
                    @if (session()->has('message'))
                    <p>{!! session()->get('message') !!}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
