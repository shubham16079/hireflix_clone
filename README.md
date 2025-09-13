# 🎬 Hireflix Clone - Video Interview Platform

A comprehensive video interview platform built with Laravel, featuring candidate interviews, submission management, and reviewer functionality - inspired by Hireflix.

## ✨ Features

### 🎯 Core Functionality
- **Video Interview System** - Record and manage video interviews
- **Candidate Management** - Send invitations and track submissions
- **Reviewer System** - Share interviews with reviewers for evaluation
- **Real-time Progress Tracking** - Monitor interview completion status
- **Advanced Filtering** - Search and filter submissions with AJAX
- **Email Notifications** - Brevo integration for invitations
- **Export Functionality** - Download submission data as CSV

### 🎨 User Experience
- **Responsive Design** - Works on desktop, tablet, and mobile
- **Modern UI** - Beautiful interface with Tailwind CSS
- **SweetAlert2 Integration** - Professional notifications and confirmations
- **Loading States** - Smooth animations and feedback
- **Role-based Access** - Admin, reviewer, and candidate roles

### 🔧 Technical Features
- **Laravel 11** - Modern PHP framework
- **MySQL Database** - Reliable data storage
- **Video Storage** - Local file system with public access
- **CSRF Protection** - Secure form submissions
- **Pagination** - Efficient data loading
- **AJAX Filtering** - Real-time search and filter updates

## 🚀 Installation

### Prerequisites

Before you begin, ensure you have the following installed:
- **PHP 8.2+** with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer** - PHP dependency manager
- **MySQL 8.0+** - Database server
- **Node.js & NPM** - For frontend assets (optional)

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/hireflix-clone.git
cd hireflix-clone
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (optional)
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

1. **Create Database**
   ```sql
   CREATE DATABASE hireflix_clone;
   ```

2. **Configure Database Connection**
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hireflix_clone
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```

### Step 5: Storage Configuration

```bash
# Create storage link
php artisan storage:link

# Set proper permissions (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 6: Email Configuration (Brevo)

1. **Get Brevo API Key**
   - Sign up at [Brevo](https://www.brevo.com/)
   - Create an API key in your account settings

2. **Configure Email Settings**
   Edit `.env` file:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp-relay.brevo.com
   MAIL_PORT=587
   MAIL_USERNAME=your_brevo_email
   MAIL_PASSWORD=your_brevo_api_key
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email@domain.com
   MAIL_FROM_NAME="Hireflix Clone"
   ```

### Step 7: Create Admin User

```bash
# Run the seeder to create an admin user
php artisan db:seed --class=UserSeeder
```

**Default Admin Credentials:**
- Email: `admin@hireflix.com`
- Password: `password`

### Step 8: Start the Application

```bash
# Start Laravel development server
php artisan serve
```

The application will be available at `http://localhost:8000`

## 📁 Project Structure

```
hireflix-clone/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # Authentication
│   │   ├── InterviewController.php     # Interview management
│   │   ├── SubmissionController.php    # Submission handling
│   │   ├── ReviewController.php        # Review system
│   │   └── CandidateInterviewController.php # Candidate interface
│   ├── Models/
│   │   ├── User.php                    # User model
│   │   ├── Interview.php               # Interview model
│   │   ├── Question.php                # Question model
│   │   ├── Submission.php              # Submission model
│   │   └── Review.php                  # Review model
│   └── Services/
│       └── LaravelBrevoMailService.php # Email service
├── database/
│   ├── migrations/                     # Database migrations
│   └── seeders/                        # Database seeders
├── resources/
│   ├── views/
│   │   ├── layouts/                    # Layout templates
│   │   ├── interviews/                 # Interview views
│   │   ├── submissions/                # Submission views
│   │   ├── reviews/                    # Review views
│   │   ├── candidate/                  # Candidate interface
│   │   └── emails/                     # Email templates
│   └── css/                           # Stylesheets
├── routes/
│   └── web.php                        # Web routes
└── storage/
    └── app/public/interview_videos/   # Video storage
```

## 🎯 Usage Guide

### For Administrators

1. **Create Interviews**
   - Navigate to "Interviews" → "Create New Interview"
   - Add interview details and questions
   - Configure video settings

2. **Send Invitations**
   - Go to interview details page
   - Use "Send Invitation" to invite candidates
   - Copy interview link for manual sharing

3. **Manage Submissions**
   - View all submissions with advanced filtering
   - Search by candidate name/email
   - Filter by status and date range
   - Export filtered data as CSV

4. **Share with Reviewers**
   - Use "Share with Reviewers" feature
   - Send reviewer invitations via email
   - Reviewers get direct access to submissions

### For Candidates

1. **Access Interview**
   - Click the interview link from email
   - Enter your details to start
   - Record video responses to questions

2. **Interview Process**
   - Watch question videos
   - Record your responses
   - Use retake functionality if needed
   - Complete all questions to submit

### For Reviewers

1. **Review Submissions**
   - Access submissions via email link
   - Watch candidate video responses
   - Provide scores and comments
   - Submit reviews for evaluation

## 🔧 Configuration Options

### Video Settings
- **Max Video Duration**: Configurable per interview
- **Retake Permissions**: Allow/deny video retakes
- **Video Quality**: Adjustable recording settings

### Email Templates
- **Interview Invitations**: Customizable candidate emails
- **Reviewer Invitations**: Professional reviewer emails
- **Company Branding**: Add your logo and colors

### Security Features
- **CSRF Protection**: All forms protected
- **Input Validation**: Comprehensive data validation
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Output escaping

## 🐛 Troubleshooting

### Common Issues

1. **Storage Link Error**
   ```bash
   php artisan storage:link
   ```

2. **Permission Issues (Linux/Mac)**
   ```bash
   sudo chown -R www-data:www-data storage
   sudo chown -R www-data:www-data bootstrap/cache
   ```

3. **Email Not Sending**
   - Check Brevo API key configuration
   - Verify SMTP settings in `.env`
   - Check firewall settings for port 587

4. **Video Upload Issues**
   - Ensure `storage/app/public` is writable
   - Check PHP upload limits in `php.ini`
   - Verify file permissions

### Debug Mode

Enable debug mode for development:
```env
APP_DEBUG=true
APP_ENV=local
```

## 📝 API Documentation

### Key Endpoints

- `GET /interviews` - List all interviews
- `POST /interviews` - Create new interview
- `GET /interviews/{id}/submissions` - Get interview submissions
- `POST /interviews/{id}/invite` - Send candidate invitation
- `GET /interview/{token}` - Candidate interview interface
- `POST /interview/{token}/save-video` - Save video response

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- **Laravel Framework** - The PHP framework for web artisans
- **Tailwind CSS** - Utility-first CSS framework
- **SweetAlert2** - Beautiful, responsive alerts
- **Brevo** - Email service provider
- **Hireflix** - Inspiration for the video interview concept

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Issues](https://github.com/yourusername/hireflix-clone/issues) page
2. Create a new issue with detailed description
3. Include error logs and steps to reproduce

## 🚀 Deployment

### Production Deployment

1. **Server Requirements**
   - PHP 8.2+
   - MySQL 8.0+
   - Web server (Apache/Nginx)
   - SSL certificate

2. **Environment Setup**
   ```bash
   # Set production environment
   APP_ENV=production
   APP_DEBUG=false
   
   # Optimize for production
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Security Considerations**
   - Use strong database passwords
   - Enable HTTPS
   - Set proper file permissions
   - Regular security updates

---

**Built with ❤️ using Laravel and modern web technologies**