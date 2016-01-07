<?php

/**
 * Copyright (C) 2016
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
 * Class: Rating.
 *
 * Represents a Rating.
 * https://schema.org/Rating
 *
 * @see JsonLD
 */
class Rating extends JsonLD {

    /**
     * bestRating
     * The highest value allowed in this rating system. If bestRating is omitted, 5 is assumed.
     *
     * @var Integer
     */
    public $bestRating = 5;

    /**
     * The rating for the content.
     * */
    public $ratingValue;

    /**
     * The lowest value allowed in this rating system. If worstRating is omitted, 1 is assumed.
     * */
    public $worstRating = 1;

    /**
     * name
     *
     * @var String
     */
    public $name;

    /**
     * url
     *
     * The Rating page in wordpress.
     *
     * @var String
     */
    public $url;

    /**
      */
    public function __construct() {
        parent::__construct("Rating");
    }
}


