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
 * Test the TemplateEngine correctly parses tags.
 *
 * @category  Tests
 * @package   PersonalWebsite
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class TemplateEngineTest extends TestCase
{


    /**
     * Create a known test string to be used by all the following tests.
     *
     * @return string Test Data
     */
    public function createTestData()
    {
        return array(array('Hello {START}World{END}{/START} END'));
    }//end createTestData()


    /**
     * Replace a tag in a string with a given value.
     *
     * @param string $testData Test data provided by createTestData().
     *
     * @covers       ABFramework\classes\TemplateEngine::replaceTag
     * @dataProvider createTestData
     * @return       none
     */
    public function testReplaceTag($testData)
    {
        $templateEngine = new \ABFramework\classes\TemplateEngine();
        $out = $testData;
        $templateEngine->replaceTag('{END}', 'START', $out);

        $this->assertEquals($out, 'Hello {START}WorldSTART{/START} END');
    }//end testReplaceTag()


    /**
     * Parse a set of tags in a given array.
     *
     * @param string $testData Test data provided by createTestData().
     *
     * @covers       ABFramework\classes\TemplateEngine::parseTags
     * @dataProvider createTestData
     * @return       none
     */
    public function testParseTags($testData)
    {
        // Test replacing with empty and single space strings.
        $testArray = array(
                      '{START}' => '',
                      '{END}'   => ' ',
                     );

        $templateEngine = new \ABFramework\classes\TemplateEngine();
        $out = $testData;
        $templateEngine->parseTags($testArray, $out);

        $this->assertEquals($out, 'Hello World {/START} END');
    }//end testParseTags()


    /**
     * Remove a set of tags in a given array.
     *
     * @param string $testData Test data provided by createTestData().
     *
     * @covers       ABFramework\classes\TemplateEngine::removeTags
     * @dataProvider createTestData
     * @return       none
     */
    public function testRemoveTags($testData)
    {
        $testArray = array(
                      '{START}',
                      '{END}',
                     );

        $templateEngine = new \ABFramework\classes\TemplateEngine();
        $out = $testData;
        $templateEngine->removeTags($testArray, $out);

        $this->assertEquals($out, 'Hello World{/START} END');
    }//end testRemoveTags()


    /**
     * Test logic tags.
     *
     * @param string $testData Test data provided by createTestData().
     *
     * @covers       ABFramework\classes\TemplateEngine::logicTag
     * @dataProvider createTestData
     * @return       none
     */
    public function testLogicTag($testData)
    {
        $templateEngine = new \ABFramework\classes\TemplateEngine();
        $out = $testData;

        // Test with a valid tag.
        $tag = $templateEngine->logicTag('{START}', '{/START}', $out);
        $this->assertEquals($tag, 'World{END}');

        // Test for a tag which will not be found. Should return a blank string.
        $tag = $templateEngine->logicTag('{STARTX}', '{/STARTX}', $out);
        $this->assertEmpty($tag);
    }//end testLogicTag()


    /**
     * Test removing logic tags.
     *
     * @param string $testData Test data provided by createTestData().
     *
     * @covers       ABFramework\classes\TemplateEngine::removeLogicTag
     * @dataProvider createTestData
     * @return       none
     */
    public function testRemoveLogicTag($testData)
    {
        $templateEngine = new \ABFramework\classes\TemplateEngine();

        // Test with a valid tag.
        $out = $testData;
        $templateEngine->removeLogicTag('{START}', '{/START}', $out);
        $this->assertEquals($out, 'Hello  END');

        // Test with a missing tag. Should not modify the input string.
        $out = $testData;
        $templateEngine->removeLogicTag('{STARTX}', '{/STARTX}', $out);
        $this->assertEquals($out, $testData);
    }//end testRemoveLogicTag()
}//end class
