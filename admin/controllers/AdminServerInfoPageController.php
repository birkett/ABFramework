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
use \ABFramework\models\AdminServerInfoPageModel as AdminServerInfoPageModel;

/**
 * Handles generating the server information page.
 *
 * The server information page contains the server software versions, and lists
 * the PHP module dependencies for this application.
 *
 * @category  AdminControllers
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class AdminServerInfoPageController extends AdminBasePageController
{


    /**
     * Build the Server Info page.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminServerInfoPageModel();
    }//end __construct()


    /**
     * Handle GET requests - build the Server Info page.
     *
     * @param string $output Unparsed template passed by reference.
     *
     * @return void
     */
    public function getHandler(&$output)
    {
        parent::getHandler($output);

        $serverData = $this->model->getServerInfo();

        $tags = array(
                 '{APACHEVERSION}'    => $serverData['version_apache'],
                 '{PHPVERSION}'       => $serverData['version_php'],
                 '{MYSQLVERSION}'     => $serverData['version_mysql'],
                 '{PDOMYSQLEXT}'      => $serverData['extension_pdo_mysql'],
                 '{PHPCURLEXT}'       => $serverData['extension_curl'],
                 '{PHPJSONEXT}'       => $serverData['extension_json'],
                 '{PHPDATEEXT}'       => $serverData['extension_date'],
                 '{PHPFILTEREXT}'     => $serverData['extension_filter'],
                 '{PHPHASHEXT}'       => $serverData['extension_hash'],
                 '{PHPSESSIONEXT}'    => $serverData['extension_session'],
                 '{PHPPCREEXT}'       => $serverData['extension_pcre'],
                 '{PHPMCRYPTEXT}'     => $serverData['extension_mcrypt'],
                 '{PHPMBSTRINGEXT}'   => $serverData['extension_mbstring'],
                 '{PASSWORDHASH}'     => $serverData['function_pass_hash'],
                 '{HTTPRESPONSECODE}' => $serverData['function_http_code'],
                );
        $this->templateEngine->parseTags($tags, $output);
    }//end getHandler()


    /**
     * Handle POST requests - do nothing.
     *
     * @param string $output Unparsed template passed by reference.
     *
     * @return void
     */
    public function postHandler(&$output)
    {
        // Fail silently if this ever recieves a POST request.
        $this->badRequest('');
    }//end postHandler()
}//end class
