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
 * @category  Models
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */

namespace ABFramework\models;

/**
 * Defines functionality used by all other models.
 *
 * All other models inherit from this, so we also store a database instance.
 * getBaseURL() will return the normalized base URL for the site, including the
 * request protocol ( HTTP/ HTTPS).
 *
 * @category  Models
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class BasePageModel
{

    /**
     * Store a database instance - used by child classes.
     *
     * @var object $databse
     */
    protected $database;


    /**
     * Get an input variable from SERVER.
     *
     * @param string $varname Name of the variable to get.
     * @param string $filters Any valid filter or sanitize value.
     *
     * @return mixed Value, NULL on var not found, false when filter failed.
     */
    public function getServerVar($varname, $filters)
    {
        $var = filter_input(INPUT_SERVER, $varname, $filters);

        // Work around a bug, FastCGI nukes INPUT_SERVER on some hosts.
        if ($var === null) {
            if (isset($_SERVER[$varname]) === true) {
                $var = $_SERVER[$varname];
            }
        }

        return $var;
    }//end getServerVar()


    /**
     * Get an input variable from GET.
     *
     * @param string $varname Name of the variable to get.
     * @param string $filters Any valid filter or sanitize value.
     *
     * @return mixed Value, NULL on var not found, false when filter failed.
     */
    public function getGetVar($varname, $filters)
    {
        $var = filter_input(INPUT_GET, $varname, $filters);
        return $var;
    }//end getGetVar()


    /**
     * Get an input variable from POST.
     *
     * @param string $varname Name of the variable to get.
     * @param string $filters Any valid filter or sanitize value.
     *
     * @return mixed Value, NULL on var not found, false when filter failed.
     */
    public function getPostVar($varname, $filters)
    {
        $var = filter_input(INPUT_POST, $varname, $filters);
        return $var;
    }//end getPostVar()


    /**
     * Get a file from the _FILES superglobal.
     *
     * @param string $filename Filename of the entry to return.
     *
     * @return array File data array, or null on not found.
     */
    public function getFileVar($filename)
    {
        if (isset($_FILES[$filename]) === true) {
            return $_FILES[$filename];
        }

        return null;
    }//end getFileVar()


    /**
     * Get the base URL for the site (Protocol+Domain+TrailingSlash).
     *
     * @return string URL
     */
    public function getBaseURL()
    {
        $serverProtocol = $this->getServerVar(
            'SERVER_PROTOCOL',
            FILTER_SANITIZE_STRING
        );
        $serverHost     = $this->getServerVar(
            'HTTP_HOST',
            FILTER_SANITIZE_STRING
        );

        $proto = 'http://';

        if (mb_stripos($serverProtocol, 'https') === true) {
            $proto = 'https://';
        }

        return $proto.$serverHost.'/';
    }//end getBaseURL()


    /**
     * Store a database instance, accessible from child classs.
     *
     * @return void
     */
    public function __construct()
    {
        $this->database = \ABFramework\classes\PDODatabaseMySQL::getInstance();
    }//end __construct()
}//end class
