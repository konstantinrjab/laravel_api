<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="parameter",
 *   description="products have parameters"
 * )
 *
 * @SWG\Definition(
 *   definition="parameter",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="Parameter ID"
 *   ),
 *   @SWG\Property(
 *      property="name",
 *      type="string",
 *      description="Parameter Name"
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
class Parameter extends Model
{
    protected $fillable = ['name'];
    protected $table = 'parameters';
    
    public function items()
    {
        return $this->belongsToMany('App\Item');
    }
}
