# Dearly — Relational Database Architecture Specification

**Database Standard:** SQL-92 / ANSI SQL compliant. Tested on SQLite 3 (zero-configuration development and automated testing) and MySQL 8.0+ / MariaDB 10.5+ (production).  
**Character Encoding:** `utf8mb4` with collation `utf8mb4_unicode_ci`.

---

## 1. Schema Overview & Entity Relationship Summary

The Dearly data architecture is organized into 9 functional domains:
1. **Identity & Authorization**: `users`, `user_profiles`, `roles`, `permissions`, `role_user`, `permission_role`, `oauth_accounts`.
2. **Privacy & Postal Addressing**: `mailing_codes`, `correspondence_connections`, `blocked_users`.
3. **Questionnaires & Compatibility**: `questionnaires`, `questions`, `question_options`, `user_questionnaire_attempts`, `questionnaire_answers`, `match_results`.
4. **Letters & Postal Stationery**: `stationery_themes`, `envelope_designs`, `postage_stamps`, `wax_seals`, `letters`, `letter_threads`, `letter_events`.
5. **Abuse Control & Throttling**: `daily_sending_counters`, `user_reports`, `moderation_actions`.
6. **Communications & Delivery**: `notifications`, `email_delivery_logs`.
7. **Platform Configuration & Content**: `application_settings`, `content_pages`, `translations`.
8. **Security & Compliance**: `admin_audit_logs`.

---

## 2. Table Specifications

### 2.1 Identity & Authorization

#### `users`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `name`: `VARCHAR(255)` (Internal / OAuth name)
- `email`: `VARCHAR(255) UNIQUE` (Private, never exposed)
- `email_verified_at`: `TIMESTAMP NULL`
- `password`: `VARCHAR(255) NULL` (For admin / local dev accounts)
- `role`: `VARCHAR(50) DEFAULT 'user'` ('super_admin', 'admin', 'moderator', 'user')
- `is_suspended`: `BOOLEAN DEFAULT FALSE`
- `suspension_reason`: `TEXT NULL`
- `suspended_at`: `TIMESTAMP NULL`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `user_profiles`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED UNIQUE FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `nickname`: `VARCHAR(100) UNIQUE NOT NULL` (Public pseudonym)
- `age`: `INTEGER UNSIGNED NOT NULL` (Minimum 18 enforced)
- `gender`: `VARCHAR(50) NULL` ('female', 'male', 'non_binary', 'prefer_not_to_say')
- `preferred_language`: `VARCHAR(10) DEFAULT 'en'`
- `country`: `VARCHAR(100) NULL`
- `bio`: `TEXT NULL`
- `interests`: `JSON NULL` (Array of tags, e.g. ["Reading", "Analog Photography", "Tea", "Vintage Postcards"])
- `communication_preferences`: `JSON NULL` (Response frequency, letter length preference)
- `is_matching_paused`: `BOOLEAN DEFAULT FALSE`
- `profile_visibility`: `VARCHAR(50) DEFAULT 'public'`
- `is_onboarded`: `BOOLEAN DEFAULT FALSE`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `oauth_accounts`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `provider`: `VARCHAR(50)` ('google')
- `provider_user_id`: `VARCHAR(255)`
- `created_at`, `updated_at`: `TIMESTAMP`
- `UNIQUE(provider, provider_user_id)`

---

### 2.2 Privacy & Postal Addressing

#### `mailing_codes`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED UNIQUE FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `code`: `VARCHAR(32) UNIQUE NOT NULL` (Index, format `DEARLY-XXXX-YYYY`)
- `is_active`: `BOOLEAN DEFAULT TRUE`
- `permissions_policy`: `VARCHAR(50) DEFAULT 'anyone'` ('anyone', 'accepted_connections', 'require_approval')
- `created_at`, `updated_at`: `TIMESTAMP`

#### `correspondence_connections`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_one_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `user_two_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `status`: `VARCHAR(50) DEFAULT 'connected'` ('pending', 'connected', 'dismissed', 'blocked')
- `connected_at`: `TIMESTAMP NULL`
- `created_at`, `updated_at`: `TIMESTAMP`
- `UNIQUE(user_one_id, user_two_id)`

#### `blocked_users`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE` (The blocker)
- `blocked_user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE` (The blocked)
- `reason`: `VARCHAR(255) NULL`
- `created_at`: `TIMESTAMP`
- `UNIQUE(user_id, blocked_user_id)`

---

### 2.3 Questionnaires & Compatibility

#### `questionnaires`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `title`: `VARCHAR(255) NOT NULL`
- `length`: `INTEGER UNSIGNED NOT NULL` (20, 30, 40, 50)
- `version`: `INTEGER UNSIGNED DEFAULT 1`
- `is_active`: `BOOLEAN DEFAULT TRUE`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `questions`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `questionnaire_id`: `BIGINT UNSIGNED NULL FOREIGN KEY -> questionnaires(id) ON DELETE SET NULL`
- `dimension`: `VARCHAR(100) NOT NULL` (e.g. 'communication_style', 'values', 'creative_interests')
- `question_text`: `TEXT NOT NULL`
- `weight`: `DECIMAL(4,2) DEFAULT 1.00`
- `is_active`: `BOOLEAN DEFAULT TRUE`
- `order_index`: `INTEGER DEFAULT 0`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `question_options`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `question_id`: `BIGINT UNSIGNED FOREIGN KEY -> questions(id) ON DELETE CASCADE`
- `option_key`: `VARCHAR(10) NOT NULL` ('A', 'B', 'C', 'D', 'E')
- `option_text`: `TEXT NOT NULL`
- `score_value`: `INTEGER DEFAULT 1` (Coordinate or trait index for dimensional distance calculation)
- `order_index`: `INTEGER DEFAULT 0`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `user_questionnaire_attempts`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `questionnaire_id`: `BIGINT UNSIGNED FOREIGN KEY -> questionnaires(id) ON DELETE CASCADE`
- `questionnaire_length`: `INTEGER UNSIGNED NOT NULL`
- `is_completed`: `BOOLEAN DEFAULT FALSE`
- `completed_at`: `TIMESTAMP NULL`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `questionnaire_answers`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `attempt_id`: `BIGINT UNSIGNED FOREIGN KEY -> user_questionnaire_attempts(id) ON DELETE CASCADE`
- `question_id`: `BIGINT UNSIGNED FOREIGN KEY -> questions(id) ON DELETE CASCADE`
- `selected_option_id`: `BIGINT UNSIGNED FOREIGN KEY -> question_options(id) ON DELETE CASCADE`
- `created_at`, `updated_at`: `TIMESTAMP`
- `UNIQUE(attempt_id, question_id)`

#### `match_results`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `matched_user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `compatibility_score`: `DECIMAL(5,2) NOT NULL` (0.00 - 100.00)
- `shared_dimensions`: `JSON NULL` (Breakdown of high-scoring traits)
- `is_notified`: `BOOLEAN DEFAULT FALSE`
- `status`: `VARCHAR(50) DEFAULT 'active'` ('active', 'connected', 'dismissed')
- `created_at`, `updated_at`: `TIMESTAMP`
- `UNIQUE(user_id, matched_user_id)`

---

### 2.4 Letters & Postal Collectibles

#### `stationery_themes`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `slug`: `VARCHAR(100) UNIQUE NOT NULL`
- `name`: `VARCHAR(255) NOT NULL`
- `background_css`: `VARCHAR(255)`
- `text_color`: `VARCHAR(50)`
- `border_style`: `VARCHAR(255) NULL`
- `is_active`: `BOOLEAN DEFAULT TRUE`
- `order_index`: `INTEGER DEFAULT 0`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `postage_stamps`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `slug`: `VARCHAR(100) UNIQUE NOT NULL`
- `name`: `VARCHAR(255) NOT NULL`
- `theme_category`: `VARCHAR(100)`
- `image_svg_path`: `VARCHAR(255)`
- `is_active`: `BOOLEAN DEFAULT TRUE`
- `order_index`: `INTEGER DEFAULT 0`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `wax_seals`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `slug`: `VARCHAR(100) UNIQUE NOT NULL`
- `name`: `VARCHAR(255) NOT NULL`
- `color_hex`: `VARCHAR(50)`
- `symbol_svg`: `TEXT`
- `is_active`: `BOOLEAN DEFAULT TRUE`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `letter_threads`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `participant_one_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `participant_two_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `last_letter_at`: `TIMESTAMP NULL`
- `created_at`, `updated_at`: `TIMESTAMP`

#### `letters`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `thread_id`: `BIGINT UNSIGNED NULL FOREIGN KEY -> letter_threads(id) ON DELETE SET NULL`
- `sender_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `recipient_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `reply_to_letter_id`: `BIGINT UNSIGNED NULL FOREIGN KEY -> letters(id) ON DELETE SET NULL`
- `title`: `VARCHAR(255) NOT NULL`
- `body`: `LONGTEXT NOT NULL`
- `greeting`: `VARCHAR(100) NULL`
- `signoff`: `VARCHAR(100) NULL`
- `stationery_theme_id`: `BIGINT UNSIGNED NULL FOREIGN KEY -> stationery_themes(id) ON DELETE SET NULL`
- `postage_stamp_id`: `BIGINT UNSIGNED NULL FOREIGN KEY -> postage_stamps(id) ON DELETE SET NULL`
- `wax_seal_id`: `BIGINT UNSIGNED NULL FOREIGN KEY -> wax_seals(id) ON DELETE SET NULL`
- `status`: `VARCHAR(50) DEFAULT 'draft'` ('draft', 'queued', 'sent', 'delivered', 'read', 'archived', 'rejected')
- `is_read`: `BOOLEAN DEFAULT FALSE`
- `read_at`: `TIMESTAMP NULL`
- `sent_at`: `TIMESTAMP NULL`
- `delivered_at`: `TIMESTAMP NULL`
- `sender_deleted`: `BOOLEAN DEFAULT FALSE`
- `recipient_deleted`: `BOOLEAN DEFAULT FALSE`
- `created_at`, `updated_at`: `TIMESTAMP`
- `INDEX(sender_id, status)`
- `INDEX(recipient_id, status)`

#### `letter_events`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `letter_id`: `BIGINT UNSIGNED FOREIGN KEY -> letters(id) ON DELETE CASCADE`
- `event_type`: `VARCHAR(50) NOT NULL` ('created', 'sealed', 'dispatched', 'delivered', 'opened', 'archived')
- `recorded_at`: `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`
- `metadata`: `JSON NULL`

---

### 2.5 Abuse Control & Throttling

#### `daily_sending_counters`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `date`: `DATE NOT NULL`
- `letters_sent_count`: `INTEGER UNSIGNED DEFAULT 0`
- `first_contacts_count`: `INTEGER UNSIGNED DEFAULT 0`
- `created_at`, `updated_at`: `TIMESTAMP`
- `UNIQUE(user_id, date)`

#### `user_reports`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `reporter_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `reported_user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `letter_id`: `BIGINT UNSIGNED NULL FOREIGN KEY -> letters(id) ON DELETE SET NULL`
- `reason`: `VARCHAR(100) NOT NULL`
- `details`: `TEXT NULL`
- `status`: `VARCHAR(50) DEFAULT 'open'` ('open', 'reviewed', 'action_taken', 'dismissed')
- `admin_notes`: `TEXT NULL`
- `created_at`, `updated_at`: `TIMESTAMP`

---

### 2.6 Notifications & Audit

#### `notifications`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `user_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE CASCADE`
- `type`: `VARCHAR(100) NOT NULL` ('match_found', 'letter_received', 'letter_read', 'reply_received', 'system')
- `title`: `VARCHAR(255) NOT NULL`
- `message`: `TEXT NOT NULL`
- `action_url`: `VARCHAR(255) NULL`
- `is_read`: `BOOLEAN DEFAULT FALSE`
- `read_at`: `TIMESTAMP NULL`
- `created_at`, `updated_at`: `TIMESTAMP`
- `INDEX(user_id, is_read)`

#### `application_settings`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `key`: `VARCHAR(100) UNIQUE NOT NULL`
- `value`: `LONGTEXT NULL`
- `type`: `VARCHAR(50) DEFAULT 'string'` ('string', 'integer', 'boolean', 'json')
- `created_at`, `updated_at`: `TIMESTAMP`

#### `admin_audit_logs`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `admin_id`: `BIGINT UNSIGNED FOREIGN KEY -> users(id) ON DELETE SET NULL`
- `action`: `VARCHAR(100) NOT NULL`
- `target_type`: `VARCHAR(100) NULL`
- `target_id`: `BIGINT UNSIGNED NULL`
- `ip_address`: `VARCHAR(45) NULL`
- `changes`: `JSON NULL`
- `created_at`: `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`
