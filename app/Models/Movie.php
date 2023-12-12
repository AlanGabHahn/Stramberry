<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    public $timestamps = false;

    /**
     * The attributtes that are mass assignable
     * 
     * @var array<string>
     */
    protected $fillable = [ 
        'title',
        'release_month',
        'release_year',
        'genre_id'
    ];

    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Genre(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Assessment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Assessment::class, 'movie_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function streamings(): \Illuminate\Database\Eloquent\Relations\belongsToMany
    {
        return $this->belongsToMany(Streaming::class, 'movie_streamings');
    }
}
