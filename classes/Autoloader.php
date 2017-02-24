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
 * Provides a PSR-4 compliant autoloader, and calls the site config.
 *
 * A call to Autoloader::init() should be the first call on an index page, as
 * this will autoload any classes called later. This also creates a site config
 * instance making sure symbols are available immediatly after the call to
 * Autoloader::init().
 *
 * The class autoloader will check for classes, controllers and models located
 * in either the root directory, or the admin directory. The public files will
 * always be attempted first, and admin files will only be attempted if being
 * requested from an admin page.
 *
 * @category  Autoloader
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@it-lincs.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class Autoloader
{

    /**
     * List of namespaces this autoloader is registered for.
     * These are stored as key value pairs, key being the namespace, value
     * being the path to load from.
     *
     * @var array $registeredNamespaces
     */
    private static $registeredNamespaces;


    /**
     * Add a namespace to the list that this autoloader will be registered for.
     *
     * @param string $namespace Namespace string to register.
     * @param string $path      File path to load from.
     *
     * @return none
     */
    public static function registerNamespace($namespace, $path)
    {
        Autoloader::$registeredNamespaces[$namespace] = $path;
    }//end registerNamespace()


    /**
     * Autoloader for classes, controllers and models.
     *
     * @throws \Exception Standard exception if the class is not found.
     * @return void
     */
    public static function init()
    {
        // Autoloader.
        spl_autoload_register(
            function ($class) {
                // Does the class use this namespace prefix?
                $found    = false;
                $baseDir  = '';
                $classLen = 0;

                foreach (Autoloader::$registeredNamespaces as $prefix => $path) {
                    $len = mb_strlen($prefix);
                    if (strncmp($prefix, $class, $len) == 0) {
                        $found    = true;
                        $baseDir  = $path;
                        $classLen = $len;
                    } //end if
                }//end foreach

                if ($found === false) {
                    throw new \Exception('Namespace prefix for class '.$class.' not registered.');
                }//end if

                $relativeClass = mb_substr($class, $classLen);

                $endpath = str_replace('\\', '/', $relativeClass).'.php';
                $file    = $baseDir.$endpath;

                // Try the public folders.
                if (file_exists($file) === true) {
                    include $file;

                    return;
                }

                // Try the admin folder.
                if (defined('ADMINPAGE') === true) {
                    $file = $baseDir.ADMIN_FOLDER.$endpath;
                    if (file_exists($file) === true) {
                        include $file;

                        return;
                    }
                }

                throw new \Exception('Class '.$class.' not found.');
            }
        );
    }//end init()
}//end class
