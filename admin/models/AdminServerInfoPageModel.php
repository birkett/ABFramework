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
 * @category  AdminModels
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */

namespace ABFramework\models;

use \ABFramework\models\AdminBasePageModel as AdminBasePageModel;

/**
 * Handles data for the ServerInfo controller.
 *
 * The extension dependencies are checked with a call to extension_loaded().
 *
 * @category  AdminModels
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class AdminServerInfoPageModel extends AdminBasePageModel
{


    /**
     * Grab some basic server info, like versions and PHP module support.
     *
     * @return array Server data
     */
    public function getServerInfo()
    {
        $serverSoftware = $this->getServerVar(
            'SERVER_SOFTWARE',
            FILTER_SANITIZE_STRING
        );

        $data = array(
                 'version_php'         => phpversion(),
                 'version_apache'      => $serverSoftware,
                 'version_mysql'       => $this->database->serverInfo(),

                 'extension_pdo_mysql' => extension_loaded('pdo_mysql'),
                 'extension_curl'      => extension_loaded('curl'),
                 'extension_json'      => extension_loaded('json'),
                 'extension_date'      => extension_loaded('date'),
                 'extension_filter'    => extension_loaded('filter'),
                 'extension_hash'      => extension_loaded('hash'),
                 'extension_session'   => extension_loaded('session'),
                 'extension_pcre'      => extension_loaded('pcre'),
                 'extension_mcrypt'    => extension_loaded('mcrypt'),
                 'extension_mbstring'  => extension_loaded('mbstring'),

                 'function_pass_hash'  => function_exists('password_hash'),
                 'function_http_code'  => function_exists('http_response_code'),
                );

        return $data;
    }//end getServerInfo()
}//end class
