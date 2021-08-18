<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Tests\TestCase;

class TaskStatusesTest extends TestCase
{
    /** @var $user User  */
    public $user ;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        $this->assertDatabaseCount(TaskStatus::class, 0);

        $statuses = TaskStatus::factory()
            ->count(5)
            ->create()
            ->pluck('name')
            ->all();

        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200);
        $response->assertSee($statuses);
        $this->assertDatabaseCount(TaskStatus::class, 5);
    }

    public function testCreate()
    {

        $this->actingAs($this->user)->get(route('task_statuses.create'))
            ->assertStatus(200);
    }


    public function testStore()
    {
        $this->assertDatabaseCount(TaskStatus::class, 0);
        $data = ['name' => 'new'];
        $response = $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), $data);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas(TaskStatus::class, $data);

        $response = $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), $data);

        $this->assertDatabaseCount(TaskStatus::class, 1);
    }

    public function testEdit()
    {
        /** @var $status TaskStatus */
        $status = TaskStatus::factory()->create();
        $this
            ->actingAs($this->user)
            ->get(route('task_statuses.edit', [$status->id]))
            ->assertStatus(200);
    }

    public function testUpdate()
    {
        /** @var $status TaskStatus */
        $status = TaskStatus::factory()->create();
        $updatedData = ['name' => 'updated'];
        $response = $this
            ->actingAs($this->user)
            ->patch(route('task_statuses.update', [$status->id]), $updatedData);
        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseCount(TaskStatus::class, 1);
        $this->assertDatabaseHas(TaskStatus::class, $updatedData);
    }

    public function testDestroy()
    {
        /** @var $status TaskStatus */
        $status = TaskStatus::factory()->create();
        $this->assertDatabaseCount(TaskStatus::class, 1);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('task_statuses.destroy', [$status->id]));
        $response->assertStatus(302);
        $this->assertDatabaseCount(TaskStatus::class, 0);
    }

    public function testDestroyWithExistedTask()
    {
        /** @var $status TaskStatus */
        $status = TaskStatus::factory()->create();
        $this->assertDatabaseCount(TaskStatus::class, 1);

        $taskData = [
            'name'           => 'test',
            'description'    => 'test description',
            'status_id'      => $status->id,
            'created_by_id'  => $this->user->id
        ];

        /** @var $task Task */
        $task = Task::create($taskData);


        $response = $this
            ->actingAs($this->user)
            ->delete(route('task_statuses.destroy', [$status->id]));

        $this->assertDatabaseCount(TaskStatus::class, 1);

        $response->assertStatus(302);

        $expectedFlash = __('Не удалось удалить статус');
        $this->followRedirects($response)->assertSeeText($expectedFlash);
    }
}