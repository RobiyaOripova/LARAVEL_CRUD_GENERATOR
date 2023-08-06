<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This is the model class for table "roles".
 *
 * @property int $user_id
 * @property string $role
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = ['user_id', 'role', 'deleted_at', 'created_at', 'updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
