<?php

namespace Application\Persistence\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Statistics
 *
 * This class represents the Statistics entity in the database.
 * It is used to track various statistics like home view count.
 */
class Statistics extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'statistics';

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'home_view_count',
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     */
    public $timestamps = true;
}
