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
				<div class="login-box">
					<h2>Forgot Password</h2>
                    {% if error is not empty %}
                    	<p class="error">{{error|raw}}</p>
                    {% else %}
                    	<p>Type in your email below</p>
                    {% endif %}
					<form class="form-horizontal" action="forgotpassword" method="POST">
                        <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
						<fieldset>
							<input class="input-large col-xs-12" name="email" id="email" type="text" placeholder="type email" maxlength="50"/>

							<button type="submit" class="button">Submit</button>
						</fieldset>	
					</form>
					<hr>
					<h3>Are you stuck?</h3>
					<p>
						<a href="#">Not a problem, contact our support staff.</a>
					</p>	
				</div>
		</div><!--/row-->
	</div><!--/row-->
</div><!--/container-->
</body>
</html>