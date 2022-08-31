<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['isbn','title','year','publishers_id','author_id','catalog_id','qty','price'];

    public function Publisher()
    {
        return $this->belongsTo('App\Models\Publisher', 'id');

    }

    public function Author()
    {
        return $this->belongsTo('App\Models\Author', 'id');

    }
    
    public function Catalog()
    {
        return $this->belongsTo('App\Models\Catalog', 'id');

    }
    public function Transaction()
    {
        return $this->belongsToMany('App\Models\Transaction', 'transaction_details');
    }

}
