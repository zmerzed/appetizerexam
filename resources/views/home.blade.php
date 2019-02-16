@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Examinees</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="create text-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-item">
                                <i class="glyphicon glyphicon-plus"></i> New
                            </button>
                            <a href="/schools" type="button" class="btn btn-success">
                                <i class="glyphicon glyphicon-arrow-right"></i> Go to schools with a number of passers
                            </a>
                            <br>
                            <br>
                        </div>

                        <create-examinee></create-examinee>
                        <view-examinees></view-examinees>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/examinee.js') }}"></script>
@endpush