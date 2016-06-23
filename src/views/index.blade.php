@extends('jiracal::master')

@section('content')
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1>Projects <small class="pull-right">@include('jiracal::common._auth')</small></h1>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @forelse($projectList as $key => $name)
                                    <li class="list-group-item">
                                        <a href="{{ '/' . config('jiracal.path') . '/' . $key . '/' . (new \DateTime())->format('Y') }}">{{ $name }}</a>
                                    </li>
                                @empty
                                    <li class="list-group-item">
                                        No projects found.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@append
