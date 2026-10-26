# Dearly — Letters Find Their Way

> A Global Nostalgic Digital Pen-Pal & Letter-Writing Platform.

Dearly recreates the emotional experience of having a pen friend in the 1980s and 1990s, adapted for today's digital world. Users discover compatible pen friends through a server-side weighted compatibility matching engine, compose thoughtful letters on authentic vintage stationery, fold and package them into digital envelopes sealed with virtual wax seals, and correspond using recipient-specific private mailing codes (`DEARLY-XXXX-YYYY`).

---

## Key Highlights

- **Nostalgic Vintage Aesthetic**: Warm paper ivory and aged tones, postal burgundy, muted brass, wax seals, vintage postage stamps, and realistic letter folding sequences.
- **Privacy by Design**: No personal email addresses, real names, or internal database IDs are ever exposed. Users interact strictly via nicknames and private mailing codes.
- **Explainable Compatibility Matching**: 100+ candidate MCQ question bank across 17 personality dimensions with configurable questionnaire lengths (20, 30, 40, 50 questions) and a 90% threshold engine.
- **Authentic Read Receipts**: Triggered only when a recipient opens and renders the letter content.
- **Abuse Prevention & Limits**: Concurrency-safe daily sending limits, report/moderation queue, and immediate user blocking.
- **Global Role-Based Admin Panel**: System metrics, user management, question bank management, stationery customization, dynamic branding, content management, and immutable audit logs.
- **Multilingual Support**: English, Bengali, Hindi, Arabic (with RTL), Spanish, French, and Portuguese.

---

## Getting Started

### Prerequisites
- PHP 8.2+ with `pdo_sqlite` or `pdo_mysql`, `curl`, `mbstring`, `zip`, `intl`, `gd`, `fileinfo`
- Composer 2.x
- Node.js 18+ and npm

### Local Setup
1. Clone the repository and install dependencies:
   ```bash
   composer install
   npm install
   ```
2. Copy environment file and generate key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Run migrations and seed database:
   ```bash
   php artisan migrate --seed
   ```
4. Build frontend assets:
   ```bash
   npm run build
   ```
5. Start development server:
   ```bash
   php artisan serve
   ```
   Open `http://127.0.0.1:8000` in your browser.

---

## Documentation Index
- [Master Specification](file:///d:/Project/JS/Dearly-Project/MASTER_SPECIFICATION.md)
- [Implementation Plan](file:///d:/Project/JS/Dearly-Project/IMPLEMENTATION_PLAN.md)
- [Feature Checklist](file:///d:/Project/JS/Dearly-Project/FEATURE_CHECKLIST.md)
- [Database Design](file:///d:/Project/JS/Dearly-Project/DATABASE_DESIGN.md)
- [Security & Safeguarding](file:///d:/Project/JS/Dearly-Project/SECURITY.md)
- [Deployment Guide](file:///d:/Project/JS/Dearly-Project/DEPLOYMENT.md)
- [Troubleshooting Runbook](file:///d:/Project/JS/Dearly-Project/TROUBLESHOOTING.md)
