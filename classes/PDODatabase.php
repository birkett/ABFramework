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
 * Generic database class using PDO. This should be extended by a child class,
 * providing database specific connection details.
 *
 * This class should never be instantised.
 *
 * @category  Classes
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class PDODatabase
{

    /**
     * Store the current link to avoid reconnections.
     *
     * @var object $mLink
     */
    private $mLink;


    /**
     * Constructor - private and empty to ensure this class cannot be
     * instantised without a child class.
     *
     * @return void
     */
    private function __construct()
    {
    }//end __construct()


    /**
     * Destructor - close any open database connection.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->mLink = null;
    }//end __destruct()


    /**
     * Connect to the database.
     *
     * @param string $dsn  DSN string used to connect.
     * @param string $user Username.
     * @param string $pass Password.
     *
     * @return void
     */
    protected function connect($dsn, $user, $pass)
    {
        try {
            $this->mLink = new PDO(
                $dsn,
                $user,
                $pass,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (\PDOException $exception) {
            echo 'Database error: '.$exception->getMessage();
        }
    }//end connect()


    /**
     * Get MySQL server version info.
     *
     * @return string MySQL Server version
     */
    public function serverInfo()
    {
        if ($this->mLink === null) {
            return '';
        }

        $result = $this->mLink->getAttribute(PDO::ATTR_SERVER_VERSION);

        return $result;
    }//end serverInfo()


    /**
     * Run a query.
     *
     * @param string $query  Query string to run.
     * @param array  $params Array of parameters to bind.
     *
     * @return array Array of results
     */
    public function runQuery($query, array $params)
    {
        try {
            if ($this->mLink === null) {
                return array();
            }

            $statement = $this->mLink->prepare($query);
            $statement->execute($params);
            if ($statement->columnCount() !== 0) {
                $rows = $statement->fetchAll(PDO::FETCH_OBJ);

                return $rows;
            }
        } catch (\PDOException $exception) {
            echo 'Query error: '.$exception->getMessage();
        }//end try

        return array();
    }//end runQuery()


    /**
     * Get single row from a result, until no results left.
     *
     * @param array $result Array of rows.
     *
     * @return array One row array or null when none left
     */
    public function getRow(array &$result)
    {
        if ($result === null) {
            return null;
        }

        if ($this->getNumRows($result) !== 0) {
            $row = array_shift($result);

            return $row;
        }

        return null;
    }//end getRow()


    /**
     * Get number of rows.
     *
     * @param array $result Array of results.
     *
     * @return int Number of rows in result
     */
    public function getNumRows(array $result)
    {
        if ($result === null) {
            return 0;
        }

        $count = count($result);

        return $count;
    }//end getNumRows()


    /**
     * Get the last inserted row ID.
     *
     * @return string Row ID
     */
    public function lastInsertedID()
    {
        if ($this->mLink === null) {
            return null;
        }

        $lastid = $this->mLink->lastInsertId();

        return $lastid;
    }//end lastInsertedID()
}//end class
