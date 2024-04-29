# Laravel 10 Project with Artillery Performance Testing

This project is built using Laravel 10 and includes performance testing setups using Artillery. The project contains several RESTful endpoints which are tested to ensure performance standards under load.

## Project Setup and Installation

### Requirements

- PHP >= 8.0
- Composer
- Node.js and npm

### Installation Steps

1. **Clone the repository:**
   ```
   git clone https://yourrepository.com/yourproject.git
   cd yourproject
   ```

2. **Install PHP dependencies:**
   ```
   composer install
   ```

3. **Set up the environment file:**
   Copy the `.env.example` file to a new file called `.env`, and adjust the database and other configurations as necessary:
   ```
   cp .env.example .env
   ```

4. **Generate the application key:**
   ```
   php artisan key:generate
   ```

5. **Run migrations and seeders (if applicable):**
   ```
   php artisan migrate --seed
   ```

6. **Install Node.js dependencies (for Artillery):**
   ```
   npm install -g artillery
   ```

## Artillery Performance Testing

### Writing Tests

Artillery tests are defined in YAML configuration files. These tests simulate user load on the application's endpoints to measure performance metrics like response times, request rates, and error rates.

### Example Artillery Test Configuration

Here’s an example of an Artillery test configuration to test the user creation endpoint:

```yaml
config:
  target: 'http://localhost:8000'
  phases:
    - duration: 60
      arrivalRate: 10
      rampTo: 50
      name: "Warm up phase"
    - duration: 120
      arrivalRate: 50
      name: "Sustained high load"

scenarios:
  - flow:
      - post:
          url: "/api/users"
          json:
            name: "Test User"
            email: "test{{ _uid }}@example.com"
            password: "password"
          headers:
            Content-Type: "application/json"
```

### Running Tests

To run an Artillery test, use the following command:

```
artillery run test_script.yml
```

### Analyzing Test Results

After running tests, Artillery will generate a report in the terminal and can output detailed JSON reports if configured. Analyze these reports to understand the application's behavior under different load conditions.

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Artillery Documentation](https://artillery.io/docs)

## Contributing

Contributions to improve the project are welcome. Please follow the standard fork-and-pull request workflow.

## License

This project is licensed under the MIT License.
