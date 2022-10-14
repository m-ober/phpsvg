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
     * @see https://www.w3.org/TR/SVG11/shapes.html#CircleElement
     */
    public function __construct(
        float|int|string $cx,
        float|int|string $cy,
        float|int|string $radius,
        SVGStyle|string $style = null,
        ?string $id = null
    ) {
        parent::__construct('<circle></circle>');

        $this->setCx($cx);
        $this->setCy($cy);
        $this->setRadius($radius);
        $this->setId($id);
        $this->setStyle($style);
    }

    /**
     * @deprecated
     */
    public static function getInstance(
        float|int|string $cx,
        float|int|string $cy,
        float|int|string $radius,
        SVGStyle|string $style = null,
        ?string $id = null
    ): SVGCircle {
        return new SVGCircle($cx, $cy, $radius, $style, $id);
    }

    /**
     * Define the center x
     *
     * @param int|float|string $cx
     */
    public function setCx(int|float|string $cx): void
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
    public function setCy(int|float|string $cy): void
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
    public function setRadius(int|float|string $radius): void
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
