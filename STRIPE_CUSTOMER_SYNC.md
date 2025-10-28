# Stripe Customer Sync Documentation

## Overview

The Stripe Customer Sync system allows users to synchronize their local user data with Stripe customer records. This ensures consistency between your Laravel application and Stripe's customer database.

## Features

### Web Interface
- **Settings Page**: Accessible at `/settings/stripe`
- **Create Customer**: Create new Stripe customer record
- **Update Customer**: Sync local changes to existing Stripe customer
- **Delete Customer**: Remove Stripe customer and clear local data
- **View Data**: Display both local and Stripe customer information

### Console Commands
- **Bulk Sync**: `php artisan stripe:sync-customers`
- **Create Only**: `php artisan stripe:sync-customers --create`
- **Update Only**: `php artisan stripe:sync-customers --update`
- **Single User**: `php artisan stripe:sync-customers --user=123`

## Database Schema

The system uses Laravel Cashier's standard customer columns:

```sql
ALTER TABLE users ADD COLUMN stripe_id VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN pm_type VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN pm_last_four VARCHAR(4) NULL;
ALTER TABLE users ADD COLUMN trial_ends_at TIMESTAMP NULL;
```

## Usage

### Via Web Interface

1. Navigate to Settings → Stripe Sync
2. Click "Create Stripe Customer" if you don't have one
3. Use "Update Customer Data" to sync changes
4. Use "Refresh Data" to reload information from Stripe

### Via Command Line

```bash
# Create Stripe customers for all users without stripe_id
php artisan stripe:sync-customers --create

# Update all existing Stripe customers
php artisan stripe:sync-customers --update

# Sync specific user
php artisan stripe:sync-customers --user=123 --create --update

# See all options
php artisan stripe:sync-customers --help
```

## Error Handling

- All operations are wrapped in try-catch blocks
- Errors are logged to Laravel's logging system
- User-friendly error messages are displayed in the UI
- Command-line operations show detailed progress and error reports

## Security Considerations

- All operations require user authentication
- Users can only manage their own Stripe customer data
- Stripe API calls use Laravel Cashier's secure implementation
- Sensitive data is never logged in plain text

## Integration with Payment System

This sync system works alongside the existing job payment system:

1. **Job Payments**: Create one-time checkout sessions for job posting fees
2. **Customer Sync**: Maintain consistent customer records for billing history
3. **Payment Tracking**: Store payment records in the `payments` table

## Troubleshooting

### Common Issues

1. **"No Stripe customer found"**
   - Solution: Click "Create Stripe Customer" in settings

2. **"Sync failed: Customer does not exist"**
   - Solution: Delete local stripe_id and create new customer

3. **Permission errors**
   - Solution: Ensure user is authenticated and has proper permissions

### Logs

Check Laravel logs for detailed error information:
```bash
tail -f storage/logs/laravel.log | grep -i stripe
```

## API Methods

### Livewire Component Methods

```php
// Load customer data from Stripe
$this->loadCustomerData();

// Create or update Stripe customer
$this->syncWithStripe();

// Delete Stripe customer
$this->deleteStripeCustomer();
```

### Console Command Options

```php
// Available options
--create    // Create customers for users without stripe_id
--update    // Update existing customers
--user=ID   // Target specific user
```

## Configuration

Ensure your Stripe credentials are set in `.env`:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

## Testing

The system includes comprehensive error handling and logging for easy testing and debugging.

```bash
# Test sync for a single user
php artisan stripe:sync-customers --user=1 --create

# Check logs
tail -f storage/logs/laravel.log
```
