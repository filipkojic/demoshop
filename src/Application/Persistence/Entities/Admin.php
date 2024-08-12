<?php

namespace Application\Persistence\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Admin
 *
 * This class represents the Admin entity in the database.
 * It uses Eloquent ORM for interaction with the database.
 */
class Admin extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'admins';

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'username', 'password', 'token',
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     */
    public $timestamps = true;
}
