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

use SimpleXMLElement;

class XMLElement extends SimpleXMLElement
{

    /**
     * Value used to control last used id
     *
     * @var integer
     */
    protected static $uniqueId = 0;

    /**
     * Define if is to generate identificator automagic
     *
     * @var boolean if is to generate identificator automagic
     */
    public static $useAutoId = true;

    /**
     * Remove a attribute
     *
     * @param string $attribute name of attribute
     *
     * @return void
     */
    public function removeAttribute(string $attribute): void
    {
        unset($this->attributes()->$attribute);
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
     *
     * @return void
     */
    public function setAttribute(string $attribute, $value, ?string $namespace = null): void
    {
        $this->removeAttribute($attribute);
        if (!empty($namespace)) {
            $this->addAttribute($attribute, (string) $value, $namespace);
        } else {
            $this->addAttribute($attribute, (string) $value);
        }
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
    public function setId(?string $id): void
    {
        if (self::$useAutoId) {
            $id = !empty($id) ? $id : $this->getUniqueId();
        }
        $this->setAttribute('id', $id);
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
     *
     * @return void
     */
    public function append(XMLElement $append): void
    {
        //list all namespaces used in append object
        $namespaces = $append->getNameSpaces();

        //get all childs
        if ($append->count() > 0) {
            $xml = $this->addChild($append->getName());

            foreach ($append->children() as $child) {
                /** @psalm-suppress UndefinedMethod */
                $xml->append($child);
            }
        } else {
            //add one child
            $xml = $this->addChild(
                $append->getName(),
                htmlspecialchars((string) $append, ENT_XML1)
            );
        }

        //add simple attributes
        foreach ($append->attributes() as $attribute => $value) {
            $xml->addAttribute($attribute, (string) $value);
        }

        //add attributes with namespace example xlink:href
        foreach ($namespaces as $index => $namespace) {
            foreach ($append->attributes($namespace) as $attribute => $value) {
                $xml->addAttribute($index . ':' . $attribute, (string) $value, $namespace);
            }
        }
    }

    /**
     * Find an element using it's id.
     * This function will return only one element, the first
     *
     * @param string $id the id to find
     * @return XMLElement
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
     * @return XMLElement
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
                foreach ($this->children() as $line => $child) {
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
     * @return void
     */
    public function setTitle(string $title): void
    {
        /** @psalm-suppress UndefinedThisPropertyAssignment */
        $this->title = $title;
    }

    /**
     * Return the title of element
     *
     * @return string the title of element
     */
    public function getTitle(): string
    {
        /** @psalm-suppress UndefinedThisPropertyFetch */
        return $this->title;
    }

    /**
     * Define the description of the element
     *
     * @param string $desc
     * @return void
     */
    public function setDescription(string $desc): void
    {
        /** @psalm-suppress UndefinedThisPropertyAssignment */
        $this->desc = $desc;
    }

    /**
     * Return the description of element
     *
     * @return string the description of element
     */
    public function getDescription(): string
    {
        /** @psalm-suppress UndefinedThisPropertyFetch */
        return $this->desc;
    }

    /**
     * @param string|null $filename
     * @param bool $humanReadable
     * @param bool $prolog
     * @return string|bool
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function asXML($filename = null, $humanReadable = false, $prolog = true)
    {
        // branching is required here because null cannot be passed to parent::asXML()
        if (!is_null($filename)) {
            return parent::asXML($filename);
        } else {
            $dom = dom_import_simplexml($this);

            if ($humanReadable) {
                $dom->ownerDocument->preserveWhiteSpace = false;
                $dom->ownerDocument->formatOutput = true;
            }
            if ($prolog) {
                return $dom->ownerDocument->saveXML();
            } else {
                return $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
            }
        }
    }

    /**
     * Remove an element by it's id.
     *
     * @param string $id
     * @return void
     */
    public function removeElementById(string $id): void
    {
        $this->removeElement($this->getElementById($id));
    }

    public function removeElement(SimpleXMLElement $node): void
    {
        $dom = dom_import_simplexml($node);
        $dom->parentNode->removeChild($dom);
    }
}
