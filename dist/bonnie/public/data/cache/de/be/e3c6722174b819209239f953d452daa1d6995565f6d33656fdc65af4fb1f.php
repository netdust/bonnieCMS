<?php

/* form.html */
class __TwigTemplate_debee3c6722174b819209239f953d452daa1d6995565f6d33656fdc65af4fb1f extends Twig_Template
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
        echo "

<div class=\"form\">

    ";
        // line 5
        echo $this->env->getExtension('api')->page_handler("title");
        echo "

    <form id=\"form_";
        // line 7
        echo $this->env->getExtension('api')->page_handler("id");
        echo "\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "path", array()), "html", null, true);
        echo "/forms\">
        <input type=\"hidden\" name=\"";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["csrf_key"]) ? $context["csrf_key"] : null), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, (isset($context["csrf_token"]) ? $context["csrf_token"] : null), "html", null, true);
        echo "\">
        <input type=\"hidden\" name=\"form\" value=\"";
        // line 9
        echo $this->env->getExtension('api')->page_handler("id");
        echo "\">
        ";
        // line 10
        echo $this->env->getExtension('api')->page_handler("content");
        echo "
    </form>

</div>
";
    }

    public function getTemplateName()
    {
        return "form.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 10,  42 => 9,  36 => 8,  30 => 7,  25 => 5,  19 => 1,);
    }
}
