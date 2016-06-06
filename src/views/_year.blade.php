@extends('jiracal::master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="row text-center">
                    <div class="col-xs-2">
                        <h3>
                            <a href="{{ action('\Josevh\JiraCal\JiraCalController@year', [$key, $year - 1]) }}">{{ $year - 1 }}</a>
                        </h3>
                    </div>
                    <div class="col-xs-8">
                        <h1>{{ $year }}</h1>
                        <small>
                            <a href="{{ action('\Josevh\JiraCal\JiraCalController@index') }}">Back to projects</a>
                        </small>
                    </div>
                    <div class="col-xs-2">
                        <h3>
                            <a href="{{ action('\Josevh\JiraCal\JiraCalController@year', [$key, $year + 1]) }}">{{ $year + 1 }}</a>
                        </h3>
                    </div>
                </div>
                <div class="row">
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
@endsection
