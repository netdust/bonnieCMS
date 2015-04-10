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
    <link rel="stylesheet" href="../admin/css/application.css" />
    <script data-main="../admin/js/admin" src="../admin/js/lib/require.js"></script>
</head>

<body>
    <div class="container">
        {% block content %}{% endblock %}
    </div><!--/container-->
</body>
</html>