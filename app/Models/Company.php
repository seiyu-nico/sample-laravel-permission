<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_companies');
    }

    /**
     * @return HasOne<Hoge, $this>
     */
    public function hoge(): HasOne
    {
        return $this->hasOne(Hoge::class);
    }

    /**
     * @return HasOne<Piyo, $this>
     */
    public function piyo(): HasOne
    {
        return $this->hasOne(Piyo::class);
    }
}
