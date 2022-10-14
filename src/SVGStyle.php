<?php

/**
 *
 * Description: Implementation of Style class.
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at Mar 18, 2010
 *
 * @version 0.1
 *
 * @author Eduardo Bonfandini
 *
 *-----------------------------------------------------------------------
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
 *----------------------------------------------------------------------
 */

declare(strict_types=1);

namespace mober\phpsvg;

class SVGStyle
{
    public string $fill;
    public string $stroke;
    public string $strokeWidth;
    public $stopColor;
    public $stopOpacity;
    public $display;
    public $opacity;

    /**
     * Construct the style
     *
     * @param array|string|null $style an array with styles
     */
    public function __construct(array|string $style = null)
    {
        if (is_string($style)) {
            $style = explode(';', $style);

            foreach ($style as $info) {
                $styleElement = explode(':', $info);

                if ($styleElement[0]) {
                    $property = SVGStyle::toCamelCase($styleElement[0]);
                    $this->{$property} = $styleElement[1];
                }
            }
        } elseif (is_array($style)) {
            foreach ($style as $line => $info) {
                $this->$line = $info;
            }
        }
    }

    /**
     * Return the string representation of object
     *
     * @return string representation of object
     */
    public function __toString()
    {
        $vars = get_object_vars($this);
        $result = '';

        foreach ($vars as $line => $info) {
            if (isset($info)) {
                $line = SVGStyle::fromCamelCase($line);
                $result .= "$line:$info;";
            }
        }

        return $result;
    }

    /**
     * Define the display of elemet
     *
     * @param string $display
     *
     * @return void
     */
    public function setDisplay(string $display): void
    {
        $this->display = $display;
    }

    /**
     * Return the display of element
     * @return string
     */
    public function getDisplay(): string
    {
        return $this->display;
    }

    /**
     * Show the element
     *
     * @return void
     */
    public function show(): void
    {
        $this->display = 'inline';
    }

    /**
     * Hide the element
     *
     * @return void
     */
    public function hide(): void
    {
        $this->display = 'none';
    }

    /**
     * Set the fill color
     *
     * @param SVGLinearGradient|string $fill color
     *
     * @return void
     */
    public function setFill(SVGLinearGradient|string $fill): void
    {
        if ($fill instanceof SVGLinearGradient) {
            $fill = $this->url($fill);
        }

        $this->fill = $fill;
    }

    /**
     * Get the fill color
     *
     * @return string fill color
     */
    public function getFill(): string
    {
        return $this->fill;
    }

    /**
     * Set the stroke (contour) color
     *
     * @param string $stroke the stroke color
     * @param float|int|string $width
     *
     * @return void
     */
    public function setStroke(string $stroke, float|int|string $width = 0): void
    {
        $this->stroke = $stroke;
        $this->setStrokeWidth($width);
    }

    /**
     * Define the width of the stroke
     *
     * @param float|int|string $width width of the stroke
     *
     * @return void
     */
    public function setStrokeWidth(float|int|string $width): void
    {
        if (!empty($width)) {
            $this->strokeWidth = $width;
        }
    }

    /**
     * Return the stroke width
     *
     * @return string
     */
    public function getStrokeWidth(): string
    {
        return $this->strokeWidth;
    }

    /**
     * Return the stroke (contour) color
     *
     * @return string
     */
    public function getStroke(): string
    {
        return $this->stroke;
    }

    /**
     * Make the url in some param
     *
     * @param string|XMLElement $content
     *
     * @return string
     */
    public function url(string|XMLElement $content): string
    {
        $url = $content;

        if ($content instanceof XMLElement) {
            $url = '#' . $content->getId();
        }

        return "url({$url})";
    }

    /**
     * Make a not camelCase version of string
     *
     * http://www.paulferrett.com/2009/php-camel-case-functions/
     *
     * stopColor turns stop-color
     *
     * @param string $str
     * @return string the new string
     */
    protected static function fromCamelCase(string $str): string
    {
        $str[0] = strtolower($str[0]);
        return preg_replace_callback('/([A-Z])/', function ($hit) {
            return "-" . strtolower($hit[0]);
        }, $str);
    }

    /**
     * Converts a string to camelCase
     *
     * stop-color turns stopColor
     *
     * @param string $str
     * @return string
     */
    protected static function toCamelCase(string $str): string
    {
        return preg_replace_callback('/-([a-z])/', function ($hit) {
            return strtoupper($hit[0]);
        }, $str);
    }
}
