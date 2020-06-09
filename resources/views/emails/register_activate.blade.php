<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <div class="container center-block">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                Please enjoy using the {{ config('app.name', 'Laravel') }} App. 
                                <br/>
                                If you have any questions, please do not hesitate to send us an email @ webmaster@{{ str_replace(' ','',config('app.name', 'Laravel')) }}.org                                Thank You Mu Lambda
                                <br />
                                Thank You 
                                <br />
                                {{ config('app.name', 'Laravel') }}
                                <br />
                                <br />
                                <br />
                                <!--<a class="btn btn-primary" href="{{url('/')}}">Home</a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
