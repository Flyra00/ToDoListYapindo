<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TodoController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the authenticated user's todos.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $selectedCategory = $request->query('category');
        $selectedPriority = $request->query('priority');

        $query = $request->user()->todos()->latest();

        if ($status === 'active') {
            $query->where('completed', false);
        } elseif ($status === 'completed') {
            $query->where('completed', true);
        }

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        if ($selectedPriority && in_array($selectedPriority, ['low', 'medium', 'high'])) {
            $query->where('priority', $selectedPriority);
        }

        $todos = $query->paginate(10)->withQueryString();

        $categories = $request->user()->todos()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('todos.index', [
            'todos' => $todos,
            'status' => $status,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'selectedPriority' => $selectedPriority,
        ]);
    }

    /**
     * Show the form for creating a new todo.
     */
    public function create(Request $request): View
    {
        $categories = $request->user()->todos()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('todos.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(StoreTodoRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['completed'] = false;

        $request->user()->todos()->create($validated);

        return redirect()
            ->route('todos.index')
            ->with('status', 'Todo berhasil dibuat.');
    }

    /**
     * Display the specified todo.
     */
    public function show(Todo $todo): View
    {
        $this->authorize('view', $todo);

        return view('todos.show', [
            'todo' => $todo,
        ]);
    }

    /**
     * Show the form for editing the specified todo.
     */
    public function edit(Request $request, Todo $todo): View
    {
        $this->authorize('update', $todo);

        $categories = $request->user()->todos()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('todos.edit', [
            'todo' => $todo,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified todo in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo): RedirectResponse
    {
        $this->authorize('update', $todo);

        $todo->update($request->validated());

        return redirect()
            ->route('todos.index')
            ->with('status', 'Todo berhasil diperbarui.');
    }

    /**
     * Toggle the completion status of the specified todo.
     */
    public function toggle(Todo $todo): RedirectResponse
    {
        $this->authorize('update', $todo);

        $todo->update([
            'completed' => ! $todo->completed,
        ]);

        $message = $todo->completed
            ? 'Todo telah ditandai sebagai selesai.'
            : 'Todo dikembalikan ke status aktif.';

        return back()->with('status', $message);
    }

    /**
     * Remove the specified todo from storage.
     */
    public function destroy(Todo $todo): RedirectResponse
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return redirect()
            ->route('todos.index')
            ->with('status', 'Todo berhasil dihapus.');
    }
}
