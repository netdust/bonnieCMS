<?php

namespace api\Middleware;

class UrlLocalization extends \Slim\Middleware
{

    /**
     * An array of default values.
     *
     * @var array
     */
    protected $config = array(
        'i18n.default'=>'nl'
    );

    /**
     * An array of valid locales.
     *
     * @var array
     */
    protected $languages;


    /**
     * Constructor.
     *
     * @param array $locales
     * @param array $config
     */
    public function __construct(array $locales = null, $cnf=array())
    {
        $this->languages = $locales;
        $this->config = array_merge($this->config, $cnf);
    }

    /**
     * {@inheritDoc}
     */
    public function call()
    {

        /* set language from session or default */

        $_SESSION['i18n.localeId'] = !isset($_SESSION['i18n.localeId'])
            ?  array_search( $this->config['i18n.default'], $this->languages) + 1
            : $_SESSION['i18n.localeId'];

        $this->app->config('i18n.locale', $this->languages[$_SESSION['i18n.localeId']-1] );

        /**/

        $this->locales(__DATA__.'/locale/');

        $req = $this->app->request();
        $pathInfo = $req->getPathInfo();

        // URI prefix lookup.
        $uri = explode('/', trim($pathInfo, '/'));
        $locale = $uri[0];

        $isValidLocale = in_array($locale, $this->languages);

        if ( $isValidLocale ) {
            # a locale is set, update environment & config
            $env = $this->app->environment();
            $env['slim.localization.original_path'] = $pathInfo;

            $parts = array_filter($uri, function($v) use($locale) { return $v != $locale; });
            $env['PATH_INFO'] = '/'. join('/',$parts);

            $parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $parts = array_filter($parts, function($v) use($locale) { return $v != $locale; });
            $_SERVER['REQUEST_URI'] =  '/'. join('/', $parts);

            $_SESSION['i18n.locale'] = array_search( $locale, $this->languages) + 1;
            $this->app->config('i18n.locale', $locale);

            // Assign i18n to template
            $this->app->view()->appendData(array(
                'i18n' => $this->app->config('dict.'.$locale )
            ));
        }

        $this->next->call();
    }

    private function locales($val) {
        if ($lex=$this->lexicon($val)) {
            foreach ($lex as $key=>$val) {
                $this->set($key,$val);
            }
        }
    }

    /**
     *	Transfer lexicon entries to hive
     *	@return array
     *	@param $path string
     **/
    private function lexicon($path) {
        foreach ($this->languages as $lang) {
            if( is_file($file=($base=$path.$lang).'.php') && is_array($dict=require($file)) )
            {
                $this->app->config('dict.'.$lang, $dict );
            }
        }
    }

}