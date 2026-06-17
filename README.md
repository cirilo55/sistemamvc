Sistema MVC
===========

Custom PHP MVC project using old-school PHP, Apache, PDO MySQL, and PHTML views.

The project is a build-to-learn framework exercise. The code now boots through
`sys/bootstrap.php`, uses a small custom autoloader in `sys/Autoloader.php`, and
keeps the stack free of Composer and Node.js at runtime.

Docker
------

Build and start the local stack:

```bash
docker compose up --build
```

Open the app at:

```text
http://localhost:8080
```

The Compose stack starts:

- `app`: PHP 8.2 with Apache and live-mounted source code
- `db`: MySQL 8.0 with the schema loaded from `sys/database/db.sql`
- `uploads`: persistent volume mounted at `imgs/profile`
- `sys/logs`: local app and PHP error logs ignored by Git
- `db_data`: persistent MySQL data volume

Default Docker database settings:

```env
DB_HOST=db
DB_PORT=3306
DB_DATABASE=sistemamvc
DB_USERNAME=app
DB_PASSWORD=secret
```

Seed login:

```text
Usuario: Admin
Senha: 1234
```

Local PHP Setup
---------------

If you run without Docker, create a `.env` file:

```bash
cp .envexample .env
```

Example `.env`:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistemamvc
DB_USERNAME=root
DB_PASSWORD=
```

Then import `sys/database/db.sql` into MySQL and serve the project with Apache configured to route clean URLs to `index.php`.

Development Commands
--------------------

Start the environment:

```bash
docker compose up
```

Start in the background:

```bash
docker compose up -d
```

Stop containers:

```bash
docker compose down
```

Reset the local database and upload volumes:

```bash
docker compose down -v
docker compose up -d
```

View application logs:

```bash
docker compose logs app
```

View readable framework logs:

```bash
sh scripts/show-logs.sh
```

The application logger writes daily readable files in `sys/logs/app-YYYY-MM-DD.log`.
PHP runtime errors are written to `sys/logs/php_errors.log`. The folder stays in
the repository through `sys/logs/.gitignore`, but generated log files are ignored.

Run PHP syntax checks without Composer:

```bash
docker compose exec app sh scripts/check-php.sh
```

Run a basic route/login smoke test:

```bash
sh scripts/smoke-test.sh
```

Architecture Direction
----------------------

The current learning path is:

1. Development workflow
2. Custom bootstrap and autoloader
3. Front Controller and Router
4. Request/Response objects
5. MVC cleanup
6. Dependency Injection Container
7. Service Layer
8. Repository Layer
9. Middleware Pipeline
10. Event Dispatcher
11. Logging and Error Handling
12. Testing strategy
