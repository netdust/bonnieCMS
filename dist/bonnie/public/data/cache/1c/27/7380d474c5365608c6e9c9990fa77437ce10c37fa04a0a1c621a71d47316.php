<?php

/* home.html */
class __TwigTemplate_1c277380d474c5365608c6e9c9990fa77437ce10c37fa04a0a1c621a71d47316 extends Twig_Template
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
<div class=\"home\">

    TEST

    ";
        // line 6
        echo $this->env->getExtension('api')->page_handler("content");
        echo "

    ";
        // line 8
        echo $this->env->getExtension('api')->page_handler("file");
        echo "
    ";
        // line 9
        echo $this->env->getExtension('api')->page_handler("test_ok");
        echo "

</div>
";
    }

    public function getTemplateName()
    {
        return "home.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 9,  31 => 8,  26 => 6,  19 => 1,);
    }
}
