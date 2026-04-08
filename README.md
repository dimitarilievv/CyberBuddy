# CyberBuddy – Online Safety Educational Platform

CyberBuddy is an educational web platform that teaches children (ages 10–13) online safety through interactive lessons, quizzes, real-life scenarios, AI-assisted feedback, and gamification.

The platform is designed for multiple roles: **children, parents, teachers, and admins**.

---

## 🚀 Core Features

- **Role-based learning platform**
  - User roles and permissions (child, parent, teacher, admin)
  - Parent–child relationship support
- **Structured learning content**
  - Categories, modules, lessons, resources, tags
- **Interactive cybersecurity scenarios**
  - Real-life decision-making scenarios
  - Multiple choices with consequences and safety scores
  - AI explanations for safer behavior
- **Quiz system**
  - Lesson-based quizzes with attempts, scoring, and feedback
  - Multiple question types support
- **Progress tracking**
  - Enrollment, lesson progress, completion status, time spent
- **Gamification**
  - Badges, streaks, points, leaderboard
- **Certificates**
  - Auto-generated PDF certificates for completed modules
- **AI-powered content and feedback**
  - AI-generated lessons, quizzes, and scenarios
  - AI interaction logging for monitoring and cost tracking
  - AI content suggestions with human approval workflow
- **Safety and moderation**
  - Reported content workflow
  - Activity logs and notifications

---

## 🛠️ Technology Stack

### Frontend
- **Laravel Blade** – server-rendered templates
- **Livewire 3** – reactive UI without a heavy JS framework
- **Tailwind CSS 3** – styling
- **Alpine.js** – lightweight frontend interactions
- **Chart.js** – progress and analytics charts

### Backend
- **PHP 8.2+**
- **Laravel 11**
- **Laravel Breeze** – authentication scaffolding
- **Spatie Permission** – roles & permissions
- **Spatie MediaLibrary** – file/media management
- **Barryvdh DomPDF** – PDF certificate generation

### AI
- **Gemini 2.5 Flash** – AI generation and feedback workflows

### Database
- **PostgreSQL 16**

---

## 🧱 Core Models

- User, UserProfile
- Category, Module, Lesson, Resource, Tag, MediaFile
- Scenario, ScenarioChoice, ScenarioAttempt
- Quiz, Question, QuizAttempt, QuestionAnswer
- Enrollment, UserProgress
- Badge, UserBadge, Leaderboard
- Certificate
- AIInteraction, AIContentSuggestion
- Notification, ActivityLog, ReportedContent

---

## 👥 Contributors

- **Dimitar Iliev**
- **Nina Kostanova**
- **Damjan Karadakoski**
