<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'title',
        'page_details',
        'banner_image',
        'details1',
        'image1',
        'details2',
        'image2',
        'details3',
        'details4',
        'key_values',
        'why_choose_us_title',
        'why_choose_us_subtitle',
        'why_choose_us_points',
        'years_experience',
        'establishment_year',
        'mission',
        'vision',
        'history',
    ];

    protected $casts = [
        'key_values' => 'array',
        'why_choose_us_points' => 'array',
        'years_experience' => 'integer',
        'establishment_year' => 'integer',
    ];
}
