<?php

/* Authentication/Base.php */
class __TwigTemplate_50f5eb6d0126d2406a1ff63d700ca4e2c44b0b1630279a331580591136c4f336 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
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
    <link rel=\"stylesheet\" href=\"../admin/css/application.css\" />
    <script data-main=\"../admin/js/admin\" src=\"../admin/js/lib/require.js\"></script>
</head>

<body>
    <div class=\"container\">
        ";
        // line 22
        $this->displayBlock('content', $context, $blocks);
        // line 23
        echo "    </div><!--/container-->
</body>
</html>";
    }

    // line 22
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "Authentication/Base.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 22,  50 => 23,  48 => 22,  34 => 13,  20 => 1,);
    }
}
