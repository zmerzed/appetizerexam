@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">No of passers in each schools</div>

                <div class="panel-body">
                    <a href="/" type="button" class="btn btn-success">
                        <i class="glyphicon glyphicon-arrow-left"></i> Back
                    </a>

                    <div>
                        @foreach($schools as $school)

                            <h5>{{ $school->name }} <strong style="color: blue">{{ $school->passers_count }}</strong></h5>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection