@extends('jiracal::master')

@section('content')
    <div class="container-fluid">
        <div class="row text-center">
            <div class="col-xs-2 small">
                <h3>
                    <a href="{{ action('\Josevh\JiraCal\JiraCalController@month', [$key, $cDate->copy()->subMonth()->format('Y'), $cDate->copy()->subMonth()->format('m')]) }}">{{ $cDate->copy()->subMonth()->format('F') }}</a>
                </h3>
            </div>
            <div class="col-xs-8">
                <h1>{{ $dateComponents['month'] }} {{ $dateComponents['year'] }} <small><a class="btn btn-primary btn-xs" target="_blank" href="{{ $createIssueLink }}" role="button"><strong>Create</strong></a></small></h1>
                <small>
                    <a href="{{ action('\Josevh\JiraCal\JiraCalController@year', [$key, $cDate->format('Y')]) }}">Back to year view</a>
                </small>
            </div>
            <div class="col-xs-2 small">
                <h3>
                    <a href="{{ action('\Josevh\JiraCal\JiraCalController@month', [$key, $cDate->copy()->addMonth()->format('Y'), $cDate->copy()->addMonth()->format('m')]) }}">{{ $cDate->copy()->addMonth()->format('F') }}</a>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">

                <table class="table table-responsive table-bordered table-condensed">
                    {{-- day of week labels --}}
                    <tr class="day-names bg-primary">
                        @foreach($daysOfWeek as $day)
                            <td>
                                <h3>{{ $day }}</h3>
                            </td>
                        @endforeach
                    </tr>

                    {{-- days in week before first of month  --}}
                    <tr class="week">
                        @for($monthOffset = $dateComponents['wday']; $monthOffset > 0 ; $monthOffset--)
                            <td class="day">
                                <h3>&nbsp;</h3>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                            </td>
                        @endfor

                        {{-- days of month  --}}
                        @for($day=1; $day <= $maxDays; $day++)
                            @if($dateComponents['wday'] % 7 == 0)
                                </tr>
                                <tr class="week">
                            @endif
                                <td class="col-sm-1 seven-cols day day-{{ $day }} {{ ($today->format('M') == $dateComponents['month'] && $today->year == $dateComponents['year'] && $today->day == $day) ? 'bg-success' : '' }}">
                                    <h3>
                                        <a href="{{ action('\Josevh\JiraCal\JiraCalController@day', [$key, $dateComponents['year'], str_pad($dateComponents['mon'], 2, '0', STR_PAD_LEFT), str_pad($day,  2, '0', STR_PAD_LEFT)]) }}">{{ $day }}</a>
                                    </h3>
                                    <hr>
                                    @if(isset($issues[$day]))
                                        @for($i=0; $i < 4; $i++)
                                            {{-- TODO: generate url by controller action @issue? --}}
                                            <p class="small issues">
                                                @if(isset($issues[$day][$i]) && $i < 3)
                                                    <a href="{{ config('jiracal.jira_url') . 'browse/' . $issues[$day][$i]->getKey() }}" title="{{ $issues[$day][$i]->getSummary() }}" target="_blank">
                                                        <strong>{{ $issues[$day][$i]->getKey() }}</strong>: {{ $issues[$day][$i]->getSummary()}}
                                                    </a>
                                                @elseif($i == 3 && count($issues[$day]) > 3)
                                                    <span class="pull-right"><a class="btn btn-primary btn-xs" href="{{ action('\Josevh\JiraCal\JiraCalController@day', [$key, $dateComponents['year'], str_pad($dateComponents['mon'], 2, '0', STR_PAD_LEFT), str_pad($day,  2, '0', STR_PAD_LEFT)]) }}">More</a></span>
                                                    <?php break; ?>
                                                @else($i == 1)
                                                    &nbsp;
                                                @endif
                                            </p>
                                        @endfor
                                    @else
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                    @endif
                                </td><!-- /.col.day -->
                            <?php $dateComponents['wday']++; ?>
                        @endfor
                </table>

            </div>
        </div>
@endsection
