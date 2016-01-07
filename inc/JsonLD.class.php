<?php

/**
 * Copyright (C) 2015
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace bmarwell\wp_jsonld;

abstract class JsonLD {
    /**
     * Creates a JsonLD-Objekt with type set to $jsonldtype.
     * @param String $jsonldtype
     * @param bool|FALSE $addContext
     */
    public function __construct($jsonldtype, $addContext = false) {
        $variableContext = "@context";
        $variableType = "@type";
        $variableId = "@id";

        if ($addContext === true) {
            $this->$variableContext = "http://schema.org";
        }

        $this->$variableType = $jsonldtype;
        $this->$variableId = null;
    }

    /**
     * Setter for @id. Sadly, schema.org and json-ld do require an @id-field,
     * but php of course doesn't allow this directly. You can still do set the
     * variable name to a variable and then double-reference it, what happens just here.
     * @param String $newId a new identifier (URI, etc.).
     */
    public function setId($newId)
    {
        $id = "@id";
        $this->$id = $newId;
    }
}


