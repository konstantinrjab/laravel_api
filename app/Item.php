<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="item",
 *   description="Product instance"
 * )
 *
 * @SWG\Definition(
 *   definition="item",
 *   @SWG\Property(
 *      property="item",
 *      type="object",
 *      @SWG\Property(
 *         property="id",
 *         type="integer",
 *         description="Item ID"
 *      ),
 *      @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="Item name"
 *      ),
 *      @SWG\Property(
 *         property="sku",
 *         type="string",
 *      ),
 *      @SWG\Property(
 *         property="category",
 *         ref="#definitions/category"
 *      ),
 *      @SWG\Property(
 *         property="price",
 *         type="integer",
 *      ),
 *      @SWG\Property(
 *         property="parameters",
 *         ref="#definitions/itemParameter"
 *      ),
 *      @SWG\Property(
 *         property="images",
 *         ref="#definitions/image"
 *      ),
 *      @SWG\Property(
 *         property="created_at",
 *         type="string",
 *      ),
 *      @SWG\Property(
 *         property="updated_at",
 *         type="string",
 *      )
 *  )
 * )
 */
class Item extends Model
{
    protected $fillable = ['name', 'category_id', 'price', 'sku'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function parameters()
    {
        return $this->belongsToMany('App\Parameter')->withPivot('value');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }
}
