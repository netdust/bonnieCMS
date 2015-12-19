<?php

/* index.php */
class __TwigTemplate_2b6ff7a837ba999fcdd9c693e97b17e9cddb1af4802204fbc061bdc65c1a87cb extends Twig_Template
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
    <link rel=\"stylesheet\" href=\"";
        // line 16
        echo $this->env->getExtension('api')->util_handler("to", "admin/css/style.min.css");
        echo "\" />
    <script data-main=\"";
        // line 17
        echo $this->env->getExtension('api')->util_handler("to", "admin/js/admin");
        echo "\" src=\"";
        echo $this->env->getExtension('api')->util_handler("to", "admin/js/lib/require.js");
        echo "\'';
        // line 26
        echo twig_escape_filter($this->env, (isset($context["base"]) ? $context["base"] : null), "html", null, true);
        echo "';
        define('app-bootstrap', function(){
            return {
                settings:";
        // line 29
        echo (isset($context["settings"]) ? $context["settings"] : null);
        echo ",
                modules:";
        // line 30
        echo (isset($context["modules"]) ? $context["modules"] : null);
        echo "
            }
        })
    </script>
</div>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "index.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 30,  65 => 29,  59 => 26,  45 => 17,  41 => 16,  33 => 13,  19 => 1,);
    }
}
