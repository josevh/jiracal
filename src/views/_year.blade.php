@extends('jiracal::master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="text-center">
                            <span class="pull-left small">
                                <a href="{{ action('\Josevh\JiraCal\JiraCalController@year', [$key, $year - 1]) }}">{{ $year - 1 }}</a>
                            </span>

                            {{ $year }}

                            <span class="pull-right small">
                                <a href="{{ action('\Josevh\JiraCal\JiraCalController@year', [$key, $year + 1]) }}">{{ $year + 1 }}</a>
                            </span>
                        </h1>

                        <p class="text-right" style="margin: 0;">
                            <a class="btn btn-default btn-sm" href="{{ action('\Josevh\JiraCal\JiraCalController@index') }}">Back to projects</a>
                            @include('jiracal::common._auth')
                        </p>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12">
                            <ul class="list-group">
                                @foreach($months as $num => $name)
                                    <li class="list-group-item">
                                        <a href="{{ action('\Josevh\JiraCal\JiraCalController@month', [$key, $year, $num]) }}">{{ $name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
