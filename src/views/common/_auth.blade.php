@if(session()->has('jira-auth') && session('jira-username') != env('DEF_JIRA_USERNAME'))
        <a class="btn btn-default btn-sm" href="{{ action('\Josevh\JiraCal\JiraCalController@logout') }}">Logout</a>
@endif
{{-- <a class="btn btn-default pull-right" href="{{ action('\Josevh\JiraCal\JiraCalController@login') }}">Login</a> --}}
