@extends('layouts.app')

@section('content')
    <div class="container">

        @error('contacts_csv_file')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
        @endif

        @isset($success_message)
            <div class="alert alert-success" role="alert">
                {{ $success_message }}
            </div>
        @endisset

        @isset($error_message)
            <div class="alert alert-danger" role="alert">
                {{ $error_message }}
            </div>
        @endisset

        {{ Form::open(['route' => 'contacts_upload_csv', 'files' => true]) }}

        <div class="d-flex gap-2 justify-content-center align-items-center mb-2">
            <label for="contacts_csv_file" class="form-label m-0">Upload Contacts CSV</label>
            <input id="contacts_csv_file" name="contacts_csv_file"
                class="form-control w-auto @error('contacts_csv_file') is-invalid @enderror" type="file" accept=".csv"
                required>
            <button class="btn btn-primary" type="submit">Upload</button>
        </div>

        {{ Form::close() }}

        @if (session()->has('rows_inserted'))
            @if (session()->get('rows_inserted') > 0)
                <div class="alert alert-success" role="alert">
                    {{ session()->get('rows_inserted') }} rows inserted into the database.
                </div>
            @endif
        @endif

        @if (session()->has('duplicates'))
            @if (sizeof(session()->get('duplicates')) > 0)
                <div class="alert alert-warning" role="alert">
                    Row {{ implode(', ', session()->get('duplicates')) }} already exist and skipped.
                </div>
            @endif
        @endif

        @if (session()->has('error_rows'))
            @if (sizeof(session()->get('error_rows')) > 0)
                <div class="alert alert-danger" role="alert">
                    Row {{ implode(', ', session()->get('error_rows')) }} contain some error.
                </div>
            @endif
        @endif


    </div>
@endsection
