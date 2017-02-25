<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Anthony Birkett
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 * PHP Version 5.3
 *
 * @category  Controllers
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */

namespace ABFramework\controllers;

/**
 * Handles the common tags which are present on all pages.
 *
 * Every page will be passed through this controller automatically, as all other
 * page controllers inherit from this, and call parent::__construct().
 *
 * Because everything else inherits this, we also store the page model and a
 * TemplateEngine instance here. These are protected, so are accessible from
 * child classes.
 *
 * @category  Controllers
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class BasePageController
{

    /**
     * Store an instance of the model for child controller to use.
     *
     * @var object $model
     */
    protected $model;

    /**
     * Store an instance of the template engine for child controllers to use.
     *
     * @var object $templateEngine
     */
    protected $templateEngine;

    /**
     * Array of post actions defined by the child controllers.
     *
     * @var array $postActions
     */
    protected $postActions;


    /**
     * Parse some common tags present in most (if not all) templates.
     *
     * @return none
     */
    public function __construct()
    {
        $this->model          = new \ABFramework\models\BasePageModel();
        $this->templateEngine = new \ABFramework\classes\TemplateEngine();
    }//end __construct()


    /**
     * Handle GET requests - build the base admin page.
     *
     * @param string $output Unparsed template passed by reference.
     *
     * @return void
     */
    public function getHandler(&$output)
    {
        $tags = array(
                 '{BASEURL}'  => $this->model->getBaseURL(),
                 '{THISYEAR}' => date('Y'),
                );
        $this->templateEngine->parseTags($tags, $output);

        // Remove the extra stylesheet tags if something above hanst used them.
        $this->templateEngine->removeLogicTag(
            '{EXTRASTYLESHEETS}',
            '{/EXTRASTYLESHEETS}',
            $output
        );

        if (defined('ADMINPAGE') === false) {
            $this->templateEngine->removeLogicTag(
                '{ADMINSTYLESHEET}',
                '{/ADMINSTYLESHEET}',
                $output
            );
            $this->templateEngine->replaceTag('{ADMINFOLDER}', '', $output);
        }
    }//end getHandler()


    /**
     * Handle POST requests - call any defined POST actions.
     *
     * @param string $output Unparsed template passed by reference.
     *
     * @return void
     */
    public function postHandler(&$output)
    {
        // This post handler does not use any output template.
        unset($output);

        $this->doPostAction();
    }//end postHandler()


    /**
     * Helper method for defining POST actions controllers can response to.
     *
     * Action functions are all of a similar form, this helper function will
     * spit out dynamically typed versions of the same function, reducing the
     * amount of work needed to define new actions.
     *
     * @param string $actionName    Name of the action to respond to.
     * @param string $modelFunction Function name in the model to call.
     * @param string $msgGood       Success message.
     * @param string $msgBad        Failiure message.
     * @param array  $vars          Variables to use.
     *
     * @return void
     */
    final protected function definePostAction($actionName, $modelFunction, $msgGood, $msgBad, array $vars)
    {
        $actionFunction = function () use ($modelFunction, $msgGood, $msgBad, $vars) {
            if ($this->model->$modelFunction($vars) === false) {
                $this->badRequest($msgBad);
                return;
            }

            $this->goodRequest($msgGood);
        };

        $actionEntry = array(
                        'function' => $actionFunction,
                        'msggood'  => $msgGood,
                        'msgbad'   => $msgBad,
                       );

        // Add this function to the known postActions array.
        $this->postActions[$actionName] = $actionEntry;
    }//end definePostAction()


    /**
     * Search the array of registered post actions and call one if found.
     *
     * @return void
     */
    final protected function doPostAction()
    {
        $mode = $this->model->getPostVar('mode', FILTER_SANITIZE_STRING);

        if (isset($this->postActions[$mode]) === true) {
            $this->postActions[$mode]['function']();
            return;
        }

        $this->badRequest('');
    }//end doPostAction()


    /**
     * Exit the script with a success HTTP code.
     *
     * @param string $message Message to echo.
     *
     * @return void
     */
    final protected function goodRequest($message)
    {
        http_response_code(200);
        echo $message;
    }//end goodRequest()


    /**
     * Exit the script with a reset content code, used for redirecting.
     *
     * @return void
     */
    final protected function resetRequest()
    {
        http_response_code(205);
    }//end resetRequest()


    /**
     * Exit the script and redirect the user.
     *
     * @return void
     */
    final protected function redirectRequest()
    {
        http_response_code(206);
    }//end redirectRequest()


    /**
     * Exit the script with a failed HTTP code.
     *
     * @param string $message Message to echo.
     *
     * @return void
     */
    final protected function badRequest($message)
    {
        http_response_code(400);
        echo $message;
    }//end badRequest()


    /**
     * Exit the script with a forbidden HTTP code.
     *
     * @return void
     */
    final protected function forbiddenRequest()
    {
        http_response_code(403);
    }//end forbiddenRequest()


    /**
     * Exit the script with a not found HTTP code.
     *
     * @return void
     */
    final protected function notFoundRequest()
    {
        http_response_code(400);
    }//end notFoundRequest()
}//end class
