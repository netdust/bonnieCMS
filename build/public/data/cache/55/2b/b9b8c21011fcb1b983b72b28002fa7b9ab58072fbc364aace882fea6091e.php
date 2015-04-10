<?php

/* Authentication/Forgotpassword.php */
class __TwigTemplate_552bb9b8c21011fcb1b983b72b28002fa7b9ab58072fbc364aace882fea6091e extends Twig_Template
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
    <div class=\"row\">
        <div class=\"columns small-6 small-centered\">
\t\t\t\t<div class=\"login-box\">
\t\t\t\t\t<h2>Forgot Password</h2>
                    ";
        // line 25
        if ( !twig_test_empty((isset($context["error"]) ? $context["error"] : null))) {
            // line 26
            echo "                    \t<p class=\"error\">";
            echo (isset($context["error"]) ? $context["error"] : null);
            echo "</p>
                    ";
        } else {
            // line 28
            echo "                    \t<p>Type in your email below</p>
                    ";
        }
        // line 30
        echo "\t\t\t\t\t<form class=\"form-horizontal\" action=\"forgotpassword\" method=\"POST\">
                        <input type=\"hidden\" name=\"";
        // line 31
        echo twig_escape_filter($this->env, (isset($context["csrf_key"]) ? $context["csrf_key"] : null), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, (isset($context["csrf_token"]) ? $context["csrf_token"] : null), "html", null, true);
        echo "\">
\t\t\t\t\t\t<fieldset>
\t\t\t\t\t\t\t<input class=\"input-large col-xs-12\" name=\"email\" id=\"email\" type=\"text\" placeholder=\"type email\" maxlength=\"50\"/>

\t\t\t\t\t\t\t<button type=\"submit\" class=\"button\">Submit</button>
\t\t\t\t\t\t</fieldset>\t
\t\t\t\t\t</form>
\t\t\t\t\t<hr>
\t\t\t\t\t<h3>Are you stuck?</h3>
\t\t\t\t\t<p>
\t\t\t\t\t\t<a href=\"#\">Not a problem, contact our support staff.</a>
\t\t\t\t\t</p>\t
\t\t\t\t</div>
\t\t</div><!--/row-->
\t</div><!--/row-->
</div><!--/container-->
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "Authentication/Forgotpassword.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 31,  62 => 30,  58 => 28,  52 => 26,  50 => 25,  33 => 13,  19 => 1,);
    }
}
