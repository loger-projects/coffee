<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;
use MenuMeal;
use MenuType;
use MenuReview;

class Menu extends Model
{
    protected $fillable = [
        'type_id', 
        'name', 
        'description', 
        'price',
        'is_new', 
        'rating',
        'thumbnail'
    ];

    // belongsTo: MenuType
    // hasMany: MenuReview
    // belongsToMany: Image / Meal

    /**
     * Undocumented function
     *
     * @return void
     */
    public function type()
    {
        return $this->belongsTo(MenuType::class, 'type_id');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function reviews()
    {
        return $this->hasMany(MenuReview::class, 'menu_id');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function meals()
    {
        return $this->belongsToMany(MenuMeal::class, 'menu_meal', 'menu_id', 'meal_id');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function images()
    {
        $this->belongsToMany(Image::class, 'image_menu', 'menu_id', 'image_id');
    }
}