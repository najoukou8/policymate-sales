# Sales Import & Reporting project

This Symfony 5.4 project implements a small sales import system with reporting features. It includes:

- CSV import with validation and error logging.
- Reports accessible via REST API endpoints.
- Swagger documentation for API exploration.
- PHPUnit tests.

---

## 1. Setup

### Clone the repository

```bash
git clone <your-repo-url>
cd <repo-directory>
```
### Install dependencies
```bash
composer install
```
### Configure environment

Modify .env and configure your database:
```bash
DATABASE_URL="mysql://root:password@127.0.0.1:3306/salesdb"
```
For the test environement modify the .env.test :
```bash
DATABASE_URL="mysql://root:password@127.0.0.1:3306/salesdb"
```
### Run migrations
```bash
php bin/console doctrine:migrations:migrate
```
and for the test :
```bash
php bin/console doctrine:migrations:migrate  --env=test
```

## 2. Import CSV
 the csv file is in /var/data/sales.csv
run the command :
```bash
php bin/console app:import-sales storage/app/sales.csv
```
Valid rows are imported into the sale table
Invalid rows are logged in the import_error table
re-running the command does not duplicate rows

## 3.Reports
### Top N products by revenue

Endpoint:
```bash
GET /api/report/top-products?limit=3
```
Returns the top products ordered by total revenue
### Monthly revenue summary

Endpoint:
```bash
GET /api/report/monthly-revune?year=2025
```

Returns revenue per month for the given year

### Top customers by revenue

Endpoint:
```bash
GET /api/report/top-customers?limit=3
```

Returns top customers by revenue and number of orders

### Swagger documentation

Available at: /api/docs
Allows you to explore and test all API endpoints interactively

## 4. Running tests
```bash
./vendor/bin/phpunit
```
Tests use the salesdb_test database
Only one test exist, the one that tests the import command

## 5. Assumptions & Known Limitations
- the Database is not normalized (i could've created better database)
- Only supports CSV files with the exact specified columns
- Dates must be in YYYY-MM-DD format
- No authentication for API endpoints
- Large CSV files are not optimized for chunked imports
- Not enough tests


## 6. Improvements with more time
- Redo the database with nex tables( products, costumers and orders)
- Add authentication & API security
- Implement chunked import for very large CSV files
- Add more tests
- Create  the columns of a table automatically from the CSV headers via the import command
