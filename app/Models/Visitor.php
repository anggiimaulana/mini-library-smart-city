<?php
// app/Models/Visitor.php (FIXED VERSION)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Visitor extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'visitors';
    protected $primaryKey = 'id'; // default, int
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'nim',
        'major_id',
        'study_program_id',
        'slug',
        'secret_code',
        'is_active',
        'progress',
        'certificate_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'progress' => 'integer',
    ];

    protected $hidden = [
        'secret_code'
    ];

    protected static function booted()
    {
        static::creating(function ($visitor) {
            $visitor->slug = static::generateUniqueSlug($visitor->name);
            $visitor->secret_code = static::generateUniqueSecretCode();
        });

        static::updating(function ($visitor) {
            if ($visitor->isDirty('name')) {
                $visitor->slug = static::generateUniqueSlug($visitor->name, $visitor->id);
            }
        });
    }

    /**
     * Generate unique slug from name
     */
    protected static function generateUniqueSlug(string $title, $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Generate unique secret code (6 alphanumeric)
     */
    protected static function generateUniqueSecretCode($length = 6): string
    {
        do {
            // Generate random string with letters and numbers
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (static::where('secret_code', $code)->exists());

        return $code;
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function progress()
    {
        return $this->hasMany(VisitorProgress::class);
    }

    public function getProgressPercentageAttribute()
    {
        return $this->progress ?? 0;
    }

    public function getCompletedResourcesCountAttribute()
    {
        return $this->progress()->where('is_completed', true)->count();
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    // public function getAuthIdentifierName()
    // {
    //     return 'secret_code';
    // }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    // public function getAuthIdentifier()
    // {
    //     return $this->{$this->getAuthIdentifierName()};
    // }

    /**
     * Get the password for the user (not used but required by interface)
     *
     * @return string
     */
    // public function getAuthPassword()
    // {
    //     return $this->secret_code;
    // }
}
