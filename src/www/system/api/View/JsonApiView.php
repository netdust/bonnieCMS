<?php
/**
 * jsonAPI - Slim extension to implement fast JSON API's
 *
 * @package Slim
 * @subpackage View
 * @author Jonathan Tavares <the.entomb@gmail.com>
 * @license GNU General Public License, version 3
 * @filesource
 *
 *
 */


/**
 * JsonApiView - view wrapper for json responses (with error code).
 *
 * @package Slim
 * @subpackage View
 * @author Jonathan Tavares <the.entomb@gmail.com>
 * @license GNU General Public License, version 3
 * @filesource
 */

namespace api\View;

class JsonApiView extends \Slim\View {

    /**
     * Bitmask consisting of <b>JSON_HEX_QUOT</b>,
     * <b>JSON_HEX_TAG</b>,
     * <b>JSON_HEX_AMP</b>,
     * <b>JSON_HEX_APOS</b>,
     * <b>JSON_NUMERIC_CHECK</b>,
     * <b>JSON_PRETTY_PRINT</b>,
     * <b>JSON_UNESCAPED_SLASHES</b>,
     * <b>JSON_FORCE_OBJECT</b>,
     * <b>JSON_UNESCAPED_UNICODE</b>.
     * The behaviour of these constants is described on
     * the JSON constants page.
     * @var int
     */
    public $encodingOptions = 0;

    /**
     * Content-Type sent through the HTTP header.
     * Default is set to "application/json",
     * append ";charset=UTF-8" to force the charset
     * @var string
     */
    public $contentType = 'application/json';


    // clear data before adding our new set
    public function appendData($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Cannot append view data. Expected array argument.');
        }
        $this->data->clear();
        $this->data->replace($data);
    }

    protected function render($status=200, $data = NULL) {
        $app = \Slim\Slim::getInstance();

        $response = $this->all();

        $app->response()->status($status);
        $app->response()->header('Content-Type', $this->contentType);

        $app->response()->body(json_encode($response, $this->encodingOptions));

        $app->stop();
    }

}