<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
