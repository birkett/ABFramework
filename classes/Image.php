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
 * Provides image handling and manipulation capabilities.
 *
 * Wraps up some GD and Filsystem functions for easy OOP access.
 *
 * @category  Classes
 * @package   ABFramework
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://www.a-birkett.co.uk
 */
class Image
{

    /**
     * Store the GD resource.
     *
     * @var resource $mImageData
     */
    private $mImageData;

    /**
     * Image width.
     *
     * @var integer $mImageWidth
     */
    private $mImageWidth;

    /**
     * Image height.
     *
     * @var integer $mImageHeight
     */
    private $mImageHeight;


    /**
     * Create a new image instance.
     *
     * @param string $filename File name.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->loadImageData($filename);
        $this->getDimensions($filename);
    }//end __construct()


    /**
     * Load the image data.
     *
     * @param string $filename Image file to load data from.
     *
     * @return void
     */
    private function loadImageData($filename)
    {
        $this->mImageData = imagecreatefromstring(file_get_contents($filename));
    }//end loadImageData()


    /**
     * Get the source image dimensions.
     *
     * @param string $filename File name.
     *
     * @return void
     */
    private function getDimensions($filename)
    {
        $size               = getimagesize($filename);
        $this->mImageWidth  = $size[0];
        $this->mImageHeight = $size[1];
    }//end getDimensions()


    /**
     * Rotate an image.
     *
     * @param integer $degrees Degrees to rotate by.
     *
     * @return void
     */
    public function doRotate($degrees)
    {
        // Width and height are flipped if the image has been rotated.
        if ($degrees === '90' || $degrees === '270') {
            $temp               = $this->mImageWidth;
            $this->mImageWidth  = $this->mImageHeight;
            $this->mImageHeight = $temp;
        }//end if

        $this->mImageData = imagerotate($this->mImageData, -$degrees, 0);
        $output           = imagecreatetruecolor($this->mImageWidth, $this->mImageHeight);

        imagecopyresampled(
            $output,
            $this->mImageData,
            0,
            0,
            0,
            0,
            $this->mImageWidth,
            $this->mImageHeight,
            $this->mImageWidth,
            $this->mImageHeight
        );

        $this->mImageData = $output;
    }//end doRotate()


    /**
     * Resize an image.
     *
     * @param integer $newWidth  New image width.
     * @param integer $newHeight New image height.
     * @param integer $selWidth  Selected area width.
     * @param integer $selHeight Selected area height.
     * @param integer $selX      Selection X coord.
     * @param integer $selY      Selection Y coord.
     *
     * @return void
     */
    public function doResize($newWidth, $newHeight, $selWidth, $selHeight, $selX, $selY)
    {
        $outResized = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled(
            $outResized,
            $this->mImageData,
            0,
            0,
            $selX,
            $selY,
            $newWidth,
            $newHeight,
            $selWidth,
            $selHeight
        );

        $this->mImageData   = $outResized;
        $this->mImageWidth  = $newWidth;
        $this->mImageHeight = $newHeight;
    }//end doResize()


    /**
     * Save an image.
     *
     * @param string $path     File path.
     * @param string $filename File name.
     *
     * @return void
     */
    public function saveImage($path, $filename)
    {
        $this->createDirectory($path);

        imagejpeg($this->mImageData, $path.$filename);
    }//end saveImage()


    /**
     * Create a new folder if it doesnt exist.
     *
     * @param string $path File path.
     *
     * @return void
     */
    private function createDirectory($path)
    {
        if (file_exists($path) === false) {
            mkdir($path);
        }//end if
    }//end createDirectory()
}//end class
