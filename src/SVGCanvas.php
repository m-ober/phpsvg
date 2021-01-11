<?php

declare(strict_types=1);

namespace mober\phpsvg;

interface SVGCanvas
{
    public function addShape(XMLElement $append): void;
}
