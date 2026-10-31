@extends('errors.main')

@section('container')
<div id="layoutError_content">
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center mt-4">
                        <h1 class="display-1">{{ $exception->getStatusCode() }}</h1>
                        <p class="lead">Page Expired</p>
                        <p>Access to this resource is denied.</p>
                        <a href="{{ route('login') }}">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection