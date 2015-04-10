<?php

namespace api\Extensions;

class Twig_Extension_ShareHelpers extends \Twig_Extension
{
    /**
     * Twig extension name
     *
     * @return string Extension name
     */
    public function getName()
    {
        return 'share-extension';
    }



    /**
     * Twig function declarations
     *
     * @return array Twig instances
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('twitter', array($this, 'getTwitterLink'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('facebook', array($this, 'getFacebookLink'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('pinterest', array($this, 'getPinterestLink'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('tumblr', array($this, 'getTumblrLink'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('googleplus', array($this, 'getGooglePlusLink'),array('is_safe' => array('all')))
        );
    }



    /**
     * Appends onclick handler to the link to make it open a popup
     *
     * @param int $width  Pop-up width
     * @param int $height Pop-up height
     *
     * @return string HTML to append to the link
     */
    private function appendHandler($width, $height)
    {
        return '" onclick="window.open(this.href, \'\', \'directories=no,location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,width='.$width.',height='.$height.'\'); return false;';
    }



    /**
     * Crafts Twitter link
     *
     * @param string $url  URL to share
     * @param string $text Text to tweet
     *
     * @return string <a href="..."> content
     */
    public function getTwitterLink($url, $text = '')
    {
        return 'https://twitter.com/intent/tweet?url='.rawurlencode($url).'&amp;text='.rawurlencode($text).$this->appendHandler(572, 435);
    }



    /**
     * Crafts Facebook link
     *
     * @param string $url URL to share
     *
     * @return string <a href="..."> content
     */
    public function getFacebookLink($url, $title='title', $summary='text')
    {
        //https://www.facebook.com/sharer.php?s=100&p[title]={title}&p[summary]={text}&p[url]={url}&p[images][0]={image}
        return 'https://www.facebook.com/sharer.php?s=100&p[title]='.rawurlencode($title).'&p[summary]='.rawurlencode($summary).'&p[url]='.rawurlencode($url).$this->appendHandler(572, 350);
    }



    /**
     * Crafts Pinterest link
     *
     * @param string $url   URL to share
     * @param string $media Media URL
     *
     * @return string <a href="..."> content
     */
    public function getPinterestLink($url, $media)
    {
        return 'http://pinterest.com/pin/create/button/?url='.rawurlencode($url).'&amp;media='.rawurlencode($media).$this->appendHandler(750, 316);
    }



    /**
     * Crafts Tumblr link
     *
     * @param string $url         URL to share
     * @param string $title       Title to use
     * @param string $description Description to use
     *
     * @return string <a href="..."> content
     */
    public function getTumblrLink($url, $title = '', $description = '')
    {
        return 'http://www.tumblr.com/share/link?url='.rawurlencode($url).'&amp;name='.$title.'&amp;description='.$description.$this->appendHandler(640, 435);
    }



    /**
     * Crafts Google+ link
     *
     * @param string $url         URL to share
     * @param string $title       Title to use
     * @param string $description Description to use
     *
     * @return string <a href="..."> content
     */
    public function getGooglePlusLink($url, $title = '', $description = '')
    {
        return 'https://plus.google.com/share?url='.rawurlencode($url).$this->appendHandler(640, 360);
    }
}