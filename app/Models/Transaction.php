<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'date_start', 'date_end', 'status'];

    public function members()
    {
        return $this->belongsTo('App\Models\Member', 'member_id');
    }
    public function books()
    {
        return $this->belongsToMany('App\Models\Book', 'transaction_details', 'transaction_id', 'book_id');
    }
    public function detail_pivot()
    {
        return $this->belongsToMany('App\Models\Book', 'transaction_details', 'transaction_id', 'book_id')
        ->withPivot([
            'qty'
        ])->withTimestamps();
    }
}
