# Email Marketing Module for Juzaweb

The Email Marketing Module is a comprehensive solution for managing email marketing campaigns, email automation, and performance tracking. The module is designed with Juzaweb's modular architecture and supports multi-tenancy.

## ✨ Key Features

### 📧 Campaign Management
- **Create and manage campaigns**: Create, edit, and delete email campaigns
- **Two sending types**:
  - `Manual`: Send manually on demand
  - `Auto`: Send automatically based on automation rules
- **Campaign status**: Draft → Scheduled → Sending → Sent/Paused
- **Scheduled sending**: Schedule campaigns to send automatically
- **Batch processing**: Bulk sending with Laravel Queue system for optimized performance

### 📬 Subscriber Management
- **Import/Export subscribers**: Manage subscriber lists
- **Subscriber status**: Subscribed, Unsubscribed, Pending
- **Segmentation**: Group subscribers by different criteria
- **Auto unsubscribe**: Unsubscribe links are automatically generated in emails

### 🎨 Email Templates
- **Template system**: Create and manage reusable email templates
- **Dynamic content**: Support for variables and personalization
- **Responsive design**: Responsive templates for all devices

### 🎯 Email Automation
- **Automation Rules**: Create rules for automatic email sending
- **Trigger system**: Extensible trigger system
- **Built-in triggers**:
  - `user_registered`: When user registers
  - `user_birthday`: User birthday
  - `member_registered`: When member registers
- **Delay support**: Support for delayed email sending (in hours)
- **Conditions**: Conditions for triggering automation

### 📊 Tracking & Analytics
- **Email Open Tracking**: Track email opens
- **Click Tracking**: Track clicks on links in emails
- **Real-time statistics**: Real-time views and clicks statistics
- **Campaign performance**: Performance reports for each campaign
- **User-agent tracking**: Track devices and browsers

### ⚡ Batch Processing
- **Chunked sending**: Split email sending into batches
- **Progress tracking**: Real-time progress tracking
- **Failure handling**: Error handling and automatic retry
- **Cancellation support**: Ability to cancel ongoing campaigns
- **Multiple batches**: Split into multiple batches for performance optimization

### 🔧 Multi-tenant Support
- **Website isolation**: Each website has separate data
- **Networkable trait**: Automatic filtering by website_id
- **Shared infrastructure**: Share codebase across multiple websites

## 🏗️ Technical Architecture

### Database Schema
- **UUID Primary Keys**: Use UUID instead of auto-increment
- **Enum Support**: Use PHP 8.1 backed enums for status fields
- **JSON Columns**: Store conditions and metadata as JSON
- **Proper Relationships**: Optimized foreign keys and indexes

### Queue System
- **Laravel Batches**: Use `Bus::batch()` for campaign sending
- **Background Processing**: Don't block UI when sending bulk emails
- **Retry Logic**: Automatic retry on errors
- **Monitoring**: Track progress and status of jobs

### Mail Content Processing
- **Link Tracking**: Automatically wrap links with tracking URLs
- **Open Tracking**: Insert tracking pixel into emails
- **Unsubscribe Footer**: Automatically add unsubscribe link
- **Content Transformation**: Process email content before sending

### Security
- **Signed URLs**: Use signed routes for unsubscribe
- **Encrypted Parameters**: Encrypt URL parameters for tracking
- **CSRF Protection**: Protect forms with CSRF tokens

## 📦 Installation

1. Copy the module to `juzaweb/modules/` directory
2. Run migrations:
```bash
php artisan migrate
```
3. Publish assets (if needed):
```bash
php artisan module:publish-assets EmailMarketing
```

## ⚙️ Configuration

Configuration file: `src/config/email-marketing.php`

```php
return [
    // Number of jobs per batch when sending campaigns
    'batch_size' => 100,
    
    // Default delay for automation (minutes)
    'default_delay_minutes' => 0,
    
    // Maximum retry attempts
    'max_retry_attempts' => 3,
];
```

## 🔧 Usage

### Creating a Campaign
```php
$campaign = Campaign::create([
    'name' => 'Welcome Campaign',
    'subject' => 'Welcome to our service!',
    'content' => '<h1>Welcome!</h1><p>Thank you for joining us.</p>',
    'template_id' => $template->id,
    'send_type' => CampaignSendTypeEnum::MANUAL,
    'status' => CampaignStatusEnum::DRAFT,
]);
```

### Adding Custom Automation Trigger
```php
// In Service Provider
use Juzaweb\Modules\EmailMarketing\Facades\AutomationTrigger;

AutomationTrigger::register('order_completed', [
    'label' => 'Order Completed',
    'description' => 'Triggered when user completes an order',
    'delay_support' => true,
]);
```

### Sending Campaign
```php
use Juzaweb\Modules\EmailMarketing\Services\CampaignService;

$service = app(CampaignService::class);
$result = $service->execute($campaign);
```

## 🛣️ Routes

### Admin Routes
- `GET /admin/email-marketing/campaigns` - Campaign list
- `GET /admin/email-marketing/subscribers` - Subscriber management
- `GET /admin/email-marketing/segments` - Segment management
- `GET /admin/email-marketing/email-templates` - Template management

### Public Routes
- `GET /t/o/{campaign_id}/{subscriber_id}.png` - Email open tracking
- `GET /t/c` - Link click tracking
- `GET /unsubscribe/{subscriber}` - Unsubscribe page

## 🧪 Testing

Run tests:
```bash
php artisan test tests/Feature/EmailMarketing
php artisan test tests/Unit/EmailMarketing
```

## 📝 Requirements

- PHP >= 8.2
- Laravel >= 11.0
- Juzaweb CMS
- MySQL with JSON column support
- Redis or database queue driver

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This module uses the same license as Juzaweb CMS.