<?php

/**
 *
 * Description: Implementation of ShapeEx, it is a shape with width.
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at Mar 11, 2010
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

class SVGShapeEx extends SVGShape
{
    /**
     * Define the width of the object
     *
     * @param int|float|string $width
     *
     * @return void
     */
    public function setWidth($width): void
    {
        $this->setAttribute('width', (string) $width);
    }

    /**
     * Return the width of element
     *
     * @return string the width of element
     */
    public function getWidth(): string
    {
        return $this->getAttribute('width');
    }

    /**
     * Define the height of the object
     *
     * @param int|float|string $height
     *
     * @return void
     */
    public function setHeight($height): void
    {
        $this->setAttribute('height', (string) $height);
    }

    /**
     * Return the height of element
     *
     * @return string the height of element
     */
    public function getHeight(): string
    {
        return $this->getAttribute('height');
    }
}
