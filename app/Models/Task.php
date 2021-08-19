<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status_id
 * @property int $created_by_id
 * @property int|null $assigned_to_id
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereAssignedToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $executor
 * @property-read \App\Models\TaskStatus|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Label[] $labels
 * @property-read int|null $labels_count
 * @method static \Database\Factories\TaskFactory factory(...$parameters)
 */
class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = ['name', 'description', 'status_id', 'assigned_to_id', 'created_by_id', 'updated_at', 'created_at'];

    public function status()
    {
        return $this->hasOne(TaskStatus::class, 'id', 'status_id');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }

    public function executor()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'task_label');
    }
}
