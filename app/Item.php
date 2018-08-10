<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
//    protected $fillable = [
//        'attributes'
//    ];
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
