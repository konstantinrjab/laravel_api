<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="parameters",
 *   description="products have parameters"
 * )
 *
 * @SWG\Definition(
 *   definition="parameter",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="ItemParameter ID"
 *   ),
 *   @SWG\Property(
 *      property="name",
 *      type="string",
 *      description="ItemParameter Name"
 *   ),
 *   @SWG\Property(
 *      property="created_at",
 *      type="object",
 *   ),
 *   @SWG\Property(
 *      property="updated_at",
 *      type="object",
 *   ),
 * )
 */
class Parameter extends Model
{
    protected $fillable = ['name'];
    protected $table = 'parameters';
    
    public function items()
    {
        return $this->belongsToMany('App\Item');
    }
}
