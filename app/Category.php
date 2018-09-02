<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="category",
 *   description="have products"
 * )
 *
 * @SWG\Definition(
 *   definition="category",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="Category ID"
 *   ),
 *   @SWG\Property(
 *      property="name",
 *      type="string",
 *      description="Category Name"
 *   ),
 *   @SWG\Property(
 *      property="created_at",
 *      type="string",
 *   ),
 *   @SWG\Property(
 *      property="updated_at",
 *      type="string",
 *   ),
 *     @SWG\Property(
 *      property="items_count",
 *      type="integer",
 *   ),
 * )
 */
class Category extends Model
{
    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany('App\Item');
    }
    public function parameters()
    {
        return $this->hasMany('App\CategoryParameter');
    }
}
