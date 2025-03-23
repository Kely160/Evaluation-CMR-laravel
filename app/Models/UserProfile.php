<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * Attributs assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'department',
        'salary',
        'status',
        'oauth_user_image_link',
        'user_image',
        'bio',
        'youtube',
        'twitter',
        'facebook',
        'user_id',
        'country',
        'position',
        'address',
    ];

    /**
     * Relation avec le modèle User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
