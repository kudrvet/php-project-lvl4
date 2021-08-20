<?php

namespace App\Models;

use App\Traits\AccessCreatedAt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task[] $tasks
 * @property-read int|null $tasks_count
 */

class TaskStatus extends Model
{
    use HasFactory;
    use AccessCreatedAt;

    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id', 'id');
    }
}
