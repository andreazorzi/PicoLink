#!/bin/bash
set -e

# Function to wait for database
wait_for_database() {
    echo "⏳ Waiting for database..."
    
    # Check if database environment variables are set
    if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "localhost" ] || [ "$DB_HOST" = "127.0.0.1" ]; then
        echo "ℹ️  No external database configured, skipping database wait"
        return 0
    fi
    
    # Wait for database connection
    max_attempts=30
    attempt=1
    
    until [ $attempt -eq $max_attempts ]; do
        # Check if db:monitor returns OK for mysql
        if php artisan db:monitor --databases=mysql 2>/dev/null | grep "mysql" | grep -q "OK"; then
            echo "✅ Database connection established!"
            return 0
        fi
        
        echo "⚠️ Database not ready yet... attempt $attempt/$max_attempts"
        attempt=$((attempt + 1))
        sleep 3
    done
    
    echo "❌ ERROR: Could not connect to database after $max_attempts attempts"
    exit 1
}

# Your initialization function
init_laravel() {
    echo "🚀 Initializing Laravel application..."
    
    # Ensure we're in the right directory
    cd /var/www/html
    
    # Check if Laravel is properly installed
    if [ ! -f "artisan" ]; then
        echo "❌ Laravel artisan file not found!"
        exit 1
    fi
    
    # Wait for database to be ready (if using database)
    wait_for_database
    
    # Run migrations
    echo "🔄 Running migrations..."
    php artisan migrate --force || {
        echo "⚠️  Migration failed, but continuing..."
    }
    
    # Create laravel.log file if it doesn't exist
    echo "📝 Creating laravel.log file..."
    if [ ! -f "storage/logs/laravel.log" ]; then
        touch storage/logs/laravel.log
    fi
    
    # Run init command
    echo "🚀 Running init command..."
    php artisan app:init || {
        echo "⚠️  Init command failed, but continuing..."
    }
    
    # Set file ownership to apache
    echo "🔧 Setting file ownership to apache..."
    chown -R apache:apache /var/www/html
    
    echo "✅ Laravel initialization completed!"
}

# Run initialization
init_laravel

# Execute the original command
exec "$@"