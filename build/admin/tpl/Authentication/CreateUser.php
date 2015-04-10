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
    <link rel="stylesheet" href="../admin/css/application.min.css" />
    <script data-main="../admin/js/admin" src="../admin/js/lib/require.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="columns small-6 small-centered">
            <div id="infoMessage">{{ error|raw }}</div>

            <form action="createuser" method="POST">
                <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
            <p>
                First Name: <input type="text" name="first_name" id="first_name" value="{{ first_name }}" maxlength="45"/>*
                Last Name: <input type="text" name="last_name" id="last_name"  value="{{ last_name }}" maxlength="45"/>*
            </p>
            <p>
                Company: <input type="text" name="company" id="company" value="{{ company }}" maxlength="50"/>
            </p>
            <p>
                Phone: 999-999-9999<input type="text" name="phone" id="phone" value="{{ phone }}" maxlength="14"/>
            </p>
            <p>
                Email: <input type="text" name="email" id="email" value="{{ email }}" maxlength="50"/>*
            </p>
            <p>
                Password: <input type="password" name="password" id="password" maxlength="20"/>*
            </p>
            <p>
                Group:
                {% if groups is not empty %}
                    <select name="group" id="group">
                        {% for group in groups %}
                            <option value="{{ group.id }}">{{ group.name }}</option>
                        {% endfor %}
                    </select>
                {% endif %}
            </p>
            <p>
                <input type="submit" value="Create User" />
            </p>
            </form>
        </div><!--/row-->
    </div><!--/container-->
</div><!--/container-->
</body>
</html>