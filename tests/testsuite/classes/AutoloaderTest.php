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
 * Test the Autoloader can find files in both root and admin directories.
 *
 * @category  Tests
 * @package   PersonalWebsite
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class AutoloaderTest extends TestCase
{


    /**
     * Test loading a root class.
     *
     * @covers ABFramework\classes\Autoloader::init
     * @return none
     */
    public function testRootClassLoad()
    {
        //Loads from /classes.
        $controllerFactory = new \ABFramework\classes\ControllerFactory(
            'BasePageController',
            '\\ABFramework\\controllers\\'
        );
        $this->assertNotNull($controllerFactory);
    }//end testRootClassLoad()


    /**
     * Test loading a root controller.
     *
     * @covers ABFramework\classes\Autoloader::init
     * @return none
     */
    public function testRootControllerLoad()
    {
        //Loads from /controllers.
        $blankPage = '';
        $basePageController = new \ABFramework\controllers\BasePageController(
            $blankPage
        );
        $this->assertNotNull($basePageController);
    }//end testRootControllerLoad()


    /**
     * Test loading a root model.
     *
     * @covers ABFramework\classes\Autoloader::init
     * @return none
     */
    public function testRootModelLoad()
    {
        //Loads from /models.
        $basePageModel = new \ABFramework\models\BasePageModel();
        $this->assertNotNull($basePageModel);
    }//end testRootModelLoad()


    /**
     * Test to make sure admin classes cannot be loaded from non admin pages.
     * The autoloader should fail to find the file, when the admin page symbol
     * is not defined before the call.
     *
     * @covers ABFramework\classes\Autoloader::init
     * @return none
     */
    public function testAdminLoadFromRoot()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            'Class ABFramework\models\AdminBasePageModel not found.'
        );

        $adminModel = new \ABFramework\models\AdminBasePageModel();
        $this->assertNull($adminModel);
    }//end testAdminLoadFromRoot()


    /**
     * Test loading an admin controller.
     *
     * @covers ABFramework\classes\Autoloader::init
     * @return none
     */
    public function testAdminControllerLoad()
    {
        //Loads from /admin/controllers.
        define('ADMINPAGE', true);
        $blankPage = '';
        $adminController = new \ABFramework\controllers\AdminBasePageController(
            $blankPage
        );
        $this->assertNotNull($adminController);
    }//end testAdminControllerLoad()


    /**
     * Test loading an admin model.
     *
     * @covers ABFramework\classes\Autoloader::init
     * @return none
     */
    public function testAdminModelLoad()
    {
        //Loads from /admin/models.
        $adminBasePageModel = new \ABFramework\models\AdminBasePageModel();
        $this->assertNotNull($adminBasePageModel);
    }//end testAdminModelLoad()
}//end class
