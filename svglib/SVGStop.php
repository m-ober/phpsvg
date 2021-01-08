<?php

declare(strict_types=1);

/**
 *
 * Description: Implementation of stop, used inside Linear Gradient.
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at Aug 1, 2011
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

namespace mober\phpsvg;

class SVGStop extends XMLElement
{
    /**
     * @param null|string $id
     * @param null|string $style
     * @param float $offset
     * @return SVGStop
     */
    public static function getInstance($id = null, $style = null, float $offset = 0): SVGStop
    {
        $stop = new SVGStop('<stop></stop>');

        $stop->setId($id);
        $stop->setStyle($style);
        $stop->setOffset($offset);

        return $stop;
    }

    /**
     * Define the style of element, can be a SVGStyle element or an string
     *
     * @param null|string|SVGStyle $style SVGStyle element or an string
     *
     * @return void
     */
    public function setStyle($style): void
    {
        if (is_null($style)) {
            return;
        }
        if (empty($style)) {
            $style = new SVGStyle();
        }

        $this->setAttribute('style', $style);
    }

    /**
     * Return the style element
     *
     * @return SVGStyle style of element
     */
    public function getStyle()
    {
        return new SVGStyle($this->getAttribute('style'));
    }

    /**
     * Define the color of the stop
     *
     * @param string $color
     *
     * @return void
     */
    public function setColor(string $color): void
    {
        $style = $this->getStyle();
        $style->stopColor = $color;

        $this->setStyle($style);
    }

    /**
     * Return the color of stop
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->getStyle()->stopColor;
    }

    /**
     * Define the opacity off this stop
     * The make it 100% visible set opacity to 1.
     *
     * @param float $opacity
     *
     * @return void
     */
    public function setOpacity(float $opacity = 1): void
    {
        $style = $this->getStyle();
        $style->stopOpacity = $opacity;

        $this->setStyle($style);
    }

    /**
     * Return the opacity off this stop
     *
     * @return float return the opacity off this stop, 1 means 100% visible
     *
     */
    public function getOpacity()
    {
        return $this->getStyle()->opacity;
    }

    /**
     * Define the offset of the stop
     * Offset variates from 0 to 1, passing by floating value between it.
     *
     * @param float $offset
     *
     * @return void
     */
    public function setOffset(float $offset): void
    {
        $this->setAttribute('offset', (string) $offset);
    }

    /**
     * Return the offset of the stop
     *
     * Offset variates from 0 to 1, passing by floating value between it.
     *
     * @return float
     */
    public function getOffset(): float
    {
        return (float) $this->getAttribute('offset');
    }
}
