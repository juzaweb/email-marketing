# Email Automation Trigger Registry

## Overview

The Email Automation module uses a **Singleton Registry Pattern** to manage trigger types instead of hardcoded enums. This allows any module to register custom automation triggers.

## Core Components

### 1. AutomationTriggerRegistry (Singleton)
**File:** [`Support/AutomationTriggerRegistry.php`](file:///Users/dev/projects/test/modules/EmailMarketing/Support/AutomationTriggerRegistry.php)

Central registry managing all automation triggers.

**Methods:**
- `register(string $key, array $config)` - Register a new trigger
- `all()` - Get all registered triggers
- `get(string $key)` - Get specific trigger
- `has(string $key)` - Check if trigger exists
- `keys()` - Get all trigger keys
- `labels()` - Get key-value pairs for dropdown
- `unregister(string $key)` - Remove a trigger

### 2. AutomationTrigger Facade
**File:** [`Facades/AutomationTrigger.php`](file:///Users/dev/projects/test/modules/EmailMarketing/Facades/AutomationTrigger.php)

Provides easy access to the registry.

### 3. Service Provider Registration
**File:** [`Providers/EmailMarketingServiceProvider.php`](file:///Users/dev/projects/test/modules/EmailMarketing/Providers/EmailMarketingServiceProvider.php)

- Binds singleton in `register()` method
- Registers default triggers in `boot()` method

---

## Default Triggers

6 default triggers are registered:

| Key | Label | Delay Support |
|-----|-------|---------------|
| `user_registered` | User Registered | ✅ |
| `user_registered_7_days` | User Registered (7 Days After) | ❌ |
| `user_birthday` | User Birthday | ✅ |
| `member_registered` | Member Registered | ✅ |
| `member_registered_7_days` | Member Registered (7 Days After) | ❌ |
| `member_birthday` | Member Birthday | ✅ |

---

## Usage Examples

### Registering a Custom Trigger

**In your module's Service Provider:**

```php
use Juzaweb\Modules\EmailMarketing\Facades\AutomationTrigger;

public function boot(): void
{
    AutomationTrigger::register('order_completed', [
        'label' => __('ecommerce::automation.order_completed'),
        'description' => __('ecommerce::automation.order_completed_desc'),
        'event' => \App\Events\OrderCompleted::class,
        'delay_support' => true,
    ]);
    
    AutomationTrigger::register('cart_abandoned', [
        'label' => __('ecommerce::automation.cart_abandoned'),
        'description' => __('ecommerce::automation.cart_abandoned_desc'),
        'delay_support' => false,
    ]);
}
```

### Getting All Triggers

```php
use Juzaweb\Modules\EmailMarketing\Facades\AutomationTrigger;

// Get all triggers
$triggers = AutomationTrigger::all();

// Get trigger keys only
$keys = AutomationTrigger::keys();
// Returns: ['user_registered', 'user_birthday', ...]

// Get labels for dropdown
$labels = AutomationTrigger::labels();
// Returns: ['user_registered' => 'User Registered', ...]
```

### Checking Trigger Existence

```php
if (AutomationTrigger::has('user_birthday')) {
    $trigger = AutomationTrigger::get('user_birthday');
    // Do something with trigger
}
```

### Using in Form Validation

```php
use Illuminate\Validation\Rule;
use Juzaweb\Modules\EmailMarketing\Facades\AutomationTrigger;

public function rules(): array
{
    return [
        'trigger_type' => [
            'required',
            Rule::in(AutomationTrigger::keys()),
        ],
    ];
}
```

### Using in Blade Views

```blade
<select name="trigger_type" class="form-control">
    @foreach(app('email.automation.trigger')->labels() as $key => $label)
        <option value="{{ $key }}">{{ $label }}</option>
    @endforeach
</select>
```

---

## Trigger Configuration Schema

When registering a trigger, you can provide:

```php
[
    'label' => 'Display Name',              // Required
    'description' => 'Detailed description', // Optional
    'event' => \App\Events\SomeEvent::class, // Optional - Event class
    'delay_support' => true,                 // Optional - Default: true
    // Any custom metadata...
]
```

---

## Benefits

✅ **Extensible** - Other modules can add their own triggers  
✅ **No Migration Changes** - Uses string column instead of enum  
✅ **Type Safe** - Validation ensures only registered triggers are used  
✅ **I18n Ready** - Supports translations via `__()` helper  
✅ **Clean API** - Facade provides easy access  

---

## Database Schema

The `trigger_type` column in `email_automation_rules` table is now a **string** instead of enum, allowing dynamic registration:

```php
$table->string('trigger_type'); // Dynamic trigger type from registry
```

This means you can add new triggers without modifying the database schema.
