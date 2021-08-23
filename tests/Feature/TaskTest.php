<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Faker\Factory;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskTest extends TestCase
{
    /** @var $executor User  */
    public $executor ;

    /** @var $creator User  */
    public $creator;

    /** @var  $status TaskStatus  */
    public $status;

    /** @var $label Label  */
    public $label;

    public $taskData;


    public function setUp(): void
    {
        parent::setUp();
        $this->creator = User::factory()->create();
        $this->executor = User::factory()->create();

        $this->status = TaskStatus::factory()->create();
        $this->label = Label::factory()->create();
        $this->taskData = [
            'name'           => 'test',
            'description'    => 'test description',
            'status_id'      => $this->status->id,
            'assigned_to_id' => $this->executor->id,
            'created_by_id'  => $this->creator->id,
        ];
    }

    public function testIndex()
    {
//        $status2 = TaskStatus::factory()->create();
//        $executor2 = User::factory()->create();
//        $creator2 = User::factory()->create();
//
//        $task1 = Task::factory([''])
        $this->assertDatabaseCount(Task::class, 0);
        /** @var Task $task */
        $task = Task::create($this->taskData);

        $this->assertDatabaseCount(Task::class, 1);
        $response = $this->get(route('tasks.index'));
        $response
            ->assertStatus(200)
            ->assertSeeText(Arr::except($this->taskData, 'description'));
    }

    public function testShow()
    {
        $task = Task::create($this->taskData);

        $dataToSee = [$task->name, $task->description, $task->status->name];
        $response = $this->get(route('tasks.show', [$task->id]));
        $response
            ->assertStatus(200)
            ->assertSeeText($dataToSee);
    }

    public function testCreate()
    {
        $this
            ->actingAs($this->creator)
            ->get(route('tasks.create'))
            ->assertStatus(200);
    }


    public function testStore()
    {
        $this->assertDatabaseCount(Task::class, 0);
        $this->assertDatabaseCount('task_label', 0);
        $this->assertDatabaseCount(Label::class, 1);

        $postData = array_merge($this->taskData, ['labels' => [$this->label->id]]);
        $response = $this
            ->actingAs($this->creator)
            ->post(route('tasks.store'), $postData);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas(Task::class, $this->taskData);
        $this->assertDatabaseCount('task_label', 1);
    }

    public function testEdit()
    {
        /** @var $task Task */

        $task = Task::create($this->taskData);
        $task->labels()->save($this->label);
        $task->refresh();

        $dataToSee = [$task->name, $task->description, $task->status->name, $task->executor->name, $this->label->name];
        $response = $this
            ->actingAs($this->creator)
            ->get(route('tasks.edit', [$task->id]));
        $response
            ->assertStatus(200)
            ->assertSee($dataToSee);
    }

    public function testUpdate()
    {
        $this->assertDatabaseCount(Task::class, 0);

        $task = Task::create($this->taskData);

        $this->assertDatabaseHas(Task::class, $this->taskData);

        $updatedData = ['name' => 'new', 'description' => 'new descr'];

        $postData = array_merge($this->taskData, $updatedData, ['labels' => [$this->label->id]]);

        $response = $this
            ->actingAs($this->creator)
            ->patch(route('tasks.update', [$task->id]), $postData);
        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseCount(Task::class, 1);
        $this->assertDatabaseHas(Task::class, $updatedData);
    }

    public function testDestroy()
    {
        $this->assertDatabaseCount(Task::class, 0);

        /** @var $task Task */
        $task = Task::create($this->taskData);

        $response = $this
            ->actingAs($this->creator)
            ->delete(route('tasks.destroy', [$task->id]));

        $response->assertStatus(302);
        $this->assertDatabaseCount(Task::class, 0);
    }

    public function testDestroyForeignTask()
    {
        $this->assertDatabaseCount(Task::class, 0);

        /** @var $task Task */
        $task = Task::create($this->taskData);

        $response = $this
            ->actingAs($this->executor)
            ->delete(route('tasks.destroy', [$task->id]));

        $this->assertDatabaseCount(Task::class, 1);
        $response->assertStatus(403);
    }
}
