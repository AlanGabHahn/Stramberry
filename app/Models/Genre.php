<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $table = 'genres';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable
     * 
     * @var array<string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Movie::class, 'genre_id');
    }
}
