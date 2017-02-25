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
 * @category  Traits
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */

namespace ABFramework\traits;

/**
 * Password manipulation tools, which can be used in multiple areas.
 *
 * @category  Traits
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
trait PasswordTools
{


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


    /**
     * Verify a password against a hash.
     *
     * @param string $password Plain text password.
     * @param string $hash     Stored password hash.
     *
     * @return boolean True on match, false otherwise.
     */
    public function verifyPassword($password, $hash)
    {
        // Password_verify is PHP 5.5+, fall back on older versions.
        if (function_exists('password_verify') === true) {
            $result = password_verify($password, $hash);
            return $result;
        }

        $newhash = crypt($password, $hash);

        if ($newhash === $hash) {
            return true;
        }

        return false;
    }//end verifyPassword()
}//end class
