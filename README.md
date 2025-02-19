# TravelPlanet Task

## Documentation

Documentation can be found in the **#doc** directory of this project.

## Docker Setup

### Prerequisites
- Ensure port 80 is available (not in use by local web server)

### Installation Steps
1. Start the containers:
   ```bash
   docker compose up -d
   ```

2. Access container shell:
   ```bash
   docker compose exec app bash
   ```

3. Access the application:
   - Server document root: `/var/www/html/public`
   - Application URL: `http://localhost`

## Laravel Setup

1. Environment setup
   ```bash
   copy .env.example file to .env and set your environment variables if needed
   ```
2. Generate App Key
   ```bash
   php artisan key:generate
   ```

3. Run migrations and seed the database:
   ```bash
   php artisan migrate:refresh --seed
   ```

4. Run tests:
   ```bash
   php artisan test
   ```
