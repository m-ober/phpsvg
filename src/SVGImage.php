<?php

/**
 *
 * Description: Image implementation
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at Mar 11, 2010
 *
 * @author Eduardo Bonfandini
 *
 * -----------------------------------------------------------------------
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Library General Public License as published
 *   by the Free Software Foundation; either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Library General Public License for more details.
 *
 *   You should have received a copy of the GNU Library General Public
 *   License along with this program; if not, access
 *   http://www.fsf.org/licensing/licenses/lgpl.html or write to the
 *   Free Software Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 * ----------------------------------------------------------------------
 */

declare(strict_types=1);

namespace mober\phpsvg;

class SVGImage extends SVGShapeEx
{

    /**
     * @param int|float|string $x
     * @param int|float|string $y
     * @param null|string $id
     * @param string $filename
     * @param bool $embed
     * @return SVGImage
     */
    public static function getInstance($x, $y, string $filename, bool $embed = true, ?string $id = null): SVGImage
    {
        $image = new SVGImage('<image></image>');

        $image->setX($x);
        $image->setY($y);
        $image->setId($id);
        $image->setImage($filename, $embed);

        return $image;
    }

    /**
     * Return the binary data of image
     *
     * @return string the binary data of image
     * @example file_put_contents( 'output/test.png' , $image->getImage() );
     * @psalm-suppress PossiblyNullPropertyFetch, PossiblyNullArgument
     */
    public function getImage(): string
    {
        $info = $this->getImageData();

        if ($info->encode == 'base64') {
            //get embed image
            return base64_decode($info->binary);
        } else {
            //get file of system
            return file_get_contents($this->getAttribute('xlink:href'));
        }
    }

    /**
     * Explode embed image string returning a stdClass with, mime, encode e binary properties
     *
     * @param string $image
     * @return \stdClass a stdClass with, mime, encode e binary properties
     */
    public function getImageData(): ?\stdClass
    {
        $image = $this->getAttribute('xlink:href');

        if (stripos($image, 'data:') === 0) {
            $explode = explode(',', $image);
            $mime = explode(';', $explode[0]);

            $img = new \stdClass();
            $img->mime = str_replace('data:', '', $mime[0]);
            $img->encode = $mime[1];
            $img->binary = $explode[1];

            return $img;
        }
        return null;
    }

    /**
     * Define the image file.
     * Embed files will be parsed and inserted into SVG file using base64.
     *
     * @param string $filename
     * @param bool $embed if is to embed or not
     */
    public function setImage(string $filename, bool $embed = true): void
    {
        if ($embed) {
            //get the sizes of image using gd
            $imageSize = getimagesize($filename, $imageSize);
            $mime = mime_content_type($filename);
            $file = base64_encode(file_get_contents($filename));
            $filename = 'data:' . $mime . ';base64,' . $file;
            $this->setWidth($imageSize[0]); //define the size of image
            $this->setHeight($imageSize[1]);
        }
        $this->addAttribute("xlink:href", $filename, 'http://www.w3.org/1999/xlink');
    }
}
