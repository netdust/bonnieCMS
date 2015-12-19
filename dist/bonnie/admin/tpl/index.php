<!DOCTYPE html>
<html class="js" lang="en" >
<head>

    <!--

    Copyright (c) Stefan Vandermeulen | http://netdust.be/

    -->

    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="{{csrf_key}}" content="{{csrf_token}}">
    <meta charset="utf-8" />
    <title></title>
    <link rel="stylesheet" href="{{__to( 'admin/css/style.min.css')}}" />
    <script data-main="{{__to( 'admin/js/admin' )}}" src="{{__to('admin/js/lib/require.js')}}"></script>

</head>
<body>
<div class="container">

</div>
<div id="bootstrap" role="data-bootstrap" >
    <script>
        base='{{base}}';
        define('app-bootstrap', function(){
            return {
                settings:{{settings|raw};;},
                {
                    {
                        modules | raw
                    }
                }
            }
        })
    </script>
</div>
</body>
</html>
