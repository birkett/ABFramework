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

use PDO;

/**
 * Basic wrapper for working with a MySQL database via PDO.
 *
 * @category  Classes
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class PDODatabaseMySQL extends PDODatabase
{


    /**
     * Open a database handle.
     *
     * @return object Database handle
     */
    public static function getInstance()
    {
        static $database = null;
        if (isset($database) === false) {
            $database = new PDODatabaseMySQL();
        }

        return $database;
    }//end getInstance()


    /**
     * Constructor - set up the DSN and connect to the database.
     *
     * @return void
     */
    private function __construct()
    {
        $user = DATABASE_USERNAME;
        $pass = DATABASE_PASSWORD;
        $host = DATABASE_HOSTNAME;
        $name = DATABASE_NAME;
        $port = DATABASE_PORT;

        $dsn = 'mysql:host='.$host.';dbname='.$name.';port='.$port.';charset=utf8';

        $this->connect($dsn, $user, $pass);
    }//end __construct()
}//end class
