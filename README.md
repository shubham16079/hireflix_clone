# 🎥 Hireflix Clone - Video Interview Platform

A comprehensive video interview platform built with Laravel that allows companies to conduct professional video interviews, review candidates remotely, and make data-driven hiring decisions.

## ✨ Core Features

### ✅ **Authentication System**
- **Sign up/Sign in** for Admin, Reviewer, and Candidate roles
- Role-based access control with proper permissions
- Secure authentication with Laravel's built-in auth system

### ✅ **Interview Management**
- **Admin/Reviewer**: Create interviews with title, description, and custom questions
- Multiple question types support
- Interview sharing and collaboration features
- Email invitations to candidates

### ✅ **Candidate Experience**
- **Candidates**: Access interviews via secure email links (no account required)
- Record and upload video answers to questions
- Real-time interview progress tracking
- Professional interview interface

### ✅ **Review System**
- **Reviewers**: View candidate submissions and leave scores/comments
- Collaborative review system with multiple reviewers per interview
- Review assignment management
- Comprehensive scoring and feedback system

### ✅ **Additional Features**
- Review assignment system for managing reviewer workloads
- Email notifications for interview invitations and review assignments
- Dashboard with role-specific views and statistics
- Responsive design for all devices
- Modern UI with Netflix-inspired design

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and NPM
- SQLite (included) or MySQL/PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd hireflix_clone
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   - Open your browser and go to `http://localhost:8000`
   - Use the test credentials below to explore the platform

## 🔑 Test Account Credentials

### Admin Account
- **Email**: `admin@hireflixclone.com`
- **Password**: `password`
- **Access**: Full platform access, can create interviews, manage users, view all data

### Reviewer Account
- **Email**: `reviewer@hireflixclone.com`
- **Password**: `password`
- **Access**: Can review candidate submissions, view assigned interviews

### Additional Reviewer
- **Email**: `john.reviewer@hireflixclone.com`
- **Password**: `password`

### Candidate Access
- **Note**: Candidates don't need accounts
- They access interviews via secure email links sent by admins/reviewers
- Sample interview links are available in the seeded data

## 🎯 Demo Workflow

### 1. **Sign In/Sign Up**
- Visit the homepage and click "Sign In" or "Get Started"
- Use the test credentials above
- Or create new accounts with different roles

### 2. **Creating an Interview** (Admin/Reviewer)
- Sign in as admin or reviewer
- Go to Dashboard → "Create New Interview"
- Fill in interview title, description
- Add custom questions
- Save and share with candidates

### 3. **Candidate Recording/Uploading Answers**
- Admin/Reviewer sends interview link to candidate via email
- Candidate clicks link and fills out their details
- Records video answers for each question
- Submits the completed interview

### 4. **Reviewer Viewing and Scoring Answers**
- Reviewer receives notification of new submission
- Views candidate's video responses
- Provides scores (1-10) and detailed comments
- Collaborates with other reviewers if assigned

## 📁 Project Structure

```
hireflix_clone/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # Authentication
│   │   ├── InterviewController.php     # Interview management
│   │   ├── CandidateInterviewController.php # Candidate interface
│   │   ├── ReviewController.php        # Review system
│   │   ├── SubmissionController.php    # Submission management
│   │   └── ReviewAssignmentController.php # Review assignments
│   ├── Models/
│   │   ├── User.php                    # User model with roles
│   │   ├── Interview.php               # Interview model
│   │   ├── Question.php                # Question model
│   │   ├── Submission.php              # Candidate submissions
│   │   ├── Review.php                  # Review model
│   │   └── ReviewAssignment.php        # Review assignments
│   └── Services/
│       └── LaravelBrevoMailService.php # Email service
├── database/
│   ├── migrations/                     # Database migrations
│   └── seeders/                        # Sample data seeders
├── resources/
│   ├── views/
│   │   ├── auth/                       # Authentication views
│   │   ├── interviews/                 # Interview management views
│   │   ├── candidate/                  # Candidate interface views
│   │   ├── reviews/                    # Review system views
│   │   └── dashboard.blade.php         # Main dashboard
│   └── css/                            # Styling
└── routes/
    └── web.php                         # Application routes
```

## 🛠️ Technical Stack

- **Backend**: Laravel 11 (PHP 8.1+)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Authentication**: Laravel's built-in auth system
- **Email**: Laravel Brevo Mail Service
- **File Storage**: Laravel's file storage system
- **JavaScript**: Vanilla JS for video recording and UI interactions

## 🔧 Configuration

### Email Configuration
The application uses Laravel Brevo for email services. Configure in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email
MAIL_PASSWORD=your_brevo_password
MAIL_ENCRYPTION=tls
```

### File Storage
Video files are stored in `storage/app/public/videos/`. Ensure the storage link is created:
```bash
php artisan storage:link
```

## 🚨 Known Limitations

1. **Video Storage**: Currently stores video files locally. For production, consider cloud storage (AWS S3, etc.)
2. **Email Service**: Requires Brevo account for email functionality
3. **Video Processing**: Basic video recording without advanced processing features
4. **Real-time Features**: No real-time collaboration (WebSocket integration needed)
5. **Mobile App**: Web-only, no native mobile app
6. **Advanced Analytics**: Basic statistics, no advanced analytics dashboard

## 🎬 Demo Video Requirements

The application is ready for a 3-8 minute Loom video demonstrating:

1. **Sign in/Sign up** (30 seconds)
   - Show login with test credentials
   - Demonstrate role-based dashboard access

2. **Creating an Interview** (2 minutes)
   - Admin creates new interview
   - Adds title, description, questions
   - Shows interview management interface

3. **Candidate Recording/Uploading** (2 minutes)
   - Access interview via email link
   - Fill candidate details
   - Record video answers
   - Submit interview

4. **Reviewer Viewing and Scoring** (2 minutes)
   - Reviewer views submission
   - Watches video responses
   - Provides scores and comments
   - Shows review management

## 📞 Support

For technical support or questions:
- Check the code comments for implementation details
- Review the Laravel documentation for framework-specific questions
- All core functionality is implemented and tested

## 📄 License

This project is for demonstration purposes. Please ensure compliance with any applicable licenses for production use.

---

**🎯 Ready for Demo!** All core functionality is implemented and working correctly. The application provides a complete video interview platform with professional features and modern UI.