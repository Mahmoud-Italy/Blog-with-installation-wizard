@extends('install.layout')

@section('content')
    <h2>1. Pre-Installation</h2>

    <div class="box">
        <p>Please make sure the PHP extensions listed below are installed.</p>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Extensions</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($requirement->extensions() as $label => $satisfied)
                        <tr>
                            <td>{{ $label }}</td>

                            <td class="text-center">
                                <i class="fa fa-{{ $satisfied ? 'check' : 'times' }}" aria-hidden="true"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="box">
        <p>Please make sure you have set the correct permissions for the directories listed below.</p>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Directories</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($requirement->directories() as $label => $satisfied)
                        <tr>
                            <td>{{ $label }}</td>

                            <td class="text-center">
                                <i class="fa fa-{{ $satisfied ? 'check' : 'times' }}" aria-hidden="true"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="content-buttons clearfix">
        <a href="{{ $requirement->satisfied() ? url('install/configuration') : '#' }}" class="btn btn-primary pull-right" {{ $requirement->satisfied() ? '' : 'disabled' }}>
            Continue
        </a>
    </div>
@endsection
