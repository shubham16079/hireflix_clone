#!/bin/bash

# Hireflix Clone Quick Setup Script
echo "🎬 Hireflix Clone - Quick Setup"
echo "==============================="

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "⚠️  .env file already exists"
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Set permissions (Linux/Mac only)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🔐 Setting permissions..."
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
fi

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate

# Run seeders
echo "🌱 Creating default users and sample data..."
php artisan db:seed

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "🔑 Default Login Credentials:"
echo "   Admin: admin@hireflixclone.com / password"
echo "   HR: hr@hireflixclone.com / password"
echo "   Reviewer: reviewer@hireflixclone.com / password"
echo ""
echo "📋 Sample Data Created:"
echo "   - 3 sample interviews with questions"
echo "   - Ready for testing and demonstration"
echo ""
echo "🚀 Start the server: php artisan serve"
echo "🌐 Then visit: http://localhost:8000"
echo ""
echo "Happy interviewing! 🎯"
