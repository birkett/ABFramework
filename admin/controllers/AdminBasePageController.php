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
 * @category  AdminControllers
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */

namespace ABFramework\controllers;

/**
 * Handles all admin pages. All admin controllers inherit from this.
 *
 * The basic admin tags are parsed here, but a call is also made to
 * BasePageController::__construct() to parse out the public basic tags.
 *
 * @category  AdminControllers
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class AdminBasePageController extends BasePageController
{

    /**
     * Store an instance of the session manager for child controllers to use.
     *
     * @var object $sessionManager
     */
    protected $sessionManager;


    /**
     * Basic tags common to most (if not all) admin pages.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model          = new \ABFramework\models\AdminBasePageModel();
        $this->sessionManager = \ABFramework\classes\SessionManager::getInstance();
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
        parent::getHandler($output);
        $tags = array('{ADMINFOLDER}' => ADMIN_FOLDER);
        $this->templateEngine->parseTags($tags, $output);

        $tags = array(
                 '{ADMINSTYLESHEET}',
                 '{/ADMINSTYLESHEET}',
                );
        $this->templateEngine->removeTags($tags, $output);
    }//end getHandler()


    /**
     * Handle POST requests - let BasePageController handle any POST actions.
     *
     * @param string $output Unparsed template passed by reference.
     *
     * @return void
     */
    public function postHandler(&$output)
    {
        // BasePageController::postHandler will call any registered actions.
        parent::postHandler($output);
    }//end postHandler()
}//end class
