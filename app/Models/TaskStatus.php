<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaskStatus
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\TaskStatusFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class TaskStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id', 'id');
    }
}
