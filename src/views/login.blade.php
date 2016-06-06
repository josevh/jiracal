@extends('jiracal::master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                @if(!is_null(config('jiracal.jira_url')))
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2>Jira Authentication</h2>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3>Login</h3>
                                    <hr>
                                    <form class="form" action="{{ url(config('jiracal.path') . '/auth') }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                            <label for="jira-username">Username</label>
                                            <input class="form-control" name="jira-username" type="text" value="" id="jira-username">
                                        </div>
                                        <div class="form-group">
                                            <label for="jira-password">Password</label>
                                            <input class="form-control" name="jira-password" type="password" value="" id="jira-password">
                                        </div>
                                        <input class="btn btn-default" type="submit" value="Submit">
                                    </form>
                                </div>
                                @if(!is_null(config('jiracal.guest_jira_username')))
                                    <div class="col-sm-6">
                                        <h3>Guest</h3>
                                        <hr>
                                        <form class="form" action="{{ url(config('jiracal.path').'/auth') }}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input name="guest" type="hidden" value="true">
                                            <input class="btn btn-default" type="submit" value="Continue">
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h2>Config Required</h2>
                        </div>
                        <div class="panel-body">
                            <p>
                                Config is missing JIRA url. Please check your <code>.env</code> file.
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@append
