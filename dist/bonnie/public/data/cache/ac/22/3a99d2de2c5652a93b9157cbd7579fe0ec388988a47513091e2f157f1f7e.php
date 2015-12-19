<?php

/* base.html */
class __TwigTemplate_ac223a99d2de2c5652a93b9157cbd7579fe0ec388988a47513091e2f157f1f7e extends Twig_Template
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
<!--[if IE 8]><html class=\"no-js lt-ie9\" lang=\"en\"> <![endif]-->
<!--[if gt IE 8]><!--><html class=\"no-js\" lang=\"en\"> <!--<![endif]-->

<head>
    <!--    Stefan Vandermeulen | http://netdust.be/    -->
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, minimum-scale=1,  user-scalable=no\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta charset=\"utf-8\"/>

    <title>";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "title", array(), "method"), "html", null, true);
        echo "</title>
    <meta name=\"description\" content=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "seo_description", array(0 => 1), "method"), "html", null, true);
        echo "\"/>
    <meta name=\"keywords\" content=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "keywords", array(0 => 1), "method"), "html", null, true);
        echo "\"/>


    <link rel=\"stylesheet\" href=\"";
        // line 16
        echo $this->env->getExtension('api')->util_handler("csspath", "main.css");
        echo "\">

    <!--[if lt IE 9]>
        <script type=\"text/javascript\" src=\"//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js\"></script>
        <script type=\"text/javascript\" src=\"";
        // line 20
        echo $this->env->getExtension('api')->util_handler("jspath", "plugins/matchMedia.js");
        echo "\"";
        // line 27
        echo twig_escape_filter($this->env, (isset($context["body_classes"]) ? $context["body_classes"] : null), "html", null, true);
        echo "\">



";
        // line 31
        $this->displayBlock('content', $context, $blocks);
        // line 33
        echo "



</body>
</html>";
    }

    // line 31
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "base.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 31,  72 => 33,  70 => 31,  63 => 27,  53 => 20,  46 => 16,  40 => 13,  36 => 12,  32 => 11,  20 => 1,);
    }
}
