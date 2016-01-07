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
     */
    public function __construct($jsonldtype) {
        $variableType = "@type";
        $variableId = "@id";

        $this->$variableType = $jsonldtype;
        $this->$variableId = null;
    }

    /** Adds @context. */
    public function addContext() {
        $variableContext = "@context";
        $this->$variableContext = "http://schema.org";
    }

    /**
     * Setter for @id. Sadly, schema.org and json-ld do require an @id-field,
     * but php of course doesn't allow this directly. You can still do set the
     * variable name to a variable and then double-reference it, what happens just here.
     * @param String $newId a new identifier (URI, etc.).
     */
    public function setId($newId) {
        $variableId = "@id";
        $this->$variableId = $newId;
    }
}


