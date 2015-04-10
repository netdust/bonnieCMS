<?php

/* Authentication/CreateUser.php */
class __TwigTemplate_b7637b7e8f8def1cfeb26897d936a2b326ee10ebce74861a591905cf8a3800c3 extends Twig_Template
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
            <div id=\"infoMessage\">";
        // line 23
        echo (isset($context["error"]) ? $context["error"] : null);
        echo "</div>

            <form action=\"createuser\" method=\"POST\">
                <input type=\"hidden\" name=\"";
        // line 26
        echo twig_escape_filter($this->env, (isset($context["csrf_key"]) ? $context["csrf_key"] : null), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, (isset($context["csrf_token"]) ? $context["csrf_token"] : null), "html", null, true);
        echo "\">
            <p>
                First Name: <input type=\"text\" name=\"first_name\" id=\"first_name\" value=\"";
        // line 28
        echo twig_escape_filter($this->env, (isset($context["first_name"]) ? $context["first_name"] : null), "html", null, true);
        echo "\" maxlength=\"45\"/>*
                Last Name: <input type=\"text\" name=\"last_name\" id=\"last_name\"  value=\"";
        // line 29
        echo twig_escape_filter($this->env, (isset($context["last_name"]) ? $context["last_name"] : null), "html", null, true);
        echo "\" maxlength=\"45\"/>*
            </p>
            <p>
                Company: <input type=\"text\" name=\"company\" id=\"company\" value=\"";
        // line 32
        echo twig_escape_filter($this->env, (isset($context["company"]) ? $context["company"] : null), "html", null, true);
        echo "\" maxlength=\"50\"/>
            </p>
            <p>
                Phone: 999-999-9999<input type=\"text\" name=\"phone\" id=\"phone\" value=\"";
        // line 35
        echo twig_escape_filter($this->env, (isset($context["phone"]) ? $context["phone"] : null), "html", null, true);
        echo "\" maxlength=\"14\"/>
            </p>
            <p>
                Email: <input type=\"text\" name=\"email\" id=\"email\" value=\"";
        // line 38
        echo twig_escape_filter($this->env, (isset($context["email"]) ? $context["email"] : null), "html", null, true);
        echo "\" maxlength=\"50\"/>*
            </p>
            <p>
                Password: <input type=\"password\" name=\"password\" id=\"password\" maxlength=\"20\"/>*
            </p>
            <p>
                Group:
                ";
        // line 45
        if ( !twig_test_empty((isset($context["groups"]) ? $context["groups"] : null))) {
            // line 46
            echo "                    <select name=\"group\" id=\"group\">
                        ";
            // line 47
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["groups"]) ? $context["groups"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["group"]) {
                // line 48
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["group"], "id", array()), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["group"], "name", array()), "html", null, true);
                echo "</option>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['group'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 50
            echo "                    </select>
                ";
        }
        // line 52
        echo "            </p>
            <p>
                <input type=\"submit\" value=\"Create User\" />
            </p>
            </form>
        </div><!--/row-->
    </div><!--/container-->
</div><!--/container-->
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "Authentication/CreateUser.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  117 => 52,  113 => 50,  102 => 48,  98 => 47,  95 => 46,  93 => 45,  83 => 38,  77 => 35,  71 => 32,  65 => 29,  61 => 28,  54 => 26,  48 => 23,  33 => 13,  19 => 1,);
    }
}
