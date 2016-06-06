@extends('jiracal::master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="row text-center">
                    <div class="col-xs-2">
                        <h3>
                            <a href="{{ action('\Josevh\JiraCal\JiraCalController@day', [$key, $pDate->year, str_pad($pDate->month, 2, '0', STR_PAD_LEFT), str_pad($pDate->day, 2, '0', STR_PAD_LEFT)]) }}">{{ str_pad($pDate->day, 2, '0', STR_PAD_LEFT) }}</a>
                        </h3>
                    </div>
                    <div class="col-xs-8">
                        <h2>{{ $cDate->format('F') }} {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}, {{ $year }}</h2>
                        <small>
                            <a href="{{ action('\Josevh\JiraCal\JiraCalController@month', [$key, $year, str_pad($month, 2, '0', STR_PAD_LEFT)]) }}">Back to month view</a>
                        </small>
                    </div>
                    <div class="col-xs-2">
                        <h3>
                            <a href="{{ action('\Josevh\JiraCal\JiraCalController@day', [$key, $nDate->year, str_pad($nDate->month, 2, '0', STR_PAD_LEFT), str_pad($nDate->day, 2, '0', STR_PAD_LEFT)]) }}">{{ str_pad($nDate->day, 2, '0', STR_PAD_LEFT) }}</a>
                        </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="list-group">
                            @if(isset($issues[(int)$day]))
                                @foreach($issues[(int)$day] as $date => $issue)
                                    <li class="list-group-item">
                                        <a href="{{ config('jiracal.jira_url') . 'browse/' . $issue->getKey() }}" title="{{ $issue->getSummary() }}" target="_blank">{{ $issue->getKey() }} - {{ $issue->getSummary() }}</a>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item">No issues found on this day.</li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
