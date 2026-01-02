# Tkambio Challenge Full Stack

This is the backend for the Tkambio Full Stack challenge that implements an asynchonous user reporting system using Laravel, MySQL, and Redis.

## Setup and installation

### 1. Requirements
- Docker & Docker Compose (Laravel Sail)
- composer

### 2. Installation
```bash
# Clone the repository
git clone <your-repo-link>
cd tkambio-challenge-backend

# Install dependencies
composer install

# Create environment file
cp .env.example .env

# Start Laravel Sail
./vendor/bin/sail up -d

# Generate app key
./vendor/bin/sail artisan key:generate
```

## Usage

To see the system in action, follow these steps in order:

### 1. Database & Seeders
Run the migrations and seed the database with test users.

```Bash
./vendor/bin/sail artisan migrate --seed
```

### 2. Processing Jobs (Queues)
Since report generation is asynchronous, you must start the queue worker to process the exports.

```Bash
./vendor/bin/sail artisan queue:work
```

> Note: Keep this terminal open or run it in the background to process the reports triggered via API.

### 3. Storage Link
Make sure the exported files are accessible via the web.

```Bash
./vendor/bin/sail artisan storage:link
```

## API Usage
Key Endpoints
- `POST /api/login`: Get your Bearer Token.
- `GET /api/reports`: List all your reports (paginated).
- `POST /api/reports`: Request a new report (Requires title, date_init, date_end).  
- `GET /api/reports/{id}`: Check report status and get the secure download link.

**Important**: All requests must include the following headers:

- `Accept: application/json`
- `Authorization: Bearer <your_token>`