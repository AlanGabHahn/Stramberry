<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessments';
    public $timestamps = false;

    /**
     * The attributtes that are mass assignable
     * 
     * @var array<string>
     */
    protected $fillable = [ 
        'comment',
        'rating',
        'movie_id',
        'user_id'
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
    public function User(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
