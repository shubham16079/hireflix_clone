@echo off
echo 🎬 Hireflix Clone - Quick Setup
echo ===============================

REM Check if .env exists
if not exist .env (
    echo 📝 Creating .env file...
    copy .env.example .env
    echo ✅ .env file created
) else (
    echo ⚠️  .env file already exists
)

REM Generate application key
echo 🔑 Generating application key...
php artisan key:generate

REM Create storage link
echo 🔗 Creating storage link...
php artisan storage:link

REM Run migrations
echo 🗄️  Running database migrations...
php artisan migrate

REM Run seeders
echo 🌱 Creating default users and sample data...
php artisan db:seed

echo.
echo 🎉 Setup completed successfully!
echo.
echo 🔑 Default Login Credentials:
echo    Admin: admin@hireflixclone.com / password
echo    HR: hr@hireflixclone.com / password
echo    Reviewer: reviewer@hireflixclone.com / password
echo.
echo 📋 Sample Data Created:
echo    - 3 sample interviews with questions
echo    - Ready for testing and demonstration
echo.
echo 🚀 Start the server: php artisan serve
echo 🌐 Then visit: http://localhost:8000
echo.
echo Happy interviewing! 🎯
pause
