<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_todo_pages(): void
    {
        $todo = Todo::factory()->create();

        $this->get(route('todos.index'))->assertRedirect(route('login'));
        $this->get(route('todos.create'))->assertRedirect(route('login'));
        $this->post(route('todos.store'), ['title' => 'Test', 'priority' => 'medium'])->assertRedirect(route('login'));
        $this->get(route('todos.show', $todo))->assertRedirect(route('login'));
        $this->get(route('todos.edit', $todo))->assertRedirect(route('login'));
        $this->put(route('todos.update', $todo), ['title' => 'Test', 'priority' => 'medium'])->assertRedirect(route('login'));
        $this->patch(route('todos.toggle', $todo))->assertRedirect(route('login'));
        $this->delete(route('todos.destroy', $todo))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_todo_list(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->for($user)->create([
            'title' => 'Learn Laravel 13',
            'category' => 'Belajar',
            'priority' => 'high',
        ]);

        $response = $this->actingAs($user)->get(route('todos.index'));

        $response->assertOk();
        $response->assertSee('Learn Laravel 13');
        $response->assertSee('Belajar');
        $response->assertSee('High');
    }

    public function test_user_cannot_view_other_users_todos(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $todoA = Todo::factory()->for($userA)->create(['title' => 'User A Secret Task']);

        // User B cannot see User A's todo in index
        $indexResponse = $this->actingAs($userB)->get(route('todos.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee('User A Secret Task');

        // User B cannot view User A's todo detail directly (403 Forbidden)
        $detailResponse = $this->actingAs($userB)->get(route('todos.show', $todoA));
        $detailResponse->assertForbidden();
    }

    public function test_user_can_create_todo_with_category_priority_and_due_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('todos.store'), [
            'title' => 'New Awesome Todo',
            'description' => 'Detailed descriptions here',
            'category' => 'Pekerjaan',
            'priority' => 'high',
            'due_date' => '2026-10-01',
        ]);

        $response->assertRedirect(route('todos.index'));
        $todo = Todo::first();
        $this->assertNotNull($todo);
        $this->assertEquals($user->id, $todo->user_id);
        $this->assertEquals('New Awesome Todo', $todo->title);
        $this->assertEquals('Detailed descriptions here', $todo->description);
        $this->assertEquals('Pekerjaan', $todo->category);
        $this->assertEquals('high', $todo->priority);
        $this->assertEquals('2026-10-01', $todo->due_date->format('Y-m-d'));
        $this->assertFalse($todo->completed);
    }

    public function test_todo_requires_title_to_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('todos.store'), [
            'title' => '',
            'priority' => 'medium',
            'description' => 'Without title',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('todos', 0);
    }

    public function test_user_can_update_own_todo(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->for($user)->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'priority' => 'low',
        ]);

        $response = $this->actingAs($user)->put(route('todos.update', $todo), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'category' => 'Pribadi',
            'priority' => 'high',
            'due_date' => '2026-12-31',
        ]);

        $response->assertRedirect(route('todos.index'));
        $todo->refresh();
        $this->assertEquals('Updated Title', $todo->title);
        $this->assertEquals('Updated Description', $todo->description);
        $this->assertEquals('Pribadi', $todo->category);
        $this->assertEquals('high', $todo->priority);
        $this->assertEquals('2026-12-31', $todo->due_date->format('Y-m-d'));
    }

    public function test_user_cannot_update_other_users_todo(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $todoA = Todo::factory()->for($userA)->create(['title' => 'Original User A Title']);

        $response = $this->actingAs($userB)->put(route('todos.update', $todoA), [
            'title' => 'Hacked by User B',
            'priority' => 'high',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('todos', [
            'id' => $todoA->id,
            'title' => 'Original User A Title',
        ]);
    }

    public function test_user_can_toggle_todo_completion(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->for($user)->create(['completed' => false]);

        // Toggle to true
        $this->actingAs($user)->patch(route('todos.toggle', $todo));
        $this->assertTrue($todo->fresh()->completed);

        // Toggle back to false
        $this->actingAs($user)->patch(route('todos.toggle', $todo));
        $this->assertFalse($todo->fresh()->completed);
    }

    public function test_user_cannot_toggle_other_users_todo(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $todoA = Todo::factory()->for($userA)->create(['completed' => false]);

        $response = $this->actingAs($userB)->patch(route('todos.toggle', $todoA));
        $response->assertForbidden();
        $this->assertFalse($todoA->fresh()->completed);
    }

    public function test_user_can_delete_own_todo(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('todos.destroy', $todo));

        $response->assertRedirect(route('todos.index'));
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_user_cannot_delete_other_users_todo(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $todoA = Todo::factory()->for($userA)->create();

        $response = $this->actingAs($userB)->delete(route('todos.destroy', $todoA));

        $response->assertForbidden();
        $this->assertDatabaseHas('todos', ['id' => $todoA->id]);
    }

    public function test_user_can_filter_todos_by_status(): void
    {
        $user = User::factory()->create();

        $activeTodo = Todo::factory()->for($user)->create([
            'title' => 'Active Task A',
            'completed' => false,
        ]);

        $completedTodo = Todo::factory()->for($user)->create([
            'title' => 'Completed Task B',
            'completed' => true,
        ]);

        // Status All
        $allResponse = $this->actingAs($user)->get(route('todos.index', ['status' => 'all']));
        $allResponse->assertSee('Active Task A');
        $allResponse->assertSee('Completed Task B');

        // Status Active
        $activeResponse = $this->actingAs($user)->get(route('todos.index', ['status' => 'active']));
        $activeResponse->assertSee('Active Task A');
        $activeResponse->assertDontSee('Completed Task B');

        // Status Completed
        $completedResponse = $this->actingAs($user)->get(route('todos.index', ['status' => 'completed']));
        $completedResponse->assertDontSee('Active Task A');
        $completedResponse->assertSee('Completed Task B');
    }
}
