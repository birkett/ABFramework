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

use \ABFramework\controllers\AdminBasePageController as AdminBasePageController;
use \ABFramework\models\AdminLoginPageModel as AdminLoginPageModel;

/**
 * Handles generating the login page.
 *
 * @category  AdminControllers
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class AdminLoginPageController extends AdminBasePageController
{


    /**
     * Build the login page.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminLoginPageModel();
    }//end __construct()


    /**
     * Handle GET requests - build the login page.
     *
     * @param string $output Unparsed template passed by reference.
     *
     * @return void
     */
    public function getHandler(&$output)
    {
        parent::getHandler($output);
    }//end getHandler()


    /**
     * Handle POST requests - login / logout user.
     *
     * @param string $output Unparsed template passed by reference.
     *
     * @return void
     */
    public function postHandler(&$output)
    {
        $mode = $this->model->getPostVar('mode', FILTER_SANITIZE_STRING);

        if ($mode === 'login') {
            $this->actionLogin();
            return;
        }

        if ($mode === 'logout') {
            $this->actionLogout();
        }
    }//end postHandler()


    /**
     * Handle the login request.
     *
     * @return void
     */
    private function actionLogin()
    {
        $user = $this->model->getPostVar('username', FILTER_SANITIZE_STRING);
        $pass = $this->model->getPostVar('password', FILTER_SANITIZE_STRING);

        if ($this->model->checkCredentials($user, $pass) === false) {
            $this->badRequest('Incorrect username or password.');
            return;
        }

        // Set up the session on successful login.
        $this->sessionManager->doLogin($user);
        $this->goodRequest('');
    }//end actionLogin()


    /**
     * Handle the logout request.
     *
     * @return void
     */
    private function actionLogout()
    {
        $this->sessionManager->doLogout();
        $this->resetRequest();
    }//end actionLogout()
}//end class
