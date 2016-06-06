<!DOCTYPE html>
<html lang="en">
    <head>
        @include('jiracal::common._meta')
        @include('jiracal::common._css')
    </head>
    <body>
        @include('jiracal::common._nav')
        @include('jiracal::common._alerts')
        @section('content')

        @show

        @include('jiracal::common._js')
    </body>
</html>
