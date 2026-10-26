# Dearly — Implementation Plan & Architecture Specification
**Brand Tagline:** *"Letters Find Their Way."*
**Document Version:** 1.0.0
**Status:** Approved & Active Implementation

---

## 1. Executive Summary & Product Vision

Dearly is a global, nostalgic digital pen-pal and letter-writing platform engineered to revive the intimate, slow, and meaningful correspondence experience of the 1980s and 1990s. Unlike instantaneous messaging tools or dating apps, Dearly emphasizes:
- **Serendipity & Compatibility**: Discovering pen friends through a server-side weighted questionnaire engine (20, 30, 40, or 50 questions) covering 17+ personality dimensions.
- **Privacy by Design**: Anonymous public nicknames and private recipient-specific mailing codes (`DEARLY-XXXX-YYYY`) that prevent identity enumeration and protect personal email addresses.
- **Atmospheric Correspondence**: Authentic stationery papers, collectible virtual postage stamps, digital wax seals, realistic folding and envelope sealing interactions, and respectful delivery cadences.
- **Safety, Moderation & Control**: Strict daily letter limits, block and report mechanisms, read receipts triggered only when letters are legitimately opened by recipients, and granular role-based administrative control.

---

## 2. Technology Stack & Runtime Environment

| Layer | Selected Technology | Notes |
|---|---|---|
| **Operating System** | Windows / Linux compatible | Tested on Windows 11 with PHP 8.2+ CLI |
| **Framework** | Laravel 11.x | Modern modular monolith architecture |
| **Language** | PHP 8.2.33+ | Extensions enabled: `pdo_sqlite`, `pdo_mysql`, `curl`, `mbstring`, `openssl`, `zip`, `intl`, `gd`, `fileinfo` |
| **Database** | SQLite (local/testing zero-config) & MySQL/MariaDB (production) | Dual-driver support with database migrations & seeders |
| **Frontend Core** | Blade Templates, Tailwind CSS, Alpine.js | Responsive design, vintage aesthetic, accessible WCAG 2.2 AA compliant |
| **Build Tooling** | Vite, PostCSS, Autoprefixer | Node.js v24.16.0 / npm v11.13.0 |
| **Authentication** | Google OAuth (Socialite) + Local Secure Dev/Admin Auth | Clean OAuth callback, state verification, session regeneration |
| **Mail & Queues** | Laravel Mail & Database Queue Workers | Idempotent notifications, retry policies, HTML & text templates |
| **Localization** | Multi-locale JSON & PHP translations | English (`en`), Bengali (`bn`), Hindi (`hi`), Arabic (`ar` with RTL), Spanish (`es`), French (`fr`), Portuguese (`pt`) |
| **Testing** | PHPUnit / Pest testing suite | Feature tests, Unit tests, and End-to-End workflow validation |

---

## 3. Architecture & Directory Structure

```text
Dearly-Project/
├── app/
│   ├── Enums/
│   │   ├── LetterStatus.php
│   │   ├── UserRole.php
│   │   ├── MatchStatus.php
│   │   └── ReportReason.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/ (OAuth & Login)
│   │   │   ├── OnboardingController.php
│   │   │   ├── PostOfficeController.php
│   │   │   ├── DiscoveryController.php
│   │   │   ├── QuestionnaireController.php
│   │   │   ├── LetterComposerController.php
│   │   │   ├── MailboxController.php
│   │   │   ├── MailingCodeController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── SafetyController.php
│   │   │   └── Admin/ (Dashboard, Users, Questions, Letters, Stationery, Settings, Audit)
│   │   ├── Middleware/ (Authenticate, RoleMiddleware, EnsureProfileComplete, ThrottleLetters)
│   │   └── Requests/ (Form requests for letters, profile, questionnaires, moderation)
│   ├── Jobs/
│   │   ├── ProcessMatchNotificationsJob.php
│   │   ├── SendLetterDeliveryJob.php
│   │   └── SendReadReceiptJob.php
│   ├── Mail/ (MatchFoundMail, NewLetterMail, LetterReadMail)
│   ├── Models/
│   │   ├── User.php
│   │   ├── UserProfile.php
│   │   ├── MailingCode.php
│   │   ├── Questionnaire.php
│   │   ├── Question.php
│   │   ├── QuestionOption.php
│   │   ├── UserQuestionnaireAttempt.php
│   │   ├── QuestionnaireAnswer.php
│   │   ├── MatchResult.php
│   │   ├── CorrespondenceConnection.php
│   │   ├── Letter.php
│   │   ├── LetterThread.php
│   │   ├── LetterEvent.php
│   │   ├── StationeryTheme.php
│   │   ├── PostageStamp.php
│   │   ├── WaxSeal.php
│   │   ├── Notification.php
│   │   ├── DailySendingCounter.php
│   │   ├── BlockedUser.php
│   │   ├── UserReport.php
│   │   ├── ApplicationSetting.php
│   │   └── AdminAuditLog.php
│   ├── Policies/ (LetterPolicy, ProfilePolicy, MatchPolicy)
│   └── Services/
│       ├── CompatibilityScoringService.php
│       ├── MailingCodeService.php
│       ├── LetterDeliveryService.php
│       ├── DailyLimitService.php
│       └── AdminAuditService.php
├── config/ (app, auth, dearly, database, mail, queue)
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/ (Settings, Themes, Stamps, Seals, Questions [100+ items], Admin)
├── lang/ (en, bn, hi, ar, es, fr, pt)
├── resources/
│   ├── css/ (custom vintage paper textures, animations, typography, rtl)
│   ├── js/ (Alpine letter folding, envelope sealing, audio toggles)
│   └── views/
│       ├── components/ (envelopes, stamps, wax-seals, paper-sheet, badges)
│       ├── layouts/ (public, authenticated post-office, admin)
│       ├── public/ (landing, about, how-it-works, privacy, terms, faq)
│       ├── onboarding/ (step-by-step profile setup)
│       ├── discovery/ (questionnaire taking, match discovery)
│       ├── letters/ (composer, envelope folding studio, reader, thread view)
│       ├── mailbox/ (inbox, sent, drafts, archive)
│       └── admin/ (full management suites)
├── routes/
│   ├── web.php (Public, Auth, PostOffice, Correspondence)
│   └── admin.php (Admin dashboard, resource CRUDs)
└── tests/
    ├── Feature/ (AuthTest, QuestionnaireTest, MatchingTest, LetterFlowTest, AdminTest)
    └── Unit/ (CompatibilityScorerTest, MailingCodeTest, DailyLimitTest)
```

---

## 4. Phased Implementation Roadmap

### Phase 1: Discovery, System Audit & Architecture Alignment (Current)
- Audit runtime environment, binaries, database options, Node.js, and toolchains.
- Draft `IMPLEMENTATION_PLAN.md`, `FEATURE_CHECKLIST.md`, and `DATABASE_DESIGN.md`.
- Establish project conventions, security guardrails, and testing standards.

### Phase 2: Foundation & Core Laravel Setup
- Initialize Laravel 11 application in project root.
- Configure `.env` and `.env.example` with SQLite and MySQL support.
- Implement complete migration suite for all 25+ relational entities.
- Implement comprehensive seeders (system settings, stationery themes, postage stamps, wax seals, and an authentic 100+ question bank across 17 dimensions).
- Implement role-based access control (RBAC: Super Administrator, Administrator, Moderator, User).
- Implement initial administrator creation command / seeder.

### Phase 3: Authentication, Onboarding & User Profiles
- Google OAuth Socialite integration with local mock/test driver fallback.
- First-time onboarding wizard (nickname validation, age/adult safeguard, language, optional region, friendship preferences).
- Public vs. Private data isolation (never exposing emails or real identities).
- Unique mailing code generation (`DEARLY-XXXX-YYYY`) per user.

### Phase 4: Friendship Discovery & MCQ Matching Engine
- Configurable questionnaire versions (20, 30, 40, 50 questions).
- Autosaving interactive question flow with progress tracking.
- Explainable weighted compatibility scoring algorithm with configurable threshold (default 90%).
- Match discovery dashboard with shared interest indicators.
- In-app match notifications and queued email dispatches with anti-spam / duplicate prevention.

### Phase 5: Letter-Writing Studio, Envelopes & Lifecycle
- Calming, vintage stationery editor (multiple themes, fonts, autosave drafts).
- Folding sequence and envelope packaging interactive UI (envelope selection, stamp affixing, wax sealing).
- Backend letter delivery lifecycle (`draft` -> `queued` -> `sent` -> `delivered` -> `read`).
- Direct mailing code recipient lookup with strict privacy protections.
- Recipient mailbox, envelope opening interaction, and authentic read receipt generation.
- Correspondence thread view.

### Phase 6: Notifications, Daily Limits & Abuse Prevention
- Configurable daily sending limits (letters/day, new contacts/day, length limits).
- Server-side concurrency-safe counter enforcement.
- Block and Report system with automatic message suppression.
- In-app notification center with read/unread tracking and category preferences.

### Phase 7: Global Role-Based Admin Panel
- Comprehensive metrics dashboard (users, letters, matches, delivery status).
- User management with suspension and role assignment.
- Question bank management (CRUD, weights, dimension tags, translations).
- Stationery, stamps, and seals catalog management.
- Dynamic application settings (brand colors, typography, match threshold, limits).
- Content management (FAQs, Privacy Policy, Terms, Guidelines).
- Immutable administrative audit logs.

### Phase 8: Internationalization (i18n), RTL & Accessibility
- Full language switching for 7 languages (EN, BN, HI, AR, ES, FR, PT).
- Right-to-Left (RTL) styling and layout mirroring for Arabic.
- WCAG 2.2 AA accessibility, contrast validation, and reduced-motion modes.

### Phase 9: Comprehensive Automated Testing & Verification
- Test coverage for authentication, questionnaires, matching algorithm, mailing codes, letter sending, and admin authorization.
- End-to-end integration test of the complete pen-pal workflow.
- Defect identification, bug fixing, and test re-runs.

### Phase 10: Documentation, Packaging & Handover
- Final update to `FEATURE_CHECKLIST.md` with honest completion statuses.
- Complete documentation suite (`README.md`, `SECURITY.md`, `DEPLOYMENT.md`, `TROUBLESHOOTING.md`).
- Verified zero-error build and runtime validation.
