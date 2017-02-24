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
 * Wraps up the PHP session functions for handling user authentication.
 *
 * SessionManager handles the user login / logout, and validates the session.
 * A call to SessionManager::getInstance() should be one of the first calls in a
 * protected page. This will ensure that the isLoggedIn() function is available
 * immediatly after.
 *
 * doLogin() will assign the users name, User-Agent string and IP address to a
 * new session, preventing most session stealing.
 * isLoggedIn() checks this data matches what was expected.
 * An EXPIRES entry is also assigned, forcing a session to expire after a set
 * period of time (by default 1 hour, SESSION_EXPIRY_TIME).
 *
 * @category  Classes
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class SessionManager
{


    /**
     * Create or return a new instance of the SessionManager singleton.
     *
     * @return object SessionManager instance
     */
    public static function getInstance()
    {
        static $sessionmanager = null;
        if (isset($sessionmanager) === false) {
            $sessionmanager = new SessionManager();
        }//end if

        return $sessionmanager;
    }//end getInstance()


    /**
     * Start a session.
     *
     * @return void
     */
    private function __construct()
    {
        session_name('ABFrameworkAdmin');
        session_set_cookie_params(SESSION_EXPIRY_TIME, '/'.ADMIN_FOLDER);

        if (defined('RUNNING_PHPUNIT_TESTS') === false) {
            session_start();
        }//end if
    }//end __construct()


    /**
     * Destroy the open session.
     *
     * @return void
     */
    public function doLogout()
    {
        session_unset();
        session_destroy();
    }//end doLogout()


    /**
     * Set a username in the session.
     *
     * @param string $user Username to store.
     *
     * @return void
     */
    public function doLogin($user)
    {
        $cip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
        $cua = filter_input(
            INPUT_SERVER,
            'HTTP_USER_AGENT',
            FILTER_SANITIZE_STRING
        );

        // Annoying bug where INPUT_SERVER is stripped on some hosts.
        if ($cip === null || $cua === null) {
            $cip = $_SERVER['REMOTE_ADDR'];
            $cua = $_SERVER['HTTP_USER_AGENT'];
        }//end if

        $this->setVar('user', $user);
        $this->setVar('ip', $cip);
        $this->setVar('ua', $cua);
        $this->setVar('EXPIRES', (time() + SESSION_EXPIRY_TIME));

        $this->regenerateID();
    }//end doLogin()


    /**
     * Is a user logged in.
     *
     * @return boolean Is logged in
     */
    public function isLoggedIn()
    {
        $cip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
        $cua = filter_input(
            INPUT_SERVER,
            'HTTP_USER_AGENT',
            FILTER_SANITIZE_STRING
        );

        // Annoying bug where INPUT_SERVER is stripped on some hosts.
        if ($cip === null || $cua === null) {
            $cip = $_SERVER['REMOTE_ADDR'];
            $cua = $_SERVER['HTTP_USER_AGENT'];
        }//end if

        $sessionUser    = $this->getVar('user');
        $sessionIP      = $this->getVar('ip');
        $sessionUA      = $this->getVar('ua');
        $sessionExpires = $this->getVar('EXPIRES');

        if (isset($sessionUser) === false
            || isset($sessionIP) === false
            || isset($sessionUA) === false
            || isset($sessionExpires) === false
        ) {
            return false;
        }//end if

        if ($sessionIP !== $cip
            || $sessionUA !== $cua
            || $sessionExpires < time()
        ) {
            $this->doLogout();

            return false;
        }//end if

        // If everything passed, session is valid.
        return true;
    }//end isLoggedIn()


    /**
     * Regenerate the session ID, destroying the old one after copying values.
     *
     * @return void
     */
    public function regenerateID()
    {
        if (defined('RUNNING_PHPUNIT_TESTS') === false) {
            session_regenerate_id(true);
        }//end if
    }//end regenerateID()


    /**
     * Wrapper around the $_SESSION superglobal used for getting.
     *
     * @param string $var Variable to get.
     *
     * @return string Variable value
     */
    public function getVar($var)
    {
        if (isset($_SESSION[$var]) === true) {
            return $_SESSION[$var];
        }//end if

        return null;
    }//end getVar()


    /**
     * Wrapper around the $_SESSION superglobal used for setting.
     *
     * @param string $var   Variable to set.
     * @param string $value Variable value.
     *
     * @return void
     */
    private function setVar($var, $value)
    {
        $_SESSION[$var] = $value;
    }//end setVar()
}//end class
