<?php

/**
 *
 * Description: Implementation of text object
 *
 * Blog: http://trialforce.nostaljia.eng.br
 *
 * Started at Mar 11, 2010
 *
 * @version 0.1
 *
 * @author Eduardo Bonfandini
 *
 * -----------------------------------------------------------------------
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
 * ----------------------------------------------------------------------
 */

declare(strict_types=1);

namespace mober\phpsvg;

class SVGText extends SVGShape
{

    /**
     * @param int|float|string $x
     * @param int|float|string $y
     * @param null|string $id
     * @param string $text
     * @param null|SVGStyle|string $style
     * @return SVGText
     */
    public static function getInstance($x, $y, string $text, $style = null, ?string $id = null): SVGText
    {
        $t = new SVGText('<text></text>');
        $t->setX($x);
        $t->setY($y);
        $t->setId($id);
        if (!is_null($style)) {
            $t->setAttribute('style', $style);
        }
        /** @psalm-suppress UndefinedMethod */
        $t[0] = $text;

        return $t;
    }
}
