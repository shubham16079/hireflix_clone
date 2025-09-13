# 🔑 Default Login Credentials

This document contains the default login credentials for the Hireflix Clone application. These are created automatically when you run the database seeder.

## 👤 User Accounts

### 🔑 Admin Users
These users have full access to the system and can:
- Create and manage interviews
- Send invitations to candidates
- Share interviews with reviewers
- View all submissions and reviews
- Export data

| Email | Password | Role |
|-------|----------|------|
| `admin@hireflixclone.com` | `password` | Admin |
| `hr@hireflixclone.com` | `password` | Admin |

### 👥 Reviewer Users
These users can:
- Review candidate submissions
- Score and comment on interviews
- View assigned interviews

| Email | Password | Role |
|-------|----------|------|
| `reviewer@hireflixclone.com` | `password` | Reviewer |
| `john.reviewer@hireflixclone.com` | `password` | Reviewer |

### 💡 Candidate Access
- **Candidates do NOT need accounts**
- They access interviews via email links sent to them
- Each interview generates a unique token-based URL
- Candidates enter their details when starting the interview

## 🎯 Sample Data

When you run the seeder, the following sample data is created:

### 📋 Sample Interviews
1. **Software Developer Interview**
   - 5 technical questions
   - 5-minute video limit
   - Retake allowed

2. **Marketing Manager Interview**
   - 4 marketing-focused questions
   - 4-minute video limit
   - Retake allowed

3. **Customer Support Interview**
   - 4 customer service questions
   - 3-minute video limit
   - No retake allowed

## 🚀 Quick Start

1. **Login as Admin:**
   - Go to `/login`
   - Use: `admin@hireflixclone.com` / `password`
   - Create your first interview or use sample data

2. **Test as Reviewer:**
   - Login with: `reviewer@hireflixclone.com` / `password`
   - Review sample submissions (if any exist)

3. **Test Candidate Flow:**
   - Create an interview as admin
   - Send invitation to a test email
   - Access the interview link as a candidate

## 🔒 Security Note

**⚠️ Important:** These are default credentials for development and testing only. In production:

1. **Change all passwords** to strong, unique passwords
2. **Remove or disable** default accounts
3. **Create new accounts** with proper credentials
4. **Remove the credentials card** from the login page
5. **Delete this file** or move it to a secure location

## 🛠️ Customization

To customize the default users, edit:
- `database/seeders/UserSeeder.php` - Modify user details
- `database/seeders/SampleDataSeeder.php` - Modify sample interviews

To remove the credentials display:
- Delete or comment out `@include('auth.login-credentials')` in `resources/views/auth/login.blade.php`

---

**Happy Testing! 🎬**
