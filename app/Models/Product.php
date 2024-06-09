<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'Name',
        'Description',
        'Categorey_id',
        'Size',
        'user_id',
        'Price',
        'Quantity',
        'color',
        'gender',
    ];

    public function categorie()
    {
        return $this->hasMany(Categorie::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }
}
