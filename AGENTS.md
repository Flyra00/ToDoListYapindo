# AGENTS.md — Laravel Todo List Technical Test

## 1. Project Objective

Build and maintain a simple, professional, portable TODO List application for the Yapindo Jaya Abadi Developer Technical Test.

The application must use:

- Laravel (existing project)
- Laravel Breeze for authentication/login only
- Blade + Alpine.js for the frontend
- PostgreSQL as the primary database
- Docker / Docker Compose for a portable database environment
- Laravel Eloquent ORM
- Laravel migrations
- Laravel validation and authorization

The application must allow an authenticated user to:

1. View a list of their Todo items
2. Create a new Todo
3. View Todo details
4. Edit a Todo
5. Mark a Todo as completed/uncompleted
6. Delete a Todo
7. Filter Todos by status

All Todo data must be persisted in PostgreSQL.

The implementation must remain simple, maintainable, and easy to move to another machine or server.

---

## 2. Source of Requirements

The original technical-test requirement is:

> Mini Application: Build back-end & front-end “TODO List.” User can “Create new todo”, “Update new todo telah selesai”, and “Delete todo”. All data must be sent to the database.

Assessment criteria:

- Application works properly
- Uses a modern framework
- Data is integrated with a database

Submission requirements:

- Submit the project to GitHub
- Invite GitHub user `anggi117`
- Include a README explaining setup and how to run the project
- Completion should be communicated to `anggidputra567@gmail.com`

Additional project requirements requested for this implementation:

- Todo has `title` and `description`
- Todo status is represented by a boolean `completed`
- CRUD pages should be separated rather than putting every operation on one page
- Include a Todo list page and filtering
- Keep the UI simple and professional
- PostgreSQL must run through Docker
- Database should be easy to migrate/move
- Include and preserve the ERD described in this document

---

## 3. Non-Negotiable Rules

### 3.1 Inspect Before Changing

Before modifying the project:

1. Inspect the existing Laravel version.
2. Inspect `composer.json`.
3. Inspect `package.json`.
4. Inspect the existing Breeze installation.
5. Determine whether Breeze uses Blade/Alpine and preserve the existing setup.
6. Inspect existing routes.
7. Inspect existing controllers.
8. Inspect existing models.
9. Inspect existing migrations.
10. Inspect `.env.example`.
11. Inspect Docker-related files.
12. Inspect the current database configuration.
13. Inspect existing frontend assets and styling.
14. Check whether Todo-related functionality already exists.

Do not blindly overwrite existing project files.

If functionality already exists and is suitable, extend or refactor it instead of duplicating it.

---

## 4. Breeze Scope

Laravel Breeze is used primarily for authentication.

Keep the existing Breeze authentication functionality, including as applicable:

- Login
- Logout
- Authentication middleware
- User model
- Password handling
- Session/authentication mechanisms

Do not replace Breeze with another authentication system.

Do not unnecessarily redesign the authentication pages.

Todo functionality should be implemented separately from the authentication implementation.

The expected flow is:

```text
Guest
  |
  v
Login (Breeze)
  |
  v
Authenticated User
  |
  v
Todo Application
```

All Todo routes must require authentication.

---

## 5. Functional Requirements

### 5.1 Todo List

Provide a dedicated Todo index page.

Example route:

```text
GET /todos
```

The page should:

- Display the authenticated user's Todos
- Display title
- Display description or a reasonable excerpt
- Display completion status
- Provide an action to edit
- Provide an action to delete
- Provide an action to mark completed/uncompleted
- Provide filtering

Recommended filters:

```text
All
Active
Completed
```

The UI must remain simple and readable.

---

### 5.2 Create Todo

Provide a dedicated create page.

Example:

```text
GET /todos/create
POST /todos
```

Fields:

```text
title
description
```

Requirements:

- `title` is required
- `description` may be nullable
- New Todos default to `completed = false`
- `user_id` must be assigned from the authenticated user
- Do not accept `user_id` from untrusted client input
- Validate input using Laravel validation

---

### 5.3 View Todo

Provide a dedicated detail page.

Example:

```text
GET /todos/{todo}
```

Display:

- Title
- Description
- Completion status
- Created date
- Updated date
- Edit action
- Delete action
- Complete/uncomplete action

---

### 5.4 Edit Todo

Provide a dedicated edit page.

Example:

```text
GET /todos/{todo}/edit
PUT/PATCH /todos/{todo}
```

The user must be able to edit:

- Title
- Description

Do not allow arbitrary modification of:

- `id`
- `user_id`
- timestamps
- ownership

Validate all submitted data.

---

### 5.5 Complete / Uncomplete Todo

Todo completion is represented by:

```text
completed BOOLEAN
```

Default:

```text
false
```

The user must be able to change:

```text
false -> true
true -> false
```

The implementation may use a dedicated endpoint such as:

```text
PATCH /todos/{todo}/toggle
```

or an equivalent Laravel RESTful design.

Use Alpine.js only where it provides useful interaction. Do not introduce unnecessary JavaScript complexity.

---

### 5.6 Delete Todo

Provide a delete action.

Example:

```text
DELETE /todos/{todo}
```

The user must only be able to delete their own Todo.

Use a confirmation interaction before destructive deletion where practical.

---

### 5.7 Filter Todo

Filtering must support at least:

```text
All
Active
Completed
```

Meaning:

```text
All       -> all Todos belonging to the authenticated user
Active    -> completed = false
Completed -> completed = true
```

Filtering can be implemented using query parameters, for example:

```text
/todos?status=all
/todos?status=active
/todos?status=completed
```

The filtering implementation must remain simple and maintainable.

---

# 6. Database Design

Use PostgreSQL.

Do not use SQLite as the primary application database.

The database schema must be created through Laravel migrations.

Do not manually require users to create tables using SQL scripts when migrations can provide the schema.

---

## 7. ERD

The minimum required entity relationship is:

```text
┌─────────────────────────────┐
│            users            │
├─────────────────────────────┤
│ PK id                       │
│    name                     │
│    email                    │
│    password                 │
│    ... Breeze fields        │
│    created_at                │
│    updated_at                │
└──────────────┬──────────────┘
               │
               │ 1
               │
               │
               │ N
┌──────────────▼──────────────┐
│            todos            │
├─────────────────────────────┤
│ PK id                       │
│ FK user_id                  │
│    title                    │
│    description              │
│    completed                 │
│    created_at                │
│    updated_at                │
└─────────────────────────────┘
```

Relationship:

```text
users 1 ─────────── N todos
```

Meaning:

- One User can have many Todos
- Each Todo belongs to exactly one User

Laravel relationship:

```php
// User.php
public function todos()
{
    return $this->hasMany(Todo::class);
}
```

```php
// Todo.php
public function user()
{
    return $this->belongsTo(User::class);
}
```

Recommended database definition:

```text
todos
--------------------------------
id              BIGINT PK
user_id         BIGINT FK
title           VARCHAR
description     TEXT NULL
completed       BOOLEAN DEFAULT FALSE
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

The foreign key should reference:

```text
users.id
```

Use an appropriate foreign-key deletion strategy. Prefer cascading deletion when consistent with the existing application's user lifecycle, because a Todo cannot exist without its owner.

---

# 8. Authorization and Data Isolation

This is mandatory.

A user must never be able to access another user's Todo.

For every Todo operation, verify ownership:

- List
- View
- Create
- Edit
- Update
- Toggle completion
- Delete

The Todo query should be scoped to the authenticated user wherever possible.

Preferred conceptual pattern:

```php
auth()->user()->todos()
```

Avoid insecure patterns such as:

```php
Todo::find($id)
```

followed by an operation without verifying ownership.

Use Laravel Policies or another clean Laravel authorization mechanism where appropriate.

The following must never be trusted from request input:

```text
user_id
owner_id
```

The owner must be derived from the authenticated session/user.

---

# 9. Laravel Architecture

Prefer standard Laravel conventions.

Expected components:

```text
app/
├── Models/
│   ├── User.php
│   └── Todo.php
│
├── Http/
│   ├── Controllers/
│   │   └── TodoController.php
│   │
│   └── Requests/
│       └── Todo-related Form Requests when beneficial
│
└── Policies/
    └── TodoPolicy.php
```

Routes:

```text
routes/
└── web.php
```

Views:

```text
resources/
└── views/
    └── todos/
        ├── index.blade.php
        ├── create.blade.php
        ├── show.blade.php
        └── edit.blade.php
```

Use route model binding where appropriate.

Do not create unnecessary services, repositories, packages, APIs, or architectural layers for this small technical test.

Keep the architecture proportional to the application.

---

# 10. Frontend Requirements

Use:

- Blade
- Alpine.js
- Existing Breeze frontend infrastructure where appropriate

Do not introduce:

- React
- Vue
- Inertia
- Livewire
- jQuery
- another CSS framework

unless the existing project already depends on them and removing them would cause unnecessary disruption.

The UI should be:

- Simple
- Clean
- Professional
- Responsive
- Easy to understand
- Appropriate for a developer technical test

Do not spend excessive effort creating a highly complex dashboard.

The goal is a functional application with good engineering practices.

---

# 11. Docker and PostgreSQL

PostgreSQL must be containerized.

Use Docker Compose where appropriate.

Expected conceptual architecture:

```text
┌─────────────────────────────┐
│        Docker Compose       │
├─────────────────────────────┤
│                             │
│  Laravel Application        │
│          │                  │
│          │ PostgreSQL       │
│          ▼                  │
│  ┌──────────────────────┐   │
│  │      PostgreSQL      │   │
│  │      Container       │   │
│  └──────────┬───────────┘   │
│             │               │
│             ▼               │
│      Named Volume           │
│                             │
└─────────────────────────────┘
```

The PostgreSQL data must use a persistent Docker volume.

Do not store PostgreSQL data inside the Git repository.

Do not commit database credentials.

Do not commit `.env`.

Use `.env.example` for required configuration.

---

# 12. Environment Configuration

Database configuration must be environment-driven.

Example:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=todo
DB_USERNAME=laravel
DB_PASSWORD=secret
```

The exact values must match the Docker Compose configuration.

Never hardcode production credentials in:

- PHP source code
- JavaScript
- Blade templates
- migrations
- GitHub repository
- README

If credentials are shown in documentation, they must be clearly identified as local development/example credentials.

---

# 13. Database Portability

One of the project priorities is easy database migration.

The project must be designed so that another developer can:

1. Clone the GitHub repository
2. Configure `.env`
3. Start Docker
4. Run Laravel migrations
5. Run the application
6. Obtain the required database schema without manually recreating tables

Required mechanisms:

```text
Laravel migrations
+
Docker Compose
+
PostgreSQL volume
+
.env.example
+
README
```

If database backup/restore documentation is added, prefer standard PostgreSQL tools such as:

```text
pg_dump
pg_restore
```

Do not build a custom database migration system.

---

# 14. Git and Repository Rules

Do not commit:

```text
.env
vendor/
node_modules/
database dumps containing real/private data
Docker volumes
credentials
secrets
```

Ensure `.gitignore` is appropriate.

Before declaring the project complete:

- Check `git status`
- Verify no secrets are staged
- Verify `.env` is ignored
- Verify required source files are tracked
- Verify migrations are tracked
- Verify Docker configuration is tracked
- Verify README is tracked

---

# 15. Testing Requirements

Before completion, test at minimum:

### Authentication

- Guest cannot access Todo pages
- Authenticated user can access Todo pages

### Create

- Valid Todo can be created
- Invalid Todo is rejected
- New Todo belongs to authenticated user
- New Todo starts as incomplete

### Read

- User can see their own Todos
- User cannot see another user's Todo

### Update

- User can edit their own Todo
- User cannot edit another user's Todo
- Validation works

### Completion

- Todo can be marked completed
- Completed Todo can be marked active again

### Delete

- User can delete their own Todo
- User cannot delete another user's Todo

### Filter

- All shows all user's Todos
- Active shows incomplete Todos
- Completed shows completed Todos

### Database

- Fresh migration works
- Application connects to PostgreSQL
- Data persists after restarting containers

---

# 16. Development Workflow for AI Agents

AI agents must follow this sequence.

## Phase 1 — Inspect

Inspect the existing project before editing.

Do not immediately write code.

Determine:

- Laravel version
- PHP version requirements
- Breeze setup
- Alpine setup
- Database configuration
- Docker configuration
- Existing application structure

## Phase 2 — Plan

Create a concise implementation plan.

The plan should identify:

- migrations
- models
- relationships
- policy/authorization
- controller
- routes
- views
- validation
- Docker changes
- tests
- README changes

## Phase 3 — Implement

Implement the smallest clean solution that satisfies the requirements.

Prefer existing Laravel functionality over installing packages.

## Phase 4 — Verify

Run appropriate:

```text
migration checks
tests
lint/static checks when available
application checks
Docker checks
```

Do not claim success based solely on code inspection.

## Phase 5 — Review

Review the implementation for:

- authorization vulnerabilities
- mass assignment vulnerabilities
- validation issues
- incorrect relationships
- broken routes
- incorrect database configuration
- unnecessary dependencies
- exposed credentials
- poor UX
- duplicated code

## Phase 6 — Documentation

Update README with:

- project description
- requirements
- installation
- environment setup
- Docker setup
- database setup
- migration instructions
- frontend build instructions
- application startup
- test instructions
- GitHub submission notes
- database portability information

---

# 17. Change Management Rules

Do not make unrelated changes.

Do not:

- rewrite the entire project
- replace the existing authentication system
- introduce unnecessary dependencies
- restructure unrelated application modules
- change existing functionality without a reason
- delete existing files without inspection
- overwrite configuration blindly

If an existing configuration conflicts with the requirements, make the smallest safe change.

If a major architectural change is necessary, explain why before making it.

---

# 18. Code Quality Rules

Follow Laravel conventions.

Prefer:

- dependency injection
- route model binding
- Eloquent relationships
- Form Requests when validation becomes substantial
- Policies for authorization
- mass-assignment protection
- named routes
- clear variable names
- small controllers
- reusable Blade components when useful

Avoid:

- raw SQL when Eloquent is sufficient
- duplicated authorization logic
- duplicated validation
- unnecessary abstraction
- giant controllers
- giant Blade files
- inline business logic scattered throughout views

The code should be understandable to another developer reviewing a technical-test submission.

---

# 19. Definition of Done

The project is NOT complete until all of the following are true:

- [ ] Laravel project runs
- [ ] Breeze authentication works
- [ ] Todo routes require authentication
- [ ] PostgreSQL works
- [ ] PostgreSQL runs through Docker
- [ ] Database uses Laravel migrations
- [ ] User-Todo relationship exists
- [ ] Todo has title
- [ ] Todo has description
- [ ] Todo has boolean completed status
- [ ] User can create Todo
- [ ] User can view Todo list
- [ ] User can view Todo details
- [ ] User can edit Todo
- [ ] User can mark Todo completed
- [ ] User can mark Todo active again
- [ ] User can delete Todo
- [ ] User can filter Todos
- [ ] Users cannot access other users' Todos
- [ ] Validation works
- [ ] Tests or equivalent verification pass
- [ ] Docker setup works from a clean environment
- [ ] Database persists through container restart
- [ ] `.env.example` is complete
- [ ] Secrets are not committed
- [ ] README is complete
- [ ] Git repository is clean and ready for submission

---

# 20. Final Audit Before Submission

Before saying "project completed", perform a final audit.

### Functional audit

Verify every required user action manually or through automated tests.

### Security audit

Especially verify:

```text
User A -> cannot access User B's Todo
User A -> cannot modify User B's Todo
User A -> cannot delete User B's Todo
```

### Database audit

Verify:

```text
Laravel
   |
   v
PostgreSQL
   |
   v
Docker volume
```

and verify that data survives a normal container restart.

### Repository audit

Check that:

- `.env` is not committed
- credentials are not committed
- migrations are committed
- Docker configuration is committed
- README is committed
- application source is committed

### Documentation audit

README must allow a new developer to understand:

```text
What the project is
        ↓
Requirements
        ↓
Installation
        ↓
Environment configuration
        ↓
Docker
        ↓
Database migration
        ↓
Run application
        ↓
Run tests
```

---

# 21. Important Priority

When making implementation decisions, use this priority order:

```text
1. Correctness
2. Security / authorization
3. Database integrity
4. Maintainability
5. Portability
6. Simplicity
7. UI polish
```

Do not sacrifice correctness or security merely to make the implementation shorter.

Do not over-engineer the project merely to make it look sophisticated.

This is a small technical test. A clean, working, secure, well-documented Laravel application is preferable to an unnecessarily complex architecture.

---

# 22. Expected Final Architecture

The expected final structure should be approximately:

```text
Laravel Todo Application
│
├── Authentication
│   └── Laravel Breeze
│
├── Frontend
│   ├── Blade
│   └── Alpine.js
│
├── Backend
│   ├── TodoController
│   ├── Todo Model
│   ├── Todo Policy
│   └── Validation
│
├── Database
│   └── PostgreSQL
│
├── Infrastructure
│   └── Docker Compose
│
├── Persistence
│   └── PostgreSQL Named Volume
│
├── Database Schema
│   ├── users
│   └── todos
│
└── Documentation
    └── README.md
```

The application should remain intentionally simple and easy to understand.
