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
    /**
     * @param int|float|string $x
     * @param int|float|string $y
     * @param null|string $id
     * @param int|float|string $width
     * @param int|float|string $height
     * @param null|SVGStyle|string $style
     * @return SVGRect
     */
    public static function getInstance($x, $y, $width, $height, $style = null, ?string $id = null): SVGRect
    {
        $rect = new SVGRect('<rect></rect>');

        $rect->setX($x);
        $rect->setY($y);
        $rect->setWidth($width);
        $rect->setHeight($height);
        $rect->setId($id);
        $rect->setStyle($style);

        return $rect;
    }

    /**
     * Define the round of rect
     *
     * @param int|float|string $rx the round
     *
     * @return void
     */
    public function setRound($rx): void
    {
        $this->addAttribute('rx', (string) $rx);
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
