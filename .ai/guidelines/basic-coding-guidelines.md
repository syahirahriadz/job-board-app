# Laravel Livewire Job Board Application Coding Guidelines

## General Guidelines

### 1. File and Class Naming
- Use PascalCase for class names: `JobController`, `ApplicationTable`
- Use kebab-case for view files: `job-list.blade.php`, `application-form.blade.php`
- Livewire component class names should end with the type: `JobList`, `ApplicationTable`
- Matching view files should use kebab case: `job-list.blade.php`, `application-table.blade.php`

### 2. Code Organization
- Keep Livewire components focused and single-responsibility
- Use dedicated folders for different types of components:
  ```
  app/
    Livewire/
      Jobs/
      Applications/
      Auth/
      Shared/
  ```

### 3. Livewire Component Structure
```php
class JobList extends Component
{
    // Properties at the top
    public $search = '';
    public $filters = [];
    
    // Lifecycle hooks next
    public function mount()
    {
        // Initialize component
    }
    
    // Methods grouped by functionality
    public function updatedSearch()
    {
        // Handle search updates
    }
    
    // Render method at the bottom
    public function render()
    {
        return view('livewire.job-list');
    }
}
```

## Blade Templates

### 1. Component Organization
```blade
<div>
    <!-- Header/Title Section -->
    <header>...</header>

    <!-- Filters/Search Section -->
    <div class="filters">...</div>

    <!-- Main Content -->
    <main>...</main>

    <!-- Modals/Overlays -->
    <x-modal>...</x-modal>
</div>
```

### 2. Styling Guidelines
- Use Tailwind CSS utility classes
- Group related utilities with @apply in CSS when repeated frequently
- Maintain dark mode support using dark: variant
- Use consistent spacing and sizing scales

### 3. Event Handling
```blade
<!-- Use wire:loading states -->
<button wire:click="save" wire:loading.attr="disabled">
    <span wire:loading.remove>Save</span>
    <span wire:loading>Saving...</span>
</button>

<!-- Use wire:key for lists -->
@foreach ($jobs as $job)
    <div wire:key="job-{{ $job->id }}">
        {{ $job->title }}
    </div>
@endforeach
```

## PHP Code Style

### 1. Method Naming
- Use camelCase for method names
- Use descriptive names that indicate action
- Prefix boolean methods with questions: `isValid()`, `hasPermission()`

```php
// Good
public function approveApplication()
public function markAsRead()

// Avoid
public function process()
public function handle()
```

### 2. Property Naming
- Use camelCase for properties
- Use descriptive names
- Prefix boolean properties with is/has/should

```php
public $isLoading = false;
public $shouldRefresh = true;
public $hasAttachments = false;
```

### 3. Actions and Events
- Use consistent naming for actions and events
- Prefix action methods with action verbs
- Use past tense for events

```php
// Actions
public function approveApplication()
public function rejectApplication()

// Events
public function applicationApproved()
public function applicationRejected()
```

## Database

### 1. Migration Guidelines
- Use meaningful table names in plural form
- Add foreign key constraints
- Include timestamps
- Document column purposes in comments

```php
Schema::create('job_applications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('job_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('status')->default('pending');
    $table->timestamps();
});
```

### 2. Model Relations
- Define relationships clearly
- Use appropriate relationship types
- Document complex relationships

```php
class Job extends Model
{
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
```

## Testing

### 1. Test Organization
- Group tests by feature
- Use descriptive test names
- Follow AAA pattern (Arrange, Act, Assert)

```php
class JobApplicationTest extends TestCase
{
    /** @test */
    public function user_can_apply_for_a_job()
    {
        // Arrange
        $user = User::factory()->create();
        $job = Job::factory()->create();

        // Act
        Livewire::actingAs($user)
            ->test(JobApply::class, ['job' => $job])
            ->call('submit');

        // Assert
        $this->assertTrue($job->applications()->where('user_id', $user->id)->exists());
    }
}
```

### 2. Test Coverage
- Test all Livewire component actions
- Test component state changes
- Test validation rules
- Test authorization policies

## Security

### 1. Authorization
- Use Laravel's authorization policies
- Check permissions in both component and blade
- Use middleware for route protection

```php
// In Component
public function mount(Job $job)
{
    $this->authorize('view', $job);
}

// In Blade
@can('update', $job)
    <button wire:click="edit">Edit</button>
@endcan
```

### 2. Validation
- Always validate user input
- Use form request validation for complex forms
- Handle validation errors gracefully

```php
protected $rules = [
    'application.name' => 'required|string|max:255',
    'application.email' => 'required|email',
    'application.resume' => 'required|file|mimes:pdf|max:10240',
];
```

## Performance

### 1. Query Optimization
- Eager load relationships
- Use pagination
- Cache expensive queries

```php
public function render()
{
    return view('livewire.job-list', [
        'jobs' => Job::with(['company', 'applications'])
            ->latest()
            ->paginate(10)
    ]);
}
```

### 2. Component Optimization
- Use wire:model.lazy for form inputs
- Debounce search inputs
- Use polling judiciously

```blade
<input 
    wire:model.lazy="search"
    wire:model.debounce.500ms="search"
    type="text"
>
```

## Git Workflow

### 1. Commit Messages
- Use conventional commits
- Be descriptive but concise
- Reference issues/tickets

```
feat: add job application status tracking
fix: resolve duplicate application submissions
docs: update installation instructions
```

### 2. Branch Naming
- Use feature/ for new features
- Use fix/ for bug fixes
- Use refactor/ for code improvements

```
feature/job-application-status
fix/duplicate-submissions
refactor/livewire-components
```

## Documentation

### 1. Code Comments
- Document complex logic
- Explain business rules
- Use PHPDoc blocks for methods

```php
/**
 * Process the job application and notify relevant parties.
 *
 * @param Application $application
 * @return void
 * @throws ApplicationException
 */
public function processApplication(Application $application)
{
    // Implementation
}
```

### 2. README
- Keep installation instructions updated
- Document environment requirements
- Include testing instructions

Remember to follow these guidelines consistently across the project to maintain code quality and developer productivity.
