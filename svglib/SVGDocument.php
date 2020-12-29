<?php

declare(strict_types=1);

/**
 *
 * Description: Implementation SVGDocument include in all other libs
 *
 * Class pre-requisites:
 * - SimpleXmlElement - http://php.net/manual/en/class.simplexmlelement.php
 * - gzip support (for compressed svg) - http://php.net/manual/en/book.zlib.php
 * - imagemagick to export to png - http://php.net/manual/en/book.imagick.php
 * - GD to use embed image - http://php.net/manual/pt_BR/book.image.php
 *
 * @link phpsvg.nostaljia.eng.br
 * @link http://trialforce.nostaljia.eng.br
 * @link http://www.w3.org/TR/SVG/
 *
 * Started at Mar 11, 2010
 * Current version 0.8 01/01/2013
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

/**
 * Reference site:
 * http://www.leftontheweb.com/message/A_small_SimpleXML_gotcha_with_namespaces
 * http://blog.jondh.me.uk/2010/10/resetting-namespaced-attributes-using-simplexml/
 * http://www.w3.org/TR/SVG/
 */

namespace mober\phpsvg;

use Exception;
use RuntimeException;

class SVGDocument extends SVGShape
{

    private const VERSION = '1.1';
    private const XMLNS = 'http://www.w3.org/2000/svg';
    private const EXTENSION = 'svg';
    private const EXTENSION_COMPACT = 'svgz';
    private const HEADER = 'image/svg+xml';
    private const EXPORT_TYPE_IMAGE_MAGIC = 'imagick';
    private const EXPORT_TYPE_INKSCAPE = 'inkscape';

    /**
     * Return the extension of a filename
     *
     * @param string $filename the filename to get the extension
     * @return string the filename to get the extension
     */
    protected static function getFileExtension(string $filename): string
    {
        $explode = explode('.', $filename);
        return strtolower($explode[ count($explode) - 1 ]);
    }

    /**
     * Return a SVGDocument
     * If filename is not passed create a default svg.
     * Supports gzipped content.
     *
     * @param string|null $filename the file to load
     * @return SVGDocument
     */
    public static function getInstance($filename = null)
    {
        if ($filename) {
            //if is svgz use compres.zlib to load the compacted SVG
            if (SVGDocument::getFileExtension($filename) == self::EXTENSION_COMPACT) {
                //verify if zlib is installed
                if (!function_exists('gzopen')) {
                    throw new RuntimeException('GZip support not installed.');
                }

                $filename = 'compress.zlib://' . $filename;
            }

            //get the content
            $content = file_get_contents($filename);

            //throw error if not found
            if (!$content) {
                throw new RuntimeException('Impossible to load content of file ' . $filename);
            }

            $svg = new SVGDocument($content);
        } else {
            //create clean SVG
            $svg = new SVGDocument('<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg></svg>');

            //define the default parameters A4 pageformat
            $svg->setWidth('210mm');
            $svg->setHeight('297mm');
            $svg->setVersion(self::VERSION);
            $svg->setAttribute('xmlns', self::XMLNS);
        }

        return $svg;
    }

    /**
     * Out the file, used in browser situation,
     * because it changes the header to xml header
     *
     * @return void
     */
    public function output(): void
    {
        header('Content-type: ' . self::HEADER);
        echo $this->asXML();
    }

    /**
     * Export the object as xml text, OR xml file.
     * If the file extension is svgz, the function will save it correctely;
     *
     * @param string|null $filename the file to save, is optional, you can output to a var
     * @param bool $humanReadable
     * @return bool|int|string if filename empty: xml as string, otherwise: bytes written as int or false on failure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function asXML($filename = null, $humanReadable = false)
    {
        //if is svgz use compres.zlib to load the compacted SVG
        if (!empty($filename) && SVGDocument::getFileExtension($filename) == self::EXTENSION_COMPACT) {
            //verify if zlib is installed
            if (!function_exists('gzopen')) {
                throw new RuntimeException('GZip support not installed.');
            }

            $filename = 'compress.zlib://' . $filename;
        }

        $xml = parent::asXML(null, $humanReadable);

        //need to do it, if pass a null filename it return an error
        if (!empty($filename)) {
            return file_put_contents($filename, $xml);
        }

        return $xml;
    }

    /**
     * Define the version of SVG document
     *
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->setAttribute('version', $version);
    }

    /**
     * Get the version of SVG document
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->getAttribute('version');
    }

    /**
     * Define the page dimension , width.
     *
     * @param int|float|string $width
     * @example setWidth('350px');
     * @example setWidth('350mm');
     */
    public function setWidth($width): void
    {
        $this->setAttribute('width', (string) $width);
    }

    /**
     * Set the view box attribute, used to make SVG resizable in browser.
     *
     * @param string $startX start x coordinate
     * @param string $startY start y coordinate
     * @param string $width width
     * @param string $height height
     */
    public function setViewBox(string $startX, string $startY, string $width, string $height): void
    {
        $viewBox = str_replace(['%', 'px'], '', "$startX $startY $width $height");
        $this->setAttribute('viewBox', $viewBox);
    }

    /**
     * Set the default view box, based on width and height.
     *
     * Used to make SVG resizable in browser.
     */
    public function setDefaultViewBox(): void
    {
        $this->setViewBox('0', '0', $this->getWidth(), $this->getHeight());
    }

    /**
     * Returns the width of page
     *
     * @return string the width of page
     */
    public function getWidth(): string
    {
        return $this->getAttribute('width');
    }

    /**
     * Define the height of page.
     *
     * @param int|float|string $height
     *
     * @return void
     * @example setHeight('350px');
     * @example setHeight('350mm');
     */
    public function setHeight($height): void
    {
        $this->setAttribute('height', (string) $height);
    }

    /**
     * Returns the height of page
     *
     * @return string the height of page
     */
    public function getHeight(): string
    {
        return $this->getAttribute('height');
    }

    /**
     * Add a shape to SVG graphics
     *
     * @param XMLElement $append the element to append
     */
    public function addShape(XMLElement $append): void
    {
        $this->append($append);
    }

    /**
     * Add some element to defs, normally a gradient
     *
     * @param XMLElement $element
     * @psalm-suppress UndefinedThisPropertyFetch
     */
    public function addDefs(XMLElement $element): void
    {
        if (!$this->defs) {
            $defs = new XMLElement('<defs></defs>');
            $this->append($defs);
        }
        $this->defs->append($element);
    }

    /**
     * Add some script content to svg
     *
     * @param string $script
     */
    public function addScript(string $script): void
    {
        $element = new XMLElement('<script>' . $script . '</script>');
        $this->append($element);
    }

    /**
     * Return the definitions of the document, normally has gradients.
     *
     * @return XMLElement
     * @psalm-suppress UndefinedThisPropertyFetch
     */
    public function getDefs(): XMLElement
    {
        return $this->defs;
    }

    /**
     * Export to a image file, consider file extension
     * Uses imageMagick or inkcape. If one fail try other.
     *
     * Try to define the complete path of files, works better for exportation.
     *
     * @param string $filename
     * @param int $width the width of exported image
     * @param int $height the height of exported image
     * @param boolean $respectRatio respect the ratio, image proportion
     * @param string $exportType the default export type
     * @return bool
     * @throws Exception usually \ImagickException
     */
    public function export(
        string $filename,
        $width = 0,
        $height = 0,
        $respectRatio = false,
        $exportType = SVGDocument::EXPORT_TYPE_IMAGE_MAGIC
    ): bool {
        if ($exportType == SVGDocument::EXPORT_TYPE_IMAGE_MAGIC) {
            try {
                return $this->exportImagick($filename, $width, $height, $respectRatio);
            } catch (Exception $e) {
                try {
                    $this->exportInkscape($filename, $width, $height);
                    return true;
                } catch (Exception $exc) {
                    $exc = null;
                    throw $e; //throw the first error
                }
            }
        } else {
            try {
                $this->exportInkscape($filename, $width, $height);
                return true;
            } catch (Exception $e) {
                try {
                    return $this->exportImagick($filename, $width, $height, $respectRatio);
                } catch (Exception $exc) {
                    $exc = null;
                    throw $e; //throw the original error
                }
            }
        }
    }

    /**
     * Export as SVG to image document using inkscape.
     *
     * It will save a temporary file on default system tempo folder.
     *
     * @param string $filename try to use complete path. Works better.
     * @param int $width
     * @param int $height
     */
    public function exportInkscape(string $filename, int $width = 0, int $height = 0): void
    {
        $format = SVGDocument::getFileExtension($filename);
        $inkscape = new Inkscape($this);
        $inkscape->setSize($width, $height);
        $inkscape->export($format, $filename);
    }

    /**
     * Export to a image file, consider file extension
     * Uses imageMagick
     *
     * @param string $filename
     * @param int $width the width of exported image
     * @param int $height the height of exported image
     * @param bool $respectRatio respect the ratio, image proportion
     * @return bool
     * @throws \ImagickException
     * @noinspection PhpComposerExtensionStubsInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function exportImagick(string $filename, $width = 0, $height = 0, bool $respectRatio = false): bool
    {
        if (!extension_loaded('imagick')) {
            throw new RuntimeException('Imagick extension missing.');
        }

        $image = new \Imagick();

        /** @psalm-suppress PossiblyInvalidArgument */
        $ok = $image->readImageBlob($this->asXML(null, false));

        if ($ok) {
            if ($width > 0 && $height > 0) {
                $image->thumbnailImage($width, $height, $respectRatio);
            }
            $image->writeImage($filename);
            $ok = true;
        }

        return $ok;
    }
}
