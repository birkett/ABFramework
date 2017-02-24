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
 * Handles data for the login controller.
 *
 * @category  AdminModels
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class AdminLoginPageModel extends AdminBasePageModel
{


    /**
     * Check if supplied credentials match the database (login function).
     *
     * @param string $username Input username.
     * @param string $password Input password.
     *
     * @return boolean True when verified, False otherwise
     */
    public function checkCredentials($username, $password)
    {
        if ($username === '' || $password === '') {
            return false;
        }

        $result = $this->database->runQuery(
            'SELECT password
             FROM   site_users
             WHERE  username = :username',
            array(':username' => $username)
        );

        if ($this->database->getNumRows($result) === 1) {
            $dbhash = $this->database->getRow($result);

            // Password_verify is PHP 5.5+, fall back on older versions.
            if (function_exists('password_verify') === true) {
                $result = password_verify($password, $dbhash->password);
                return $result;
            }

            $newhash = crypt($password, $dbhash->password);

            if ($newhash === $dbhash->password) {
                return true;
            }

            return false;
        }//end if

        return false;
    }//end checkCredentials()


    /**
     * Generate a new password hash using a random salt.
     *
     * @param string $password Plain text password.
     *
     * @return string Password hash
     */
    public function hashPassword($password)
    {
        $options = array('cost' => HASHING_COST);
        // Password_hash is PHP 5.5+, fall back when not available.
        if (function_exists('password_hash') === true) {
            $result = password_hash($password, PASSWORD_BCRYPT, $options);
            return $result;
        }

        $salt = base64_encode(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
        $salt = str_replace('+', '.', $salt);

        $result = crypt($password, '$2y$'.$options['cost'].'$'.$salt.'$');
        return $result;
    }//end hashPassword()
}//end class
