<?php

namespace NGiraud\NovaTranslatable\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    protected $guarded = [];

    /**
     * All fields that are translatable.
     *
     * @return array
     */
    public $translatable = [
        'name',
//        'characteristics',
    ];
}
