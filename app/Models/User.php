<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'date_of_birth',
        'country',
        'is_student',
        'account_type',
        'bio',
        'github_url',
        'linkedin_url',
        'avatar',
        'cv_path',
        'privacy_settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_student' => 'boolean',
            'privacy_settings' => 'array',
        ];
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }

    public function isFieldVisible(string $field): bool
    {
        // If privacy_settings is null, assume everything is visible (default) or hidden?
        // Let's assume hidden by default for safety, OR visible.
        // User asked to "choose what to share", implying opt-in or opt-out.
        // Let's assume public by default for now to match current behavior, 
        // unless set to false.
        
        // If specific privacy setting exists and is false, it's hidden.
        // Otherwise it's visible.
        return $this->privacy_settings[$field] ?? true;
    }
}
