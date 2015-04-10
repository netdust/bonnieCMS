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
					<h2>Login to your account</h2>
                    {% if error is not empty %}
                    	<p class="error">{{error}}</p>
                    {% endif %}
					<form class="form-horizontal" action="login" method="POST">
                        <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
						<fieldset>
							<input name="email" id="email" type="text" placeholder="type email" maxlength="50" value="{{email_value}}"  autofocus="true"/>

							<input name="password" id="password" type="password" placeholder="type password" maxlength="20"/>

                            <div class="row">
                                <div class="columns small-6">
                                    <button type="submit" class="button">Login</button>
                                </div>
                                <div class="columns small-6">
                                    <label class="remember" for="remember"><input type="checkbox" id="remember" />Remember me</label>
                                </div>
                            </div>
						</fieldset>	
					</form>
                    <div class="row">
                        <div class="columns small-6">
                            <a href="forgotpassword">Get a new password.</a>
                        </div>
                        <div class="columns small-6">
                            <a href="createuser">Create an account here</a>
                        </div>
                    </div>
				</div>
			</div><!--/row-->		
		</div><!--/row-->
</div><!--/container-->
</body>
</html>