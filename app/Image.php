<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="image",
 *   description="Product image"
 * )
 *
 * @SWG\Definition(
 *   definition="image",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="Image ID"
 *   ),
 *   @SWG\Property(
 *      property="item_id",
 *      type="integer",
 *      description="Item ID"
 *   ),
 *   @SWG\Property(
 *      property="order",
 *      type="integer",
 *      description="Image priority order"
 *   ),
 *   @SWG\Property(
 *      property="path",
 *      type="string",
 *      description="storage/"
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
