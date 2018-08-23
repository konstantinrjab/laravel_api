<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
