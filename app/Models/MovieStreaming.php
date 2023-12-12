<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieStreaming extends Model
{
    use HasFactory;

    protected $table = 'movie_streamings';

    /**
     * The attributtes that are mass assignable
     * 
     * @var array<string>
     */
    protected $fillable = [ 
        'movie_id',
        'streaming_id'
    ];

    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Movie(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Streaming(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Streaming::class, 'streaming_id');
    }
}
