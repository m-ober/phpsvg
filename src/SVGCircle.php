<?php

/**
 *
 * Description: Implementation of Circle.
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at Mar 11, 2011
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

class SVGCircle extends SVGShapeEx
{
    /**
     * Construct a circle
     *
     * @param int|float|string $cx the center x
     * @param int|float|string $cy the center y
     * @param int|float|string $radius the radius of circle
     * @param null|string $id the id of element
     * @param null|SVGStyle|string $style style of element
     *
     * @return SVGCircle
     *
     * @see https://www.w3.org/TR/SVG11/shapes.html#CircleElement
     */
    public static function getInstance($cx, $cy, $radius, $style = null, ?string $id = null): SVGCircle
    {
        $circle = new SVGCircle('<circle></circle>');

        $circle->setCx($cx);
        $circle->setCy($cy);
        $circle->setRadius($radius);
        $circle->setId($id);
        $circle->setStyle($style);

        return $circle;
    }

    /**
     * Define the center x
     *
     * @param int|float|string $cx
     */
    public function setCx($cx): void
    {
        $this->setAttribute('cx', (string) $cx);
    }

    /**
     * Return the center x
     *
     * @return string cx attribute
     */
    public function getCx(): string
    {
        return $this->getAttribute('cx');
    }

    /**
     * Define the center y
     *
     * @param int|float|string $cy
     */
    public function setCy($cy): void
    {
        $this->setAttribute('cy', (string) $cy);
    }

    /**
     * Return the center y
     *
     * @return string cy attribute
     */
    public function getCy(): string
    {
        return $this->getAttribute('cy');
    }

    /**
     * Define the radius of circle
     *
     * @param int|float|string $radius
     */
    public function setRadius($radius): void
    {
        $this->setAttribute('r', (string) $radius);
    }

    /**
     * Return the radius of circle
     *
     * @return string the radius of circle
     */
    public function getRadius(): string
    {
        return $this->getAttribute('r');
    }
}
