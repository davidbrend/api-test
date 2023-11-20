# Test project REST API
## APITTE Project with Swagger, Docker, PHP 8.1 and Nette 3.1

This project is a REST API built with Apitte, Docker, PHP 8.1, and Nette 3.1.
## Endpoints:
- `/api/v1/product/all`: Get all products with filter (query string)
- `/api/v1/product/one`: Get a single product by ID (query string)
- `/api/v1/product/delete`: Delete a product by ID (query string)
- `/api/v1/product/new`: Create a new product (json body)
- `/api/v1/product/update`: Update a product by ID (json body)

## Setup Instructions

*If folder name log does not exist, please create it to make project work properly.*

1. Clone the repository

2. Navigate into the cloned directory and start the project with Docker:

   `docker compose up -d`

3. Install the NPM dependencies:

   `npm install`

4. Build the composer dependencies by running:

   `docker compose exec php composer install`

5. Build the database by running the migrations with this command:

   `docker compose exec php composer run run-migration`

## Swagger, Adminer, and API Endpoints
After setting up, the following services can be accessed as shown:

- Swagger UI: `http://localhost:9001`
- Adminer (for database management): `http://localhost:8181`
- API endpoints (for testing): `http://localhost:8080/api/v1/`

To handle CORS, the `CorsMiddleware` middleware will be used to add appropriate CORS headers to the response.

## Planned

### Authentication
Although authentication is not yet implemented, the Plan is to use JWT Bearer tokens for authentication. With this method, after a user logs in, a token would be generated for the user which will be used to make authenticated requests to the server.

The `AuthenticationDecorator` middleware will verify the token present in the Authorization header of the request.

### Caching
Caching is another planned thing that would serve to ease queries to the database and speed up api response.

### Filter
Adding more complex filter to search for example "from" "to" range. 

### Tests
For testing purpose I plan to use PHP Unit tester.

### Versions
Versioning strategy is crucial for maintaining compatibility and providing a grace period for clients to adjust to new versions