<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="items",
 *   description="product"
 * )
 *
 * @SWG\Definition(
 *   definition="item",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="Item ID"
 *   ),
 *   @SWG\Property(
 *      property="name",
 *      type="string",
 *      description="Item Name"
 *   ),
 *   @SWG\Property(
 *      property="category",
 *      ref="#definitions/category"
 *   ),
 *   @SWG\Property(
 *      property="parameters",
 *      type="object"
 *   ),
 *   @SWG\Property(
 *      property="created_at",
 *      type="string",
 *   ),
 *   @SWG\Property(
 *      property="updated_at",
 *      type="string",
 *   ),
 * )
 */
class Item extends Model
{
    protected $fillable = ['name', 'category_id', 'image', 'price', 'sku'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    public function parameters()
    {
        return $this->belongsToMany('App\Parameter')->withPivot('value');
    }
}
