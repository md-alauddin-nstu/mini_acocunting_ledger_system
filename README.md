# 🏦 Mini Accounting Ledger System

This project implements a simplified ledger for recording financial transactions (debit/credit) and atomically maintaining the balance of Account models using a Service Class and a Custom Facade.

# 🚀 Setup & Installation

Use these commands to get the project running locally.

## 1. Install dependencies

```bash
composer install
```

## 2. Create environment file and key

```bash
cp .env.example .env
php artisan key:generate
```

## 3. Setup database (ensure 'database/database.sqlite' exists or use MySQL)

```bash
php artisan migrate --seed
```

# 💡 System Usage

The primary interface is the Ledger Facade, which ensures that for every transaction created, the corresponding account balance is updated exactly once (using Eloquent event control).

| Type            | Effect (on Asset Account) |
| --------------- | ------------------------- |
| debitIncreases  | the account balance.      |
| creditDecreases | the account balance.      |

## 1. Add Transaction (Facade)

Run the following in `php artisan tinker`:

```php
$bank = App\Domains\Account\Models\Account::where('name', 'Bank')->first();

// Debit: Deposit $1500.00
\App\Domains\Account\Facades\Ledger::addTransaction(
    $bank->id,
    'debit',
    1500.00,
    'Initial Funding'
);

// Credit: Withdrawal $200.00
\App\Domains\Account\Facades\Ledger::addTransaction(
    $bank->id,
    'credit',
    200.00,
    'Rent Payment'
);

// Check new balance
$bank->refresh()->balance; // Should output 1300.0 (1500 - 200)
```

## 2. Ledger Report (API)

View the aggregated report for a specific account via the HTTP endpoint:

Endpoint: `GET /ledger/report/{account_id}`

Example using Account ID 1 (Cash):

```bash
# Start the server
php artisan serve

# Send request (e.g., via curl)
curl http://127.0.0.1:8000/ledger/report/1
```

Output Structure:

```json
{
    "status": "success",
    "report": {
        "account_name": "Cash",
        "total_debit": 1500.0,
        "total_credit": 500.0,
        "current_balance": 1000.0
    }
}
```

# 🧪 Testing (Pest)

The application includes comprehensive tests using the Pest framework to ensure core logic and integration points are stable.
Running Tests

```Bash
php artisan test
```

## Test Coverage

| Test Group | File                                | Focus                                                                                                                |
| ---------- | ----------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| Feature    | tests/Feature/LedgerServiceTest.php | Verifies LedgerService balance calculations are mathematically correct.                                              |
| Feature    | tests/Feature/LedgerSystemTest.php  | Confirms the Facade/Service/DB integration works (balance updated once) and the Report API returns the correct data. |
