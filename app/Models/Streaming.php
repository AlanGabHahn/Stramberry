<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Streaming extends Model
{
    use HasFactory;

    protected $table = 'streamings';
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
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function movies(): \Illuminate\Database\Eloquent\Relations\belongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_streamings');
    }

}
