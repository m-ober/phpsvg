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
    protected const ELEMENT = '<ellipse></ellipse>';

    public function __construct(
        float|int|string $cx,
        float|int|string $cy,
        float|int|string $radiusX,
        float|int|string $radiusY,
        SVGStyle|string|null $style = null,
        ?string $id = null
    ) {
        parent::__construct(static::ELEMENT);

        $this->setCx($cx)
             ->setCy($cy)
             ->setRadius($radiusX, $radiusY)
             ->setId($id)
             ->setStyle($style);
    }

    /**
     * @deprecated
     */
    public static function getInstance(
        float|int|string $cx,
        float|int|string $cy,
        float|int|string $radiusX,
        float|int|string $radiusY,
        SVGStyle|string|null $style = null,
        ?string $id = null
    ): SVGEllipse {
        return new SVGEllipse($cx, $cy, $radiusX, $radiusY, $style, $id);
    }

    /**
     * Define the center x
     *
     * @param float|int|string $cx
     */
    public function setCx(float|int|string $cx): static
    {
        $this->addAttribute('cx', (string) $cx);

        return $this;
    }

    /**
     * Define the center y
     *
     * @param float|int|string $cy
     */
    public function setCy(float|int|string $cy): static
    {
        $this->addAttribute('cy', (string) $cy);

        return $this;
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
     * @param float|int|string $radiusX
     * @param float|int|string $radiusY
     */
    public function setRadius(float|int|string $radiusX, float|int|string $radiusY): static
    {
        $this->addAttribute('rx', (string) $radiusX);
        $this->addAttribute('ry', (string) $radiusY);

        return $this;
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
