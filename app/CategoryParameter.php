<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Tag(
 *   name="categoryParameter",
 *   description="category have parameters"
 * )
 *
 * @SWG\Definition(
 *   definition="categoryParameter",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *   ),
 *   @SWG\Property(
 *      property="category_id",
 *      type="integer",
 *   ),
 *     @SWG\Property(
 *      property="parameter_id",
 *      type="integer",
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
class CategoryParameter extends Model
{
    protected $table = 'category_parameter';
    protected $fillable = ['category_id', 'parameter_id', 'value'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    public function parameter()
    {
        return $this->belongsTo('App\Parameter');
    }
}
