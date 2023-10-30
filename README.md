# phpsvg
Edit and create SVG documents using PHP.
This library is a fork of `mewebstudio/phpsvg`, which was last updated in 2013.

## Features

- Open and edit SVG and SVGZ (GZipped)
- Generate thumbnails or export to PNG, JPG, GIF, PS, EPS, PDF
- Support embedded or linked images
- Strict typing, i.e. `declare(strict_types=1);`

## Installation
* Version 4.x supports PHP 8.0 and newer, because some features (like fluent methods) require language features that
were not available in earlier versions.
* Version 3.x is no longer maintained and supports PHP 7.1 to 8.0.

Install using composer:
```
composer require mober/phpsvg
```
## Example

```php
$svg = new SVGDocument();

$gradient = new SVGLinearGradient([
    // Set style using fluent methods
    (new SVGStop(0))->setColor('blue')->setOpacity(1),
    (new SVGStop(0.8))->setColor('cyan')->setOpacity(0.5),
]);
$svg->addDefs($gradient);
$svg->addShape(
    new SVGRect(10, 20, '100', '200', (new SVGStyle())->setFill($gradient))
);

$radial = new SVGRadialGradient([
    // Set style as string
    new SVGStop(0, 'stop-color:yellow;stop-opacity:1'),
    new SVGStop(0.7, 'stop-color:green;stop-opacity:1'),
]);
$svg->addDefs($radial);
$svg->addShape(
    new SVGCircle(250, 120, 100, (new SVGStyle())->setFill($radial))
);

$svg->writeXML('demo.svg', humanReadable: true);
```
