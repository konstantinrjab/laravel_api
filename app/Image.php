<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="image",
 *   description="product"
 * )
 *
 * @SWG\Definition(
 *   definition="image",
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
class Image extends Model
{
    protected $fillable = ['item_id', 'order', 'path'];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
