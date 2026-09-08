<?php

namespace App\Models;

use Database\Factories\TodoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'description', 'category', 'priority', 'due_date', 'completed'])]
class Todo extends Model
{
    /** @use HasFactory<TodoFactory> */
    use HasFactory;

    /**
     * Get the user that owns the todo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed' => 'boolean',
        ];
    }

    /**
     * Determine if the todo is overdue.
     */
    public function isOverdue(): bool
    {
        return ! $this->completed && $this->due_date !== null && $this->due_date->isPast() && ! $this->due_date->isToday();
    }

    /**
     * Determine if the todo is due today.
     */
    public function isDueToday(): bool
    {
        return ! $this->completed && $this->due_date !== null && $this->due_date->isToday();
    }
}
