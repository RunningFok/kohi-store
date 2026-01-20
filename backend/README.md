# KohiStore - E-commerce Shopping Cart

A modern e-commerce application built with Laravel 11 and Vue.js 3, featuring authenticated shopping carts, low stock notifications, and daily sales reports.

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (default) or MySQL/PostgreSQL

### Installation

1. **Clone the repository and navigate to backend directory:**
   ```bash
   cd backend
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```

4. **Copy environment file:**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional - creates sample data):**
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets:**
   ```bash
   npm run build
   ```

9. **Start the development server:**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

10. **In a separate terminal, start the queue worker (required for low stock notifications):**
    ```bash
    php artisan queue:work
    ```

## 📧 Email Configuration (Gmail)

This app uses Gmail SMTP to send email notifications. Here's how to set it up:

### Step 1: Create a Gmail App Password

1. Go to your Google Account settings: https://myaccount.google.com/
2. Navigate to **Security** → **2-Step Verification** (enable it if not already enabled)
3. Go to **App passwords**: https://myaccount.google.com/apppasswords
4. Select **Mail** and **Other (Custom name)**
5. Enter "KohiStore" as the name
6. Click **Generate**
7. Copy the 16-character password (you'll use this in `.env`)

### Step 2: Configure .env File

Update your `.env` file with the following Gmail settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="KohiStore"

# Notification settings
ADMIN_EMAIL=admin@example.com
LOW_STOCK_THRESHOLD=20
```

**Important Notes:**
- Use your **Gmail address** for `MAIL_USERNAME` and `MAIL_FROM_ADDRESS`
- Use the **16-character app password** (not your regular Gmail password) for `MAIL_PASSWORD`
- Set `ADMIN_EMAIL` to the email address where you want to receive notifications
- `LOW_STOCK_THRESHOLD` is the stock level that triggers low stock alerts (default: 20)

### Step 3: Test Email Configuration

Test your email setup:

```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from KohiStore', function ($message) {
    $message->to(config('notifications.admin_email'))
            ->subject('Test Email');
});
```

Check your inbox (and spam folder) for the test email.

## 🧪 Testing Email Notifications

### Testing Low Stock Notification

The low stock notification is triggered automatically when a product's stock falls below the threshold during checkout.

**Method 1: Through the App (Recommended)**

1. **Ensure queue worker is running:**
   ```bash
   php artisan queue:work
   ```

2. **Check current product stock:**
   - Log into the app
   - Find a product with stock above the threshold (default: 20)
   - Note the current stock quantity

3. **Trigger the notification:**
   - Add enough items to cart to bring stock below threshold
   - For example, if a product has 25 units and threshold is 20:
     - Add 6+ units to cart
     - Complete checkout
   - The notification job will be dispatched to the queue

4. **Check the queue:**
   - The queue worker will process the job
   - Check your email inbox (the `ADMIN_EMAIL` address)
   - You should receive a "Low Stock Alert" email

**Method 2: Manual Testing via Tinker**

```bash
php artisan tinker
```

```php
// Get a product
$product = App\Models\Product::first();

// Set stock below threshold
$product->stock_quantity = 15; // Below default threshold of 20
$product->save();

// Dispatch the notification job
App\Jobs\SendLowStockNotification::dispatch($product);
```

Then check your email inbox.

**Method 3: Check Queue Logs**

Monitor queue processing:
```bash
# In the terminal running queue:work, you'll see logs
# Or check Laravel logs:
tail -f storage/logs/laravel.log
```

### Testing Daily Sales Report

The daily sales report can be run manually or automatically via scheduler.

**Method 1: Manual Execution (Recommended for Testing)**

```bash
php artisan sales:report-daily
```

This will:
- Collect all orders from today
- Calculate totals and product breakdowns
- Send an email report to `ADMIN_EMAIL`

**Method 2: Scheduled Execution**

The report is scheduled to run daily at 17:30 (5:30 PM) Hong Kong time.

To enable scheduled tasks, add this to your crontab:

```bash
* * * * * cd /path-to-your-project/backend && php artisan schedule:run >> /dev/null 2>&1
```

**Method 3: Create Test Orders First**

If you want to see a report with data:

1. **Create some test orders through the app:**
   - Log in
   - Add products to cart
   - Complete checkout

2. **Run the report:**
   ```bash
   php artisan sales:report-daily
   ```

3. **Check your email** for the detailed sales report

## 🛠️ Development Commands

### Essential Commands

```bash
# Start development server
php artisan serve

# Start queue worker (required for low stock notifications)
php artisan queue:work

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Build frontend assets (development)
npm run dev

# Build frontend assets (production)
npm run build
```

### Queue Commands

```bash
# Process queue jobs
php artisan queue:work

# Process queue with verbose output
php artisan queue:work --verbose

# Process failed jobs
php artisan queue:retry all

# List failed jobs
php artisan queue:failed

# Clear failed jobs
php artisan queue:flush
```

### Scheduler Commands

```bash
# Run scheduled tasks manually
php artisan schedule:run

# List scheduled tasks
php artisan schedule:list

# Run daily sales report manually
php artisan sales:report-daily
```

## 📁 Project Structure

```
backend/
├── app/
│   ├── Console/Commands/      # Artisan commands (sales report)
│   ├── Http/Controllers/      # API controllers
│   ├── Jobs/                  # Queue jobs (low stock notification)
│   ├── Mail/                  # Email classes
│   └── Models/                # Eloquent models
├── config/
│   ├── mail.php               # Email configuration
│   ├── notifications.php      # Notification settings
│   └── queue.php              # Queue configuration
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── js/                    # Vue.js frontend
│   └── views/emails/          # Email templates
└── routes/
    ├── api.php                # API routes
    └── console.php            # Scheduled tasks
```

## 🔧 Configuration

### Key Environment Variables

```env
# Application
APP_NAME="KohiStore"
APP_URL=http://localhost:8000

# Database (SQLite by default)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Mail (Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="KohiStore"

# Notifications
ADMIN_EMAIL=admin@example.com
LOW_STOCK_THRESHOLD=20

# Queue
QUEUE_CONNECTION=database
```

## 🐛 Troubleshooting

### Emails Not Sending

1. **Check Gmail App Password:**
   - Ensure you're using the 16-character app password, not your regular password
   - Verify 2-Step Verification is enabled

2. **Check Queue Worker:**
   - Low stock notifications require `php artisan queue:work` to be running
   - Check queue logs: `storage/logs/laravel.log`

3. **Test Email Configuration:**
   ```bash
   php artisan tinker
   Mail::raw('Test', function($m) { $m->to(config('notifications.admin_email'))->subject('Test'); });
   ```

4. **Check Spam Folder:**
   - Gmail might send initial emails to spam
   - Mark as "Not Spam" to improve deliverability

### Queue Jobs Not Processing

1. **Ensure queue worker is running:**
   ```bash
   php artisan queue:work
   ```

2. **Check queue table:**
   ```bash
   php artisan tinker
   DB::table('jobs')->count();
   ```

3. **Process failed jobs:**
   ```bash
   php artisan queue:retry all
   ```

### Sales Report Not Running

1. **Run manually to test:**
   ```bash
   php artisan sales:report-daily
   ```

2. **Check scheduler:**
   ```bash
   php artisan schedule:list
   ```

3. **Verify crontab (for scheduled execution):**
   ```bash
   crontab -l
   ```

## 📚 Features

- ✅ Authenticated shopping cart (Laravel Sanctum)
- ✅ Server-side cart storage (Laravel Cache)
- ✅ Low stock email notifications (Queue jobs)
- ✅ Daily sales reports (Scheduled tasks)
- ✅ Product management
- ✅ Order management
- ✅ Customer management

## 📝 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
