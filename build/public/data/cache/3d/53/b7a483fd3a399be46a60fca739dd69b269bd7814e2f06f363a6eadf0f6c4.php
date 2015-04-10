<?php

/* \Authentication\Login.php */
class __TwigTemplate_3d53b7a483fd3a399be46a60fca739dd69b269bd7814e2f06f363a6eadf0f6c4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html class=\"js\" lang=\"en\" >
<head>

    <!--

    Copyright (c) Stefan Vandermeulen | http://netdust.be/

    -->

    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, minimum-scale=1\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta name=\"";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["csrf_key"]) ? $context["csrf_key"] : null), "html", null, true);
        echo "\" content=\"";
        echo twig_escape_filter($this->env, (isset($context["csrf_token"]) ? $context["csrf_token"] : null), "html", null, true);
        echo "\">
    <meta charset=\"utf-8\" />
    <title></title>
    <link rel=\"stylesheet\" href=\"../admin/css/application.min.css\" />
    <script data-main=\"../admin/js/admin\" src=\"../admin/js/lib/require.js\"></script>
</head>
<body>
<div class=\"container\">
\t\t<div class=\"row\">
\t\t\t<div class=\"columns small-6 small-centered\">
\t\t\t\t<div class=\"login-box\">
\t\t\t\t\t<h2>Login to your account</h2>
                    ";
        // line 25
        if ( !twig_test_empty((isset($context["error"]) ? $context["error"] : null))) {
            // line 26
            echo "                    \t<p class=\"error\">";
            echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : null), "html", null, true);
            echo "</p>
                    ";
        }
        // line 28
        echo "\t\t\t\t\t<form class=\"form-horizontal\" action=\"login\" method=\"POST\">
                        <input type=\"hidden\" name=\"";
        // line 29
        echo twig_escape_filter($this->env, (isset($context["csrf_key"]) ? $context["csrf_key"] : null), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, (isset($context["csrf_token"]) ? $context["csrf_token"] : null), "html", null, true);
        echo "\">
\t\t\t\t\t\t<fieldset>
\t\t\t\t\t\t\t<input name=\"email\" id=\"email\" type=\"text\" placeholder=\"type email\" maxlength=\"50\" value=\"";
        // line 31
        echo twig_escape_filter($this->env, (isset($context["email_value"]) ? $context["email_value"] : null), "html", null, true);
        echo "\"  autofocus=\"true\"/>

\t\t\t\t\t\t\t<input name=\"password\" id=\"password\" type=\"password\" placeholder=\"type password\" maxlength=\"20\"/>

                            <div class=\"row\">
                                <div class=\"columns small-6\">
                                    <button type=\"submit\" class=\"button\">Login</button>
                                </div>
                                <div class=\"columns small-6\">
                                    <label class=\"remember\" for=\"remember\"><input type=\"checkbox\" id=\"remember\" />Remember me</label>
                                </div>
                            </div>
\t\t\t\t\t\t</fieldset>\t
\t\t\t\t\t</form>
                    <div class=\"row\">
                        <div class=\"columns small-6\">
                            <a href=\"forgotpassword\">Get a new password.</a>
                        </div>
                        <div class=\"columns small-6\">
                            <a href=\"createuser\">Create an account here</a>
                        </div>
                    </div>
\t\t\t\t</div>
\t\t\t</div><!--/row-->\t\t
\t\t</div><!--/row-->
</div><!--/container-->
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "\\Authentication\\Login.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 31,  61 => 29,  58 => 28,  52 => 26,  50 => 25,  33 => 13,  19 => 1,);
    }
}
