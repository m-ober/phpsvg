<?php

/**
 *
 * Description: Extends SimpleXMlElement funcionalities
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

use RuntimeException;
use SimpleXMLElement;

class XMLElement extends SimpleXMLElement
{
    /**
     * Value used to control last used id
     *
     * @var integer
     */
    protected static int $uniqueId = 0;

    /**
     * Define if is to generate identificator automagic
     *
     * @var boolean if is to generate identificator automagic
     */
    public static bool $useAutoId = true;

    /**
     * @link https://php.net/manual/en/simplexmlelement.construct.php
     * @param string $data A well-formed XML string or the path or URL to an XML document if data_is_url is TRUE.
     * @param int $options Optionally used to specify additional Libxml parameters.
     * @param bool $dataIsURL By default, data_is_url is FALSE.
     * Use TRUE to specify that data is a path or URL to an XML document instead of string data.
     * @param string $namespaceOrPrefix Namespace prefix or URI.
     * @param bool $isPrefix TRUE if ns is a prefix, FALSE if it's a URI; defaults to FALSE.
     * @throws RuntimeException if the XML data could not be parsed.
     */
    public function __construct(
        string $data,
        int $options = 0,
        bool $dataIsURL = false,
        string $namespaceOrPrefix = "",
        bool $isPrefix = false
    ) {
        try {
            parent::__construct($data, $options, $dataIsURL, $namespaceOrPrefix, $isPrefix);
        } catch (\Exception $e) {
            // Catch and re-throw as unchecked exception
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param string $qualifiedName
     * @param string $value
     * @param string|null $namespace
     * @return $this
     */
    public function addAttr(string $qualifiedName, string $value, ?string $namespace = null): static
    {
        parent::addAttribute($qualifiedName, $value, $namespace);

        return $this;
    }

    /**
     * Remove a attribute
     *
     * @param string $attribute name of attribute
     */
    public function removeAttribute(string $attribute): static
    {
        unset($this->attributes()->$attribute);

        return $this;
    }

    /**
     * Define an attribute, differs from addAttribute.
     * Define overwrite existent attribute
     *
     * @param string $attribute attribute to set
     * @param mixed $value value to set (converted to string)
     * @param null|string $namespace the namespace of attribute
     *
     * @example $this->addAttribute("xlink:href", $filename, 'http://www.w3.org/1999/xlink');
     */
    public function setAttribute(string $attribute, mixed $value, ?string $namespace = null): static
    {
        $this->removeAttribute($attribute);
        if (!empty($value)) {
            if (!empty($namespace)) {
                $this->addAttribute($attribute, (string) $value, $namespace);
            } else {
                $this->addAttribute($attribute, (string) $value);
            }
        }

        return $this;
    }

    /**
     * Return a value of a attribute. Support namespaces using namespace:attribute
     *
     * @param string $attribute
     * @return string return the value of passed attribute
     * @example $svg->g->image->getAttribute('xlink:href')
     */
    public function getAttribute(string $attribute): string
    {
        $explode = explode(":", $attribute);

        if (count($explode) > 1) {
            $attributes = $this->attributes($explode[0], true);

            //if the attribute exits with namespace return it
            if ($attributes[$explode[1]]) {
                return (string) $attributes[$explode[1]];
            } else {
                //otherwize will return the attribute without namespaces
                $attribute = $explode[1];
            }
        }

        return (string) $this->attributes()->$attribute;
    }

    /**
     * Define identificator of element
     *
     * @param null|string $id
     */
    public function setId(?string $id): static
    {
        if (self::$useAutoId) {
            $id = !empty($id) ? $id : $this->getUniqueId();
        }
        $this->setAttribute('id', $id);

        return $this;
    }

    /**
     * Return identificator of element
     *
     * @return string identificator of element
     */
    public function getId(): string
    {
        return $this->getAttribute('id');
    }

    /**
     * Returns a unique, never used before  identificator, Inkscape like.
     *
     * @return string a unique, never used before  identificator
     */
    public function getUniqueId(): string
    {
        return $this->getName() . (string) self::$uniqueId++;
    }

    /**
     * Append other XMLElement, support namespaces.
     *
     * @param XMLElement $append
     */
    public function append(XMLElement $append): static
    {
        $target = dom_import_simplexml($this);
        $source = dom_import_simplexml($append);
        $import = $target->ownerDocument->importNode($source, true);
        $target->appendChild($import);

        return $this;
    }

    /**
     * Find an element using it's id.
     * This function will return only one element, the first
     *
     * @param string $id the id to find
     * @return XMLElement|null
     */
    public function getElementById(string $id): ?XMLElement
    {
        return $this->getElementByAttribute('id', $id);
    }

    /**
     * Return the first element using the attribute and value passed.
     * Recursive method.
     *
     * @param string $attribute
     * @param string $value
     * @return XMLElement|null
     */
    public function getElementByAttribute(string $attribute, string $value): ?XMLElement
    {
        if ($this->getAttribute($attribute) == $value) {
            return $this;
        } else {
            if ($this->count() > 0) {
                foreach ($this->children() as $child) {
                    /** @psalm-suppress UndefinedMethod */
                    $element = $child->getElementByAttribute($attribute, $value);

                    if ($element) {
                        return $element;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Recursive function that search elements that match the condition.
     * Return an array of XmlElement.
     *
     * @param string $attribute the attribute to search
     * @param string $value the value to search
     * @param string $condition possible values ==, != , >, >=, <, <=
     * @return XMLElement[] array of XMLElement
     */
    public function getElementsByAttribute(string $attribute, string $value, string $condition = '=='): array
    {
        $result = [];

        if ($condition == '==') {
            //treat the empty condition
            if ($value == '') {
                if (!$this->getAttribute($attribute)) {
                    $result[] = $this;
                }
            }

            if ($this->getAttribute($attribute) == $value) {
                $result[] = $this;
            }
        } elseif ($condition == '!=') {
            if ($this->getAttribute($attribute) != $value) {
                $result[] = $this;
            }
        } elseif ($condition == '>') {
            if ($this->getAttribute($attribute) > $value) {
                $result[] = $this;
            }
        } elseif ($condition == '>=') {
            if ($this->getAttribute($attribute) >= $value) {
                $result[] = $this;
            }
        } elseif ($condition == '<') {
            if ($this->getAttribute($attribute) < $value) {
                $result[] = $this;
            }
        } elseif ($condition == '<=') {
            if ($this->getAttribute($attribute) <= $value) {
                $result[] = $this;
            }
        } else {
            if ($this->count() > 0) {
                foreach ($this->children() as $child) {
                    /** @psalm-suppress UndefinedMethod */
                    $element = $child->getElementsByAttribute($attribute, $value);

                    if ($element) {
                        $result[] = $element;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Define the title of the shape
     * The first title element will be considered as document title.
     * Is defined as alternative text in browser.
     *
     * @param string $title
     */
    public function setTitle(string $title): static
    {
        /** @psalm-suppress UndefinedThisPropertyAssignment */
        $this->title = $title;

        return $this;
    }

    /**
     * Return the title of element
     *
     * @return string the title of element
     */
    public function getTitle(): string
    {
        /** @noinspection PhpCastIsUnnecessaryInspection */
        /** @psalm-suppress UndefinedThisPropertyFetch */
        return (string) $this->title;
    }

    /**
     * Define the description of the element
     *
     * @param string $desc
     */
    public function setDescription(string $desc): static
    {
        /** @psalm-suppress UndefinedThisPropertyAssignment */
        $this->desc = $desc;

        return $this;
    }

    /**
     * Return the description of element
     *
     * @return string the description of element
     */
    public function getDescription(): string
    {
        /** @noinspection PhpCastIsUnnecessaryInspection */
        /** @psalm-suppress UndefinedThisPropertyFetch */
        return (string) $this->desc;
    }

    /**
     * @param string|null $filename
     * @param bool $humanReadable
     * @param bool $prolog
     * @return string|int|false
     */
    public function writeXML(
        string|null $filename = null,
        bool $humanReadable = false,
        bool $prolog = true
    ): bool|int|string {
        $dom = dom_import_simplexml($this);

        if ($humanReadable) {
            $dom->ownerDocument->preserveWhiteSpace = false;
            $dom->ownerDocument->formatOutput = true;
        }
        if ($prolog) {
            $payload = $dom->ownerDocument->saveXML();
        } else {
            $payload = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
        }

        if (empty($filename)) {
            return $payload;
        } else {
            if (SVGDocument::getFileExtension($filename) == SVGDocument::EXTENSION_COMPACT) {
                if (!extension_loaded('zlib')) {
                    throw new RuntimeException('Please install "ext-zlib" extension.');
                }
                $filename = 'compress.zlib://' . $filename;
            }
            return file_put_contents($filename, $payload);
        }
    }

    /**
     * Remove an element by it's id.
     *
     * @param string $id
     */
    public function removeElementById(string $id): static
    {
        $this->removeElement($this->getElementById($id));

        return $this;
    }

    public function removeElement(SimpleXMLElement $node): static
    {
        $dom = dom_import_simplexml($node);
        $dom->parentNode->removeChild($dom);

        return $this;
    }
}
