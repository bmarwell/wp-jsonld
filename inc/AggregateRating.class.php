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

use bmarwell\wp_jsonld\Rating;
use bmarwell\wp_jsonld\JsonLD;

/**
 * Class: AggregateRating.
 *
 * The overall rating, based on a collection of reviews or ratings, of the item.
 * (Hint: Exclusive or, i.e. only one can apply)
 * https://schema.org/AggregateRating
 *
 * @see Rating
 */
class AggregateRating extends Rating {

    /**
     * The item that is being reviewed/rated.
     * */
    public $itemReviewed;

    /*
     * The count of total number of ratings.
     * This is what users poll gave.
     * */
    public $ratingCount;

    /**
     * The count of total number of reviews.
     * This is filled if based on reviews of the author
     * */
    public $reviewCount;



    /**
      * @param bool|FALSE $addContext
      */
    public function __construct($type = "AggregateRating") {
        parent::__construct();
    }
}


