<?php

/**
 *
 * Description: Implementation of Ellipse.
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

class SVGEllipse extends SVGShapeEx
{
    /**
     * Construct a circle
     *
     * @param int|float|string $cx the center x
     * @param int|float|string $cy the center y
     * @param int|float|string $radiusX
     * @param int|float|string $radiusY
     * @param null|string $id the id of element
     * @param null|SVGStyle|string $style style of element
     *
     * @return SVGEllipse
     */
    public static function getInstance($cx, $cy, $radiusX, $radiusY, $style = null, ?string $id = null): SVGEllipse
    {
        $circle = new SVGEllipse('<ellipse></ellipse>');

        $circle->setCx($cx);
        $circle->setCy($cy);
        $circle->setRadius($radiusX, $radiusY);
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
        $this->addAttribute('cx', (string) $cx);
    }

    /**
     * Define the center y
     *
     * @param int|float|string $cy
     */
    public function setCy($cy): void
    {
        $this->addAttribute('cy', (string) $cy);
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
     * @param int|float|string $radiusX
     * @param int|float|string $radiusY
     *
     * @return void
     */
    public function setRadius($radiusX, $radiusY): void
    {
        $this->addAttribute('rx', (string) $radiusX);
        $this->addAttribute('ry', (string) $radiusY);
    }

    /**
     * Return the x radius of circle
     *
     * @return string the radius of circle
     */
    public function getRadiusX(): string
    {
        return $this->getAttribute('rx');
    }

    /**
     * Return the y radius of circle
     *
     * @return string the radius of circle
     */
    public function getRadiusY(): string
    {
        return $this->getAttribute('ry');
    }
}
