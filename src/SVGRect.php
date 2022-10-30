<?php

/**
 *
 * Description: Implementation of Rect.
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at Mar 11, 2010
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
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.   See the
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

class SVGRect extends SVGShapeEx
{
    protected const ELEMENT = '<rect></rect>';

    public function __construct(
        float|int|string $x,
        float|int|string $y,
        float|int|string $width,
        float|int|string $height,
        SVGStyle|string $style = null,
        ?string $id = null
    ) {
        parent::__construct(static::ELEMENT);

        $this->setX($x)
             ->setY($y)
             ->setWidth($width)
             ->setHeight($height)
             ->setId($id)
             ->setStyle($style);
    }

    /**
     * @deprecated
     */
    public static function getInstance(
        float|int|string $x,
        float|int|string $y,
        float|int|string $width,
        float|int|string $height,
        SVGStyle|string $style = null,
        ?string $id = null
    ): SVGRect {
        return new SVGRect($x, $y, $width, $height, $style, $id);
    }

    /**
     * Define the round of rect
     *
     * @param float|int|string $rx the round
     */
    public function setRound(float|int|string $rx): static
    {
        $this->addAttribute('rx', (string) $rx);

        return $this;
    }

    /**
     * Return the round of rect
     *
     * @return string return the round
     */
    public function getRound(): string
    {
        return $this->getAttribute('rx');
    }
}
