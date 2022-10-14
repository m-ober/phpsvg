<?php

/**
 *
 * Description: Implementation of Shape, is a generic class with basic shape functions.
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

class SVGShape extends XMLElement
{
    private const TRANSFORM_SEPARATOR = ' ';

    /**
     * Define the x coordinate of position
     *
     * @param float|int|string $x the x coordinate of position
     */
    public function setX(float|int|string $x): static
    {
        $this->setAttribute('x', $x);

        return $this;
    }

    /**
     * Return the x coordinate of position
     *
     * @return string the x coordinate of position
     */
    public function getX(): string
    {
        return $this->getAttribute('x');
    }

    /**
     * Define the y coordinate of position
     *
     * @param float|int|string $y the y coordinate of position
     */
    public function setY(float|int|string $y): static
    {
        $this->setAttribute('y', $y);

        return $this;
    }

    /**
     * Return the y coordinate of position
     *
     * @return string the y coordinate of position
     */
    public function getY(): string
    {
        return $this->getAttribute('y');
    }

    /**
     * Define the style of element, can be a SVGStyle element or an string
     *
     * @param string|SVGStyle|null $style SVGStyle element or an string
     */
    public function setStyle(SVGStyle|string|null $style): static
    {
        if (!is_null($style)) {
            if (empty($style)) {
                $style = new SVGStyle();
            }
            $this->setAttribute('style', $style);
        }

        return $this;
    }

    /**
     * Return the style element
     *
     * @return SVGStyle style of element
     */
    public function getStyle(): SVGStyle
    {
        return new SVGStyle($this->getAttribute('style'));
    }

    /**
     * Show element
     */
    public function show(): static
    {
        $style = $this->getStyle();
        $style->show();
        return $this->setStyle($style);
    }

    /**
     * Hide the element
     */
    public function hide(): static
    {
        $style = $this->getStyle();
        $style->hide();
        return $this->setStyle($style);
    }

    /**
     * Return the string with the transformation of shape
     *
     * @return string the transformation of shape
     */
    public function getTransform(): string
    {
        return $this->getAttribute('transform');
    }

    /**
     *  Return the tranform attribute as a list/array
     *
     * @return array transform dados
     */
    public function getTranformList(): array
    {
        return explode(self::TRANSFORM_SEPARATOR, $this->getTransform());
    }

    /**
     * Define the transformation of Shape
     *
     * @param string $transform the transformation command
     * @see http://www.w3.org/TR/SVG/coords.html#TransformAttribute
     */
    public function setTransform(string $transform): static
    {
        $this->setAttribute('transform', $transform);

        return $this;
    }

    /**
     * Add a transformation of Shape
     *
     * @param string $transform the transformation command
     * @see http://www.w3.org/TR/SVG/coords.html#TransformAttribute
     */
    public function addTransform(string $transform): static
    {
        if ($this->getTransform()) {
            $transform = trim($this->getTransform()) . self::TRANSFORM_SEPARATOR . $transform;
        }
        $this->setAttribute('transform', $transform);

        return $this;
    }

    /**
     * rotate(<rotate-angle> [<cx> <cy>]), which specifies a rotation by <rotate-angle> degrees about a given point.
     * If optional parameters <cx> and <cy> are not supplied, the rotate is about the origin of the current user
     * coordinate system. The operation corresponds to the matrix [cos(a) sin(a) -sin(a) cos(a) 0 0].
     * If optional parameters <cx> and <cy> are supplied, the rotate is about the point (cx, cy). The operation
     * represents the equivalent of the following specification:
     * translate(<cx>, <cy>) rotate(<rotate-angle>) translate(-<cx>, -<cy>)
     *
     * @param float $angle the rotation angle
     * @param float $cx x of rotation point
     * @param float $cy y of rotation point
     * @see http://www.w3.org/TR/SVG/coords.html#TransformAttribute
     */
    public function rotate(float $angle, float $cx = 0, float $cy = 0): static
    {
        if ($cx > 0 && $cy > 0) {
            $this->addTransform("rotate($angle,$cx,$cy)");
        } else {
            $this->addTransform("rotate($angle)");
        }

        return $this;
    }

    /**
     * scale(<sx> [<sy>]), which specifies a scale operation by sx and sy.
     * @param float $sx
     * @param float $sy If <sy> is not provided, it is assumed to be equal to <sx>.
     * @see http://www.w3.org/TR/SVG/coords.html#TransformAttribute
     */
    public function scale(float $sx, float $sy = 0): static
    {
        if ($sx > 0 && $sy > 0) {
            $this->addTransform("scale($sx, $sy)");
        } else {
            $this->addTransform("scale($sx)");
        }

        return $this;
    }

    /**
     * translate(<tx> [<ty>]), which specifies a translation by tx and ty
     *
     * Move the shape.
     *
     * @param float $tx translate x
     * @param float $ty translate y If <ty> is not provided, it is assumed to be zero
     */
    public function translate(float $tx, float $ty = 0): static
    {
        if ($ty > 0) {
            $this->addTransform("translate($tx,$ty)");
        } else {
            $this->addTransform("translate($tx);");
        }

        return $this;
    }

    /**
     * skewX(<skew-angle>), which specifies a skew transformation along the x-axis.
     *
     * @param float $angle the skewX angle
     */
    public function skewX(float $angle): static
    {
         $this->addTransform("skewX($angle)");

         return $this;
    }

    /**
     * skewY(<skew-angle>), which specifies a skew transformation along the y-axis.
     *
     * @param float $angle the skewY angle
     */
    public function skewY(float $angle): static
    {
         $this->addTransform("skewY($angle)");

         return $this;
    }

    /**
     * matrix(<a> <b> <c> <d> <e> <f>), which specifies a transformation in the form of a
     * transformation matrix of six values.
     * matrix(a,b,c,d,e,f) is equivalent to applying the transformation matrix [a b c d e f].
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @param float $d
     * @param float $e
     * @param float $f
     */
    public function matrix(float $a, float $b, float $c, float $d, float $e, float $f): static
    {
        $this->addTransform("matrix($a,$b,$c,$d,$e,$f)");

        return $this;
    }

    /**
     * Define the script execute on click in this shape
     *
     * @param string $script
     */
    public function addOnclick(string $script): static
    {
        $this->addAttribute('onclick', $script);

        return $this;
    }

    /**
     * Define the script execute on focus in
     *
     * @param string $script
     */
    public function addOnFocusIn(string $script): static
    {
        $this->addAttribute('onfocusin', $script);

        return $this;
    }

    /**
     * Define the script execute on focus out
     *
     * @param string $script
     */
    public function addOnFocusOut(string $script): static
    {
        $this->addAttribute('onfocusout', $script);

        return $this;
    }

    /**
     * Define the script execute on active
     *
     * @param string $script
     */
    public function addOnActivate(string $script): static
    {
        $this->addAttribute('onactivate', $script);

        return $this;
    }

    /**
     * Define the script execute on mouse down
     *
     * @param string $script
     */
    public function addOnMouseDown(string $script): static
    {
        $this->addAttribute('onmousedown', $script);

        return $this;
    }

    /**
     * Define the script execute on mouse up
     *
     * @param string $script
     */
    public function addOnMouseUp(string $script): static
    {
        $this->addAttribute('onmouseup', $script);

        return $this;
    }

    /**
     * Define the script execute on mouse over
     *
     * @param string $script
     */
    public function addOnMouseOver(string $script): static
    {
        $this->addAttribute('onmouseover', $script);

        return $this;
    }

    /**
     * Define the script execute on mouse move
     *
     * @param string $script
     */
    public function addOnMouseMove(string $script): static
    {
        $this->addAttribute('onmousemove', $script);

        return $this;
    }

    /**
     * Define the script execute on mouse out
     *
     * @param string $script
     */
    public function addOnMouseOut(string $script): static
    {
        $this->addAttribute('onmouseout', $script);

        return $this;
    }
}
