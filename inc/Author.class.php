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

use bmarwell\wp_jsonld\JsonLD;

/**
 * Class: Author.
 *
 * Represents a Person who wrote an Article.
 *
 * @see JsonLD
 */
class Author extends JsonLD
{

    /**
     * email
     *
     * @var String
     */
    public $email;

    /**
     * name
     *
     * @var String
     */
    public $name;

    /**
     * url
     *
     * The author page in wordpress.
     *
     * @var String
     */
    public $url;

    /**
      * @param bool|FALSE $addContext
      */
    public function __construct($addContext = false)
    {
        parent::__construct("Person", $addContext);
    }
}


