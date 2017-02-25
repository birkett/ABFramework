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

use \ABFramework\models\AdminBasePageModel;
use \ABFramework\traits\PasswordTools;

/**
 * Handles data for the list pages controller.
 *
 * Simply fetches all page ID's and titles.
 *
 * @category  AdminModels
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class AdminPasswordPageModel extends AdminBasePageModel
{
    use PasswordTools;


    /**
     * Change a user password.
     *
     * @param array $vars Variables to use.
     *
     * @return boolean True on updated, False on error
     */
    public function changePassword(array $vars)
    {
        if (empty($vars) === true
            || isset($vars['user']) === false
            || isset($vars['currentpassword']) === false
            || isset($vars['newpassword']) === false
            || isset($vars['confirmpassword']) === false
        ) {
            return false;
        }

        // Passwords dont match.
        if ($vars['newpassword'] !== $vars['confirmpassword']) {
            return false;
        }

        // Current password is wrong.
        $res = $this->checkCredentials($vars['user'], $vars['currentpassword']);
        if ($res === false) {
            return false;
        }

        $hash = $this->hashPassword($vars['newpassword']);

        $this->database->runQuery(
            'UPDATE site_users
             SET    password = :hash
             WHERE  username = :user',
            array(
             ':hash' => $hash,
             ':user' => $vars['user'],
            )
        );

        return true;
    }//end changePassword()


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
            $return = $this->verifyPassword($password, $dbhash->password);
            return $return;
        }//end if

        return false;
    }//end checkCredentials()
}//end class
