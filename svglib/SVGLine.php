<?php

declare(strict_types=1);

/**
 *
 * Description: Implementation of Line.
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at nov 13, 2011
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

namespace mober\phpsvg;

class SVGLine extends SVGShapeEx
{
    /**
     * @param int|float|string $x1
     * @param int|float|string $y1
     * @param int|float|string $x2
     * @param int|float|string $y2
     * @param null|string $id
     * @param null|string|SVGStyle $style
     * @return SVGLine
     */
    public static function getInstance($x1, $y1, $x2, $y2, ?string $id = null, $style = null): self
    {
        $rect = new SVGLine('<line></line>');

        $rect->setX1($x1);
        $rect->setX2($x2);
        $rect->setY1($y1);
        $rect->setY2($y2);
        $rect->setId($id);
        $rect->setStyle($style);

        return $rect;
    }

    /**
     * Define the x 1 of line
     *
     * @param int|float|string $x1
     */
    public function setX1($x1): void
    {
        $this->addAttribute('x1', (string) $x1);
    }

    /**
     * Define the x 2 of line
     *
     * @param int|float|string $x2
     */
    public function setX2($x2): void
    {
        $this->addAttribute('x2', (string) $x2);
    }

    /**
     * Define the y 1 of line
     *
     * @param int|float|string $y1
     */
    public function setY1($y1): void
    {
        $this->addAttribute('y1', (string) $y1);
    }

    /**
     * Define the y 2 of line
     *
     * @param int|float|string $y2
     */
    public function setY2($y2): void
    {
        $this->addAttribute('y2', (string) $y2);
    }

    /**
     * Return x1 attribute
     *
     * @return string x1 attribute
     */
    public function getX1(): string
    {
        return $this->getAttribute('x1');
    }

    /**
     * Return x2 attribute
     *
     * @return string x2 attribute
     */
    public function getX2(): string
    {
        return $this->getAttribute('x2');
    }

    /**
     * Return y1 attribute
     *
     * @return string y1 attribute
     */
    public function getY1(): string
    {
        return $this->getAttribute('y1');
    }

    /**
     * Return y2  attribute
     *
     * @return string y2 attribute
     */
    public function getY2(): string
    {
        return $this->getAttribute('y2');
    }
}
