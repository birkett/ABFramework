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
 * @category  Classes
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */

namespace ABFramework\classes;

/**
 * Serves GET requests, by generating a page to display.
 *
 * Essentially, this is just a wrapper for the Controller, makeing calls to load
 * the page template, and create a controller instance.
 *
 * @category  Classes
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class Page
{

    /**
     * Store and instance of the Controller Factory.
     *
     * @var object $mControllerFactory
     */
    private $mControllerFactory;


    /**
     * Generate a page.
     *
     * @param string $title      Page title.
     * @param string $template   Requested template.
     * @param string $controller Page controller to use.
     * @param string $namespace  Namespcae to load the controller from.
     *
     * @return void
     */
    public function __construct($title, $template, $controller, $namespace = null)
    {
        $this->mControllerFactory = new ControllerFactory();

        if ($namespace == null) {
            $namespace = '\ABFramework\controllers\\';
        }//end if

        // GET request.
        if ($this->isGetRequest() === true) {
            $page = '';

            if ($template !== null) {
                $page = $this->loadPageTemplate('page.tpl');

                $tags = array(
                         '{PAGE}'  => $this->loadSubTemplate($template.'.tpl'),
                         '{TITLE}' => SITE_TITLE.' :: '.$title,
                        );

                $page = str_replace(array_keys($tags), $tags, $page);
            }//end if

            $pagecontroller = $this->mControllerFactory->createController($controller, $namespace);

            $pagecontroller->getHandler($page);

            // Destroy controller objects.
            unset($pagecontroller);
            echo $page;
        }//end if

        // POST request.
        if ($this->isPostRequest() === true) {
            $page           = '';
            $pagecontroller = $this->mControllerFactory->createController($controller, $namespace);

            $pagecontroller->postHandler($page);

            // Destroy controller objects.
            unset($pagecontroller);
            echo $page;
        }//end if
    }//end __construct()


    /**
     * Open a page template, taking into account if the page is in admin.
     *
     * @param string $file Input template filename.
     *
     * @return string Template
     */
    private function loadPageTemplate($file)
    {
        $template = file_get_contents(__DIR__.'/../'.PRIVATE_FOLDER.'template/'.$file);

        return $template;
    }//end loadPageTemplate()


    /**
     * Open a sub template (widget, page content).
     *
     * @param string $file Input subtemplate filename.
     *
     * @return string SubTemplate
     */
    private function loadSubTemplate($file)
    {
        if (defined('ADMINPAGE') === true) {
            if (file_exists('../'.PRIVATE_FOLDER.'admin/template/'.$file)) {
                $template = file_get_contents('../'.PRIVATE_FOLDER.'admin/template/'.$file);
                return $template;
            }//end if
        }//end if

        if (file_exists(PRIVATE_FOLDER.'template/'.$file)) {
            $template = file_get_contents(PRIVATE_FOLDER.'template/'.$file);
            return $template;
        }//end if

        throw new \Exception('Template file '.$file.' not found.');
    }//end loadSubTemplate()


    /**
     * Check if a request method is POST.
     *
     * @return boolean TRUE if is a POST request, FALSE if GET.
     */
    private function isPostRequest()
    {
        $method = filter_input(
            INPUT_SERVER,
            'REQUEST_METHOD',
            FILTER_SANITIZE_STRING
        );

        if ($method === 'POST') {
            return true;
        }

        return false;
    }//end isPostRequest()


    /**
     * Check if a request method is GET.
     *
     * @return boolean TRUE if is a GET request, FALSE if POST.
     */
    private function isGetRequest()
    {
        $method = filter_input(
            INPUT_SERVER,
            'REQUEST_METHOD',
            FILTER_SANITIZE_STRING
        );

        if ($method === 'GET') {
            return true;
        }

        return false;
    }//end isGetRequest()
}//end class
