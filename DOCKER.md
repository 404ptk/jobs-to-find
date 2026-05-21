# 🐳 Docker Setup - JobsToFind

Single-container Docker configuration for Laravel 12 with PHP 8.2 CLI, Vite 7, and SQLite. The project runs without needing to install PHP, Node.js, or other dependencies locally.

## 📋 Requirements

Install:
- **Docker Desktop** - https://www.docker.com/products/docker-desktop
- **Docker Compose** (usually included with Docker Desktop)

Verification:
```bash
docker --version
docker-compose --version
```

## 🚀 Quick Start

### Using docker.sh helper script (Recommended)

**On macOS/Linux/Windows (with Bash available):**
```bash
bash ./docker.sh up
```

This is the path we verified in the workspace.

### Or directly with Docker Compose:

```bash
# Start container (build if needed)
docker-compose up -d

# Stop container
docker-compose down

# View logs
docker-compose logs -f app

# Open shell in container
docker-compose exec app sh
```

## 📱 Accessing the Application

After startup, the application is available at:
- **http://localhost:8000**
- **Browser**: http://localhost:8000

## 🛠️ Available Commands

### Using the helper script:

```bash
./docker.sh up              # Start container
./docker.sh down            # Stop container
./docker.sh build           # Build image
./docker.sh logs            # View application logs
./docker.sh shell           # Open shell in container
./docker.sh migrate         # Run database migrations
./docker.sh seed            # Seed the database
./docker.sh cache-clear     # Clear all caches
./docker.sh test            # Run tests (PHPUnit)
./docker.sh tinker          # Laravel REPL
./docker.sh restart         # Restart container
./docker.sh ps              # Show container status
./docker.sh clean           # Remove all data (hard reset)
```

### Direct Docker commands:

```bash
# Start/stop
docker-compose up -d        # Start container in background
docker-compose down         # Stop container

# Artisan commands
docker-compose exec app php artisan [command]
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Database
docker-compose exec app php artisan tinker            # Laravel REPL
docker-compose exec app php artisan route:list        # Show routes

# Tests
docker-compose exec app php artisan test

# Shell access
docker-compose exec app sh

# Logs
docker-compose logs -f app                # Real-time logs
docker-compose logs --tail=100 app       # Show last 100 lines
```

## 📊 Architecture

This is a single-container setup running Laravel on PHP 8.2 CLI:

```
┌─────────────────────────────────────┐
│       JobsToFind (Single Container) │
├─────────────────────────────────────┤
│     PHP 8.2 CLI + Laravel 12        │ :8000
│     (php artisan serve)             │
├─────────────────────────────────────┤
│  SQLite Database (File: database.   │
│          sqlite)                    │
├─────────────────────────────────────┤
│  Node 24 Build Stage (Vite + CSS)   │ (build only)
│  Compiled assets included in image  │
└─────────────────────────────────────┘
```

### How it works:

1. **PHP 8.2 CLI** - Runs `php artisan serve` on port 8000
2. **SQLite Database** - File-based, persisted in `database/database.sqlite`
3. **Built Assets** - Compiled during Docker build (Vite, Tailwind CSS)
4. **Auto-migrations** - Database migrations run on container startup
5. **Development Mode** - `APP_DEBUG=true` by default

## 📁 File Structure

```
project/
├── Dockerfile              # Multi-stage build (PHP deps → Node build → runtime)
├── docker-compose.yml      # Single container configuration
├── .dockerignore           # Files excluded from build
├── docker.sh               # Helper script (start/stop/logs/shell/migrate/test/etc)
├── docker/
│   ├── entrypoint.sh       # Startup script (generates .env, runs migrations)
│   └── php.ini             # PHP configuration
└── ...
```

## 🔧 Configuration

### Startup Process

When the container starts, `docker/entrypoint.sh`:
1. Creates `.env` file (if missing) from `.env.example`
2. Generates `APP_KEY` (if missing)
3. Creates `database/database.sqlite` if needed
4. Runs database migrations
5. Clears cache
6. Executes `php artisan serve --host=0.0.0.0 --port=8000`

### Database

- **Type**: SQLite (file-based)
- **Location**: `database/database.sqlite` (persisted in volume)
- **Migrations**: Automatically run on every container start
- **No external database needed** - everything is self-contained

### Environment Variables in docker-compose.yml

```env
APP_NAME=JobsToFind
APP_ENV=local              # Development mode
APP_DEBUG=true             # Show detailed errors
DB_CONNECTION=sqlite       # SQLite driver
QUEUE_CONNECTION=sync      # Synchronous queue
CACHE_STORE=file          # File-based cache
SESSION_DRIVER=file       # File-based sessions
```

To change these, edit `docker-compose.yml` under the `app` service's `environment` section.

## 💾 Data Persistence

The container includes a single volume configuration:
- `./database:/app/database` - SQLite database file

All other files (code, assets, storage) are copied into the image and persist within the container.

```yaml
volumes:
  - ./database:/app/database    # SQLite persists on host
```

The SQLite database is saved to `database/database.sqlite` on the host machine. On every container start, migrations are applied, so the schema is always up-to-date.

## 🔍 Debugging

### View Logs

```bash
# Real-time application logs
docker-compose logs -f app

# Show last 50 lines
docker-compose logs --tail=50 app

# All logs (one-time)
docker-compose logs app
```

### Shell Access

```bash
# Access container shell
docker-compose exec app sh

# Inside the container:
php artisan tinker            # Laravel REPL for debugging
php artisan route:list        # Show all routes
php artisan config:show       # Display configuration
php artisan migrate:status    # Check migration status
tail -f storage/logs/laravel.log  # Follow Laravel logs
```

### Container Status

```bash
# Check if running
docker-compose ps

# View container details
docker-compose ps app
```

## 🧹 Cleanup

### Temporarily stop the container (data persists)
```bash
docker-compose stop
docker-compose start
```

### Remove container but keep data
```bash
docker-compose down
```

### Full cleanup - DELETE EVERYTHING (database, cache, etc)
```bash
# Using the helper script
./docker.sh clean

# Or manually
docker-compose down -v --rmi all
```

## 🔐 Security

This Docker setup is for **development only**. For production deployment:

1. Set `APP_DEBUG=false` in `docker-compose.yml`
2. Set `APP_ENV=production`
3. Use a strong, unique `APP_KEY`
4. Generate fresh `APP_KEY` for production: `php artisan key:generate`
5. Never commit `.env` file to Git (use `.env.example` only)
6. Use environment variables from your hosting provider
7. Enable HTTPS in production (reverse proxy with Let's Encrypt)
8. Set secure cookie flags in `config/session.php`

## 📈 Performance

Current optimizations:
- ✅ **Multi-stage build** - Reduces final image size (separate PHP deps, Node build, runtime stages)
- ✅ **Only essential PHP extensions** - pdo_sqlite, mbstring (keeps image lean)
- ✅ **Pre-compiled assets** - Vite builds during Docker build, not at runtime
- ✅ **Development defaults** - SQLite, file-based cache/sessions for instant setup
- ✅ **Minimal base image** - PHP CLI only (no PHP-FPM/Nginx overhead)

## 🐛 Troubleshooting

### "Address already in use: 0.0.0.0:8000"
Port 8000 is being used by another service. Change in `docker-compose.yml`:
```yaml
ports:
  - "8001:8000"      # Use port 8001 instead of 8000
```
Then access at: http://localhost:8001

### Container exits immediately
Check the logs:
```bash
docker-compose logs app
```
Common causes:
- Missing `.env` file or `APP_KEY` not generated
- Database migration failed
- PHP error during startup

### "Cannot connect to database" or "database is locked"
```bash
# Check if database file exists
docker-compose exec app ls -la database/

# Check permissions
docker-compose exec app chmod 777 database/database.sqlite

# Reset database and migrations
docker-compose down -v
docker-compose up -d
```

### Application not responding after startup

1. Check if container is running:
   ```bash
   docker-compose ps
   ```

2. Wait a moment (Laravel boot takes ~3-5 seconds)

3. Check logs:
   ```bash
   docker-compose logs -f app
   ```

4. Restart:
   ```bash
   docker-compose restart app
   ```

## 🚢 Production Deployment

This Dockerfile is compatible with:
- ✅ **Heroku** - Builds automatically from Dockerfile
- ✅ **AWS** - ECR, EC2 with Docker, ECS
- ✅ **Google Cloud** - Cloud Run, GKE, Cloud Build
- ✅ **DigitalOcean** - App Platform, Droplets with Docker
- ✅ **Azure** - Container Instances, App Service
- ✅ **Any host with Docker** - VPS, dedicated servers

### Basic Deployment Example (VPS with Docker)

```bash
# On your local machine
docker build -t yourusername/jobstofind:latest .
docker push yourusername/jobstofind:latest

# On the production server
docker pull yourusername/jobstofind:latest

# Use with docker-compose or run directly:
docker run -d \
  --name jobstofind \
  -p 80:8000 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -v /data/database:/app/database \
  yourusername/jobstofind:latest
```

### Environment Variables for Production

Always set on the server:
```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=your-generated-key-here
DB_CONNECTION=sqlite
```

Then access at: http://your-domain.com (with reverse proxy like Nginx on port 80)

## 📚 Resources

- [Docker Docs](https://docs.docker.com/)
- [Docker Compose Reference](https://docs.docker.com/compose/compose-file/)
- [Laravel Docker Guide](https://laravel.com/docs/deployment/docker)
- [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.configuration.php)

## ❓ FAQ

**Q: Why does it take 3-5 seconds to start?**
A: Laravel needs to boot and initialize. This is normal. The first request after startup might be slightly slower.

**Q: Can I edit code while it's running?**
A: Yes! The code is copied into the image, so you need to rebuild. For development, you might prefer running Laravel locally without Docker.

**Q: How do I add new PHP extensions?**
A: Edit the `Dockerfile` and add to the extension compilation section in the `php-deps` stage:
```dockerfile
RUN docker-php-ext-install pdo_mysql mbstring ...
```
Then rebuild: `docker-compose up -d --build`

**Q: Can I use a different database (MySQL/PostgreSQL)?**
A: Yes, but you'd need to modify `docker-compose.yml` to add a separate database service. Currently optimized for SQLite which requires no setup.

**Q: Why single container instead of multiple services?**
A: Simplicity! For development, this is sufficient. For production with multiple services, add them to `docker-compose.yml`.

**Q: How do I run background jobs/queues?**
A: Currently using `QUEUE_CONNECTION=sync` (synchronous). For async jobs in production, switch to `QUEUE_CONNECTION=database` and add a supervisor/queue worker service.

**Q: Is this production-ready?**
A: It's a good foundation. For production:
- Add a reverse proxy (Nginx) in front for HTTPS
- Set `APP_DEBUG=false`
- Use a managed database (don't rely on local SQLite)
- Monitor logs and errors
- Set up regular backups
- Use production-grade secrets management

---

**Questions?** Open an issue in the repository!
