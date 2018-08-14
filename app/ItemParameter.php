<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @SWG\Definition(
 *   definition="itemParameter",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="Item Parameter ID"
 *   ),
 *   @SWG\Property(
 *      property="item_id",
 *      type="integer",
 *      description="Item ID"
 *   ),
 *   @SWG\Property(
 *      property="parameter_id",
 *      type="integer",
 *      description="Parameter ID"
 *   ),
 *   @SWG\Property(
 *      property="value",
 *      type="sting",
 *      description="Parameter value"
 *   ),
 * )
 */
class ItemParameter extends Model
{
    protected $table = 'item_parameter';
    protected $fillable = ['value'];
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
