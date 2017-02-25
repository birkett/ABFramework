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
 * @category  Tests
 * @package   PersonalWebsite
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */

namespace ABFramework\tests;

use PHPUnit\Framework\TestCase;

/**
 * Test the SessionManager
 *
 * @category  Tests
 * @package   PersonalWebsite
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class SessionManagerTest extends TestCase
{


    /**
     * Test that SessionManager is singleton.
     *
     * @covers ABFramework\classes\SessionManager::getInstance
     * @return none
     */
    public function testSessionSingleton()
    {
        // New session manager.
        $sessionManagerA  = \ABFramework\classes\SessionManager::getInstance();
        // Return the existing instance.
        $sessionManagerB = \ABFramework\classes\SessionManager::getInstance();
        $this->assertEquals($sessionManagerA, $sessionManagerB);
    }//end testSessionSingleton()


    /**
     * Test to make sure session parameters are set correctly.
     *
     * @covers ABFramework\classes\SessionManager::__construct
     * @return none
     */
    public function testSessionName()
    {
        $sessionManager = \ABFramework\classes\SessionManager::getInstance();

        $this->assertEquals(session_name(), 'ABFrameworkAdmin');

        $params = session_get_cookie_params();

        $this->assertEquals(SESSION_EXPIRY_TIME, $params['lifetime']);
        $this->assertEquals('/'.ADMIN_FOLDER, $params['path']);
    }//end testSessionName()


    /**
     * Test to make sure sessions are destroyed correctly.
     *
     * @covers ABFramework\classes\SessionManager::doLogout
     * @return none
     */
    public function testSessionLogout()
    {
        $sessionManager = \ABFramework\classes\SessionManager::getInstance();

        $this->assertEquals('ABFrameworkAdmin', session_name());

        $sessionManager->doLogout();

        $params = session_get_cookie_params();
        $this->assertEquals(SESSION_EXPIRY_TIME, $params['lifetime']);
    }//end testSessionLogout()


    /**
     * Test to make sure sessions are created and updated.
     *
     * @covers ABFramework\classes\SessionManager::doLogin
     * @covers ABFramework\classes\SessionManager::getVar
     * @covers ABFramework\classes\SessionManager::setVar
     * @return none
     */
    public function testSessionLogin()
    {
        // Set these, PHP running without a SAPI.
        $_SERVER['REMOTE_ADDR']     = '::1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

        $sessionManager = \ABFramework\classes\SessionManager::getInstance();

        // Login will make use of, and test, setVar().
        $sessionManager->doLogin('Test');

        // This also tests getVar().
        $this->assertEquals('Test', $sessionManager->getVar('user'));
        $this->assertEquals('::1', $sessionManager->getVar('ip'));
        $this->assertEquals('PHPUnit', $sessionManager->getVar('ua'));

        // getVar should return null for unknown params.
        $this->assertEquals(null, $sessionManager->getVar('somevar'));
    }//end testSessionLogin()


    /**
     * Check that session ID regeneration works.
     *
     * @covers ABFramework\classes\SessionManager::regenerateID
     * @return none
     */
    public function testSessionRegenerateID()
    {
        // Set these, PHP running without a SAPI.
        $_SERVER['REMOTE_ADDR']     = '::1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

        $sessionManager = \ABFramework\classes\SessionManager::getInstance();
        $sessionManager->doLogin('Test');

        // The new session ID should have all params copied from the old one.
        $this->assertEquals('Test', $sessionManager->getVar('user'));
        $sessionManager->regenerateID();
        $this->assertEquals('Test', $sessionManager->getVar('user'));
    }//end testSessionRegenerateID()
}//end class
