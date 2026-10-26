# DEARLY — THE ULTIMATE MASTER BUILD SPECIFICATION
## A Global Nostalgic Digital Pen-Pal & Letter-Writing Platform

## 1. YOUR ROLE AND MISSION

Act as a senior product architect, Laravel/PHP engineer, UI/UX designer, database architect, security engineer, QA engineer, and DevOps engineer working as one team.

Build a complete, working, production-oriented web application called **Dearly**.

Brand tagline: **"Letters Find Their Way."**

The product recreates the emotional experience of having a pen friend in the 1980s and 1990s, adapted for today's digital world.

Users should discover people with compatible personalities, write meaningful letters, save unfinished letters in drafts, place letters inside beautifully designed digital envelopes, seal them, send them using recipient-specific mailing codes, and receive notifications when a letter arrives or gets read.

This is NOT a conventional chat application, dating app, or generic social network.

It is a slow, thoughtful, privacy-first digital correspondence experience.

The application must be visually memorable, emotionally engaging, accessible, secure, international, responsive, and genuinely functional.

### Non-negotiable development rules

1. Inspect the repository and existing files before making changes.
2. If the project is empty, establish the complete project structure.
3. If an existing project exists, preserve useful functionality and avoid destructive rewrites.
4. Create a written implementation plan, technical checklist, and feature checklist.
5. Implement the actual backend, database, frontend, authentication, notifications, and admin controls.
6. Do not deliver only a landing page, static HTML prototype, Figma-like mockup, or nonfunctional dashboard.
7. Do not substitute hardcoded demo data for database-backed functionality.
8. Do not leave core features as TODO comments, dead buttons, or fake API responses.
9. Run the available tests, investigate failures, fix defects, and rerun the tests.
10. Document anything that genuinely requires an external credential or human configuration.
11. Never claim a test, deployment, email delivery, or integration succeeded unless there is evidence it succeeded.
12. Make reasonable product decisions independently. Ask questions only when a genuinely blocking decision cannot be resolved safely.

Continue working through implementation, testing, and documentation rather than stopping after generating a plan.

---

# 2. TECHNOLOGY STACK

Use a maintainable, conventional PHP architecture.

- Backend: PHP 8.3+ or a newer supported compatible version.
- Framework: latest stable Laravel release compatible with the selected PHP version.
- Database: MySQL 8+.
- Frontend: Laravel Blade, Tailwind CSS, and Alpine.js; use Livewire where it materially improves interactive experiences.
- Asset compilation: Vite.
- Authentication: Laravel-compatible Google OAuth integration.
- Authentication management: Laravel's supported authentication patterns, policies, middleware, and secure sessions.
- Email: Laravel Mail with configurable SMTP or a supported transactional email provider.
- Background processing: Laravel queues with a database queue initially and optional Redis support.
- Scheduling: Laravel Scheduler for scheduled notifications, cleanup, and matching jobs.
- Testing: PHPUnit or Pest, plus browser-level testing using a compatible supported tool.
- Deployment: conventional Linux hosting, VPS, or managed Laravel hosting.

Use a current compatible package set, commit the dependency lockfile, and document the actual tested runtime versions.

Prefer a modular monolith over unnecessary microservices.

Do not require Node.js in production merely to serve precompiled frontend assets. Document build-time requirements separately.

Keep the application suitable for future scaling without introducing unnecessary infrastructure at launch.

---

# 3. BRAND IDENTITY AND VISUAL DIRECTION

Create a premium nostalgic visual identity inspired by old stationery, vintage postal systems, handwritten letters, analog photography, and 1980s correspondence.

The interface should feel warm, intimate, quiet, collectible, and emotionally meaningful.

It must not look like a generic Bootstrap dashboard or a modern instant messaging clone.

### Suggested palette

- Paper Ivory: #F6F0E5
- Aged Paper: #E9DDC7
- Ink Brown: #342A25
- Postal Burgundy: #783C46
- Forest Green: #405B4B
- Muted Brass: #B79A61
- Dusty Blue: #778B99
- Soft Rose: #C58E8B

All colors must be configurable through the admin panel.

### Typography

Use elegant readable serif typography for headings, highly readable body typography, and optional handwriting-style fonts for decorative elements.

Use appropriately licensed fonts and locally hosted assets where practical.

Never use decorative handwriting fonts for long paragraphs, forms, navigation, or essential instructions.

### Visual elements

Include carefully designed versions of:

- Vintage postage stamps.
- Letter paper with subtle texture.
- Envelopes with different flap shapes.
- Wax-seal-inspired digital seals.
- Postal cancellation marks.
- Typewriter-inspired details.
- Old mailbox illustrations.
- Handwritten signatures.
- Decorative stationery collections.
- Vintage postcards.
- Paper clips, ribbons, and pressed-flower motifs.
- A quiet analog-inspired sound design, disabled by default.

Use CSS, SVG, and lightweight animations for reusable UI elements. Use optimized original or properly licensed images for decorative artwork.

Avoid excessive visual clutter, excessive animations, and large background images that make the application slow.

Provide a reduced-motion mode and a simpler interface option.

### Design quality requirements

- Fully responsive on mobile, tablet, laptop, and desktop.
- Consistent spacing, typography, shadows, and component styles.
- Attractive empty states, loading states, error states, and success states.
- Thoughtful micro-interactions.
- Accessible color contrast and keyboard navigation.
- A functional mobile navigation system.
- A coherent design system with reusable components.

Create a brand identity that is recognizable even without displaying the logo.

---

# 4. PUBLIC WEBSITE AND LANDING PAGE

Create a visually exceptional public landing page.

### Hero section

Headline:

"Somewhere in the world, someone is waiting to hear from you."

Supporting text:

"Rediscover the beautiful art of friendship, one letter at a time."

Primary CTA: "Find My Pen Friend"

Secondary CTA: "Explore the Post Office"

Display an illustrated vintage desk, letter, envelope, stamp, and postbox.

Use a subtle letter-opening or paper-unfolding animation.

### Other landing page sections

1. A short emotional introduction to the pen-pal concept.
2. How Dearly works in three or four illustrated steps.
3. A preview of the friendship matching experience.
4. An interactive sample letter and envelope.
5. A showcase of stationery and postage designs.
6. A privacy promise explaining what other users can and cannot see.
7. A community safety section.
8. A multilingual introduction.
9. A frequently asked questions section.
10. A final CTA to create an account.
11. A complete footer with policies, support, language selection, and contact information.

Do not fabricate user counts, testimonials, awards, or statistics.

Only display genuine statistics collected by the system and approved testimonials where available.

Make all public text, images, CTAs, and section visibility configurable from the admin panel.

---

# 5. AUTHENTICATION AND ONBOARDING

Implement Google OAuth login.

### Login requirements

- Google sign-in and sign-out.
- Secure OAuth state validation.
- Correct callback handling.
- Secure session regeneration after authentication.
- Account creation on first successful login.
- Existing account linking with verified identity rules.
- Account suspension and deletion handling.
- Session expiration and logout controls.
- Authentication failure and retry screens.

Never ask users for their Google password.

Do not make an unverified email address sufficient to link accounts.

Do not automatically expose the Google profile name, profile image, or email address to other members.

### First-time onboarding

After login, guide users through a pleasant, short onboarding flow.

Collect only information necessary for operating the service.

Required or configurable profile fields:

- Unique internal user ID.
- Private email address.
- Public nickname.
- Age or verified age range.
- Gender, if the user chooses to provide it.
- Preferred language.
- Country or broad region, optional.
- Interests.
- Friendship preferences.
- Profile visibility settings.
- Notification preferences.
- Matching questionnaire completion status.

For privacy, use a birth date only if genuinely needed. Prefer age verification or a minimum-age confirmation and age range where practical.

Do not expose the full birth date.

Allow users to edit their nickname, interests, language, and other permitted profile fields.

Validate nickname uniqueness, reserved words, profanity rules, and length limits.

Clearly distinguish private account information from public profile information.

### Age and safeguarding

Configure a minimum user age according to the operator's legal requirements and the jurisdictions in which the service operates.

Default to an adult-only service unless a separate, properly safeguarded age-appropriate product is intentionally designed.

Do not match adults with minors.

Implement age-appropriate access restrictions, reporting, and account enforcement.

---

# 6. THE FRIENDSHIP DISCOVERY ENGINE

This is the central feature of Dearly.

Users should find friends whose personalities, interests, values, communication preferences, and lifestyles are compatible.

The matching process must be understandable, configurable, and database-driven.

### Questionnaire lengths

Allow the user to choose:

- 20 questions.
- 30 questions.
- 40 questions.
- 50 questions.

Administrators can create additional questionnaire lengths in the future if supported by the configured question bank.

Only publish questionnaires containing the requested number of active, valid questions.

### Question types

The initial matching questionnaire should use single-answer MCQs.

Example:

**Question:** How do you prefer to spend a quiet weekend?

A. Reading a book with a cup of tea.

B. Exploring a new place.

C. Talking with close friends.

D. Learning a new skill.

E. Staying home and enjoying music.

Another example:

**Question:** What matters most in a friendship?

A. Honesty.

B. Humor.

C. Emotional understanding.

D. Shared interests.

E. Giving each other space.

Create an initial, carefully written bank of at least 100 candidate MCQs covering multiple personality and interest dimensions.

The active questions must be editable from the admin panel. Do not hardcode the questionnaire into frontend components.

### Suggested dimensions

- Communication style.
- Emotional openness.
- Preferred conversation depth.
- Introversion and social energy.
- Humor.
- Curiosity and learning.
- Music and arts.
- Reading and writing.
- Travel and exploration.
- Nature and outdoor activities.
- Daily routines.
- Personal values.
- Friendship expectations.
- Preferred response frequency.
- Creative interests.
- Cultural interests.
- Long-term correspondence preferences.

Avoid presenting unvalidated questionnaires as clinical psychological assessments.

Do not use sensitive personal attributes to determine friendship compatibility without a legitimate, transparent, consent-based reason.

### Question management

Administrators must be able to:

- Create, edit, archive, and reorder questions.
- Add, edit, and reorder answer options.
- Assign dimensions and weights.
- Set answer compatibility rules.
- Mark optional or required questions.
- Assign questions to questionnaire lengths.
- Create and manage questionnaire versions.
- Preview the complete questionnaire.
- Publish and unpublish questionnaires.
- Translate questions and options.
- Import and export questions.
- See completion and answer-distribution statistics.

Question edits must not silently corrupt existing completed questionnaires.

Store versioned question and answer snapshots or equivalent immutable records.

### Matching algorithm

Implement a server-side, explainable weighted compatibility algorithm.

The default threshold is 90%, configurable from the admin panel.

A match must be calculated from actual stored answers, not random numbers or hardcoded matches.

Possible approach:

For each shared answer dimension, calculate a normalized compatibility score between 0 and 1.

Apply the administrator-defined weight to each dimension.

Normalize the weighted sum to produce a final score from 0 to 100.

Ignore unanswered dimensions consistently, and require a configurable minimum number of shared valid answers.

Store the algorithm version, questionnaire version, calculation time, and score used for each matching result.

For binary or categorical questions, administrators can define compatible answers. For example, two different answers may be compatible if their underlying preferences align.

A 90% match means a high score according to this defined algorithm. It does not guarantee that two people will become real-life friends.

### Matching workflow

1. The user chooses a questionnaire length.
2. The system presents the questions in a pleasant, page-by-page flow.
3. Answers are autosaved securely.
4. Users can go back and revise answers before final submission.
5. The system validates questionnaire completion.
6. Matching runs against eligible users with compatible, completed questionnaires.
7. Only matches meeting the configured threshold are eligible for a successful-match notification.
8. Results appear in a dedicated Match Discovery page.
9. The user sees the other person's nickname, permitted age display, self-selected gender if shared, preferred language, shared interests, and match percentage.
10. The user can express interest, dismiss the suggestion, or request a match where that feature is enabled.
11. A correspondence connection is established only under the configured consent and matching rules.

Never send match emails repeatedly for the same unchanged match.

Use unique database constraints, idempotent jobs, and notification records to prevent duplicate notifications.

Support pagination and efficient matching queries.

Allow users to pause matching, hide their profile from discovery, or withdraw from matching entirely.

---

# 7. MATCH FOUND EMAIL

When a qualifying match is found, send an email to the relevant user.

Suggested subject:

"Your pen friend may be waiting for you."

Suggested body:

"Dear [Nickname],

Somewhere in the world, someone shares more of your interests and preferences than you might expect.

We found a potential pen friend with a compatibility score of [Score]%.

Visit Dearly to discover your match and decide whether you would like to begin a correspondence.

A beautiful friendship can start with a single letter.

With warmth,
The Dearly Post Office"

The email must:

- Use a beautiful responsive HTML template.
- Include a plain-text alternative.
- Use the user's selected language.
- Include a secure link to the authenticated match page.
- Avoid exposing the other person's email address.
- Avoid revealing private questionnaire answers.
- Respect notification preferences and unsubscribe rules where applicable.
- Avoid claiming that compatibility guarantees friendship.

Also support in-app notifications.

Email delivery should use a queue, retry rules, delivery logging, and appropriate failure handling.

---

# 8. THE PRIVATE MAILING CODE SYSTEM

Every eligible recipient receives a unique, recipient-specific public mailing code.

Example display format:

DEARLY-7K9M-2Q8P

This is an illustrative format only. Generate codes securely and avoid predictable sequential identifiers.

### Code requirements

- Each code belongs to one recipient.
- A code must never reveal the recipient's internal database ID or email address.
- Users can copy or share their own code.
- Other users can submit a code to initiate a letter.
- A valid code resolves only to the intended eligible recipient.
- Invalid, revoked, expired, blocked, or suspended-recipient codes must not accept letters.
- Codes must not allow a sender to enumerate other accounts.
- Support code rotation, revocation, and reissue.
- Rate-limit code lookup and failed attempts.
- Prevent duplicate code assignment through database constraints.

### Important privacy distinction

The mailing code is an address for correspondence, not an authentication credential.

It identifies the intended recipient but does not authorize access to the recipient's account or letters.

A person who knows a recipient's code can attempt to send a letter only if that recipient's permissions and the platform's rules allow it.

Do not falsely promise that a code is usable by only one person if multiple senders are permitted.

Instead, implement configurable rules:

- Allow letters from any eligible sender.
- Require an accepted correspondence connection.
- Require recipient approval for first-time senders.
- Allow only explicitly approved senders.

Make the selected policy clear to users.

The recipient's email, real name, login provider identity, and account ID must never be revealed through the code.

---

# 9. THE LETTER-WRITING STUDIO

Build an immersive letter composer that feels different from writing an ordinary email.

The letter should look like a real piece of stationery.

### Paper selection

Provide multiple themes:

- Classic Ivory.
- Old Library.
- Vintage Rose.
- Forest Letter.
- Blue Airmail.
- Midnight Correspondence.
- Botanical Garden.
- Minimal Modern Paper.
- Seasonal and special editions.

Allow administrators to add and edit stationery themes, colors, backgrounds, decorative elements, and availability.

### Writing tools

Include:

- Letter title or subject.
- Main letter body.
- Optional greeting and signature.
- Character counter.
- Word count.
- Autosave.
- Manual save.
- Draft preview.
- Undo and redo where supported.
- Letter formatting.
- Font selection from an approved list.
- Line spacing.
- Text alignment.
- Optional decorative initials.
- Emoji support.
- Spellchecking through browser capabilities where available.

Keep the default editor calm and uncluttered.

The recipient should not see the sender's real name or email address unless the sender explicitly chooses an allowed disclosure feature.

### Draft system

Users can:

- Create drafts.
- Edit existing drafts.
- Rename drafts.
- Preview drafts.
- Duplicate a draft.
- Delete drafts.
- Continue writing on another device.
- Save stationery preferences.
- Keep a letter private until sending.
- Receive a warning before leaving with unsaved changes.

Drafts must remain private to their owner.

Use server-side authorization on every draft operation.

### Envelope system

After writing, the user clicks:

"Fold My Letter"

Show a smooth sequence:

1. The letter folds.
2. The selected envelope appears.
3. The letter slides into the envelope.
4. The recipient's nickname or mailing code appears on the envelope.
5. A stamp can be selected.
6. A decorative digital wax seal is applied.
7. The user previews the sealed letter.
8. The user confirms sending.

This must be a real sending workflow, not merely an animation.

Provide a reduced-motion alternative.

The animation must never delay or prevent the actual send operation.

### Postage and collectible stationery

Create a collection of virtual stamps and seals.

Examples:

- First Letter.
- Midnight Blue.
- Little Wildflower.
- Golden Friendship.
- Airmail Memories.
- Autumn Leaves.
- The Old Library.
- Special Anniversary Stamp.

Administrators can add, edit, reorder, enable, disable, and schedule stationery items.

Keep ordinary stationery accessible without payment. If monetization is introduced later, it must not compromise privacy or create misleading charges.

---

# 10. LETTER DELIVERY AND LIFECYCLE

Implement a clear letter lifecycle.

Suggested statuses:

- draft
- queued
- sent
- delivered
- read
- archived
- rejected
- recalled, only when the configured rules permit it

A draft is not a sent letter.

A sent letter should not be marked as delivered or read until the corresponding event actually occurs.

### Sending procedure

1. Authenticate the sender.
2. Validate CSRF protection and request integrity.
3. Validate the recipient code.
4. Check recipient eligibility and correspondence permissions.
5. Validate letter content and length.
6. Apply the sender's daily sending limit.
7. Check blocking, reporting, suspension, and abuse-prevention rules.
8. Prevent duplicate submission.
9. Persist the letter and its immutable sender/recipient references.
10. Record a delivery event.
11. Queue relevant notifications.
12. Update the sender's sent-letter view.
13. Make the letter available in the intended recipient's mailbox.

Use a database transaction for operations that must succeed together.

Ensure that retries do not send duplicate letters.

### Read receipts

When a recipient opens a letter and its content has successfully loaded, record a read event.

Display to the sender:

"Your letter has been read."

Optionally show the reading time, subject to privacy settings.

A recipient must not be able to mark another person's letter as read.

Do not mark a letter as read merely because an email notification was delivered or opened.

Do not use invasive tracking pixels to infer reading.

For privacy, offer a configurable read-receipt policy, but explain that read receipts cannot prove that someone fully understood the letter.

### Replies

Support replying to received letters.

A reply should preserve the correspondence thread and show the previous letter's relevant context to authorized participants.

Provide a separate letter-thread view, not an instant messaging interface.

Keep sender and recipient identity references immutable for historical records.

---

# 11. MAILBOX AND CORRESPONDENCE DASHBOARD

Create a personal post office dashboard.

### Main navigation

- My Post Office.
- Find a Pen Friend.
- My Matches.
- Write a Letter.
- Drafts.
- Received Letters.
- Sent Letters.
- Correspondence.
- My Stamps.
- Notifications.
- Profile & Privacy.
- Settings.
- Help & Safety.

### Mailbox design

Received letters should appear as illustrated envelopes or paper cards.

Display:

- Sender nickname.
- Permitted age display.
- Self-selected gender, if shared.
- Envelope design.
- Received date.
- Subject preview.
- Read/unread state.
- Relevant conversation thread.
- A subtle visual indicator for new mail.

Clicking an unopened envelope starts the envelope-opening animation and then displays the actual letter.

Do not reveal letter contents before the authorized recipient opens the letter.

### Empty states

Examples:

"No letters yet. Somewhere out there, your first one is waiting."

"Your drafts are little thoughts waiting to become letters."

"No matches today. Sometimes the best friendships take a little time."

Provide helpful actions and illustrations for every empty state.

### Organization

Allow users to:

- Search their own correspondence.
- Filter by unread/read.
- Filter by sender nickname.
- Filter by date.
- Archive letters.
- Restore archived letters.
- Pin or favorite a correspondence.
- Delete eligible items according to the retention policy.

A sender deleting their local sent copy must not silently erase the recipient's received copy.

---

# 12. NOTIFICATION CENTER

Implement a genuine notification center with database-backed notifications.

Notification types include:

- A compatible pen friend was found.
- A new letter arrived.
- A letter was read.
- A new reply arrived.
- A mailing code was changed.
- A security-sensitive account event occurred.
- A report or moderation outcome was updated, when appropriate.

Features:

- Unread count.
- Mark one as read.
- Mark all as read.
- Notification preferences by type.
- Email and in-app delivery settings.
- Localized notification text.
- Pagination.
- Notification history.
- Duplicate prevention.

Use queues for outbound email.

If real-time delivery is enabled, use a compatible Laravel broadcasting solution. Otherwise, provide sensible polling or page-refresh behavior.

Never require real-time infrastructure for basic mailbox functionality.

---

# 13. DAILY LETTER LIMITS AND ABUSE CONTROL

Administrators must be able to set:

- Maximum letters per user per day.
- Maximum first-contact letters per day.
- Maximum letters to the same recipient within a configurable period.
- Maximum pending first-contact requests.
- Maximum letter body length.
- Minimum account age before sending.
- Optional new-account restrictions.
- Optional cooldown after repeated failed recipient-code lookups.
- Optional recipient approval requirements.

All limits must be enforced server-side.

A user must not bypass limits by changing the browser clock, editing JavaScript, opening another tab, or submitting direct HTTP requests.

Use database-backed counters, atomic updates, transactions, or another concurrency-safe mechanism.

Define the daily reset timezone and use a clear, documented rule.

Allow administrators to configure a global timezone and appropriate regional handling.

Return a helpful message when a limit is reached, including when the user can send again where this can be calculated accurately.

Keep an audit record of administrative changes to limits.

---

# 14. THE GLOBAL ADMIN PANEL

Build a professional admin application with its own navigation, dashboard, search, filters, tables, forms, and detailed record views.

The admin panel must be functional and database-backed.

Do not rely on hardcoded dashboard statistics.

### Dashboard

Show relevant metrics such as:

- Total registered users.
- Active users.
- New registrations.
- Completed questionnaires.
- Matches generated.
- Match notification success and failure counts.
- Letters sent.
- Letters read.
- Daily letter volume.
- Suspended users.
- Open reports.
- Email queue failures.
- System health indicators.

Respect privacy. Do not expose private letter contents on the default dashboard.

### User management

Authorized administrators can:

- Search by internal identifier, nickname, or email when necessary.
- View permitted account metadata.
- Change account status.
- Suspend or restore an account.
- Restrict matching or letter sending.
- Review relevant abuse reports.
- Trigger an appropriate account recovery process.
- Initiate deletion or anonymization workflows.
- View administrative audit history.

Sensitive account details must require appropriate permission and be audited.

Do not provide an unrestricted impersonation feature.

If support impersonation is implemented, require explicit elevated permission, a recorded reason, visible audit logs, and appropriate user privacy safeguards.

### Matching management

Administrators can:

- Configure the default match threshold.
- Configure compatibility weights.
- Set minimum shared-answer requirements.
- Manage questionnaire versions.
- View aggregate matching statistics.
- Pause matching.
- Retry failed matching jobs.
- Manage match-notification templates.
- Review matching errors.

Do not allow administrators to secretly alter an individual user's score without a recorded, justified, auditable action.

### Letter management

Administrators can:

- Configure daily sending limits.
- Configure letter length limits.
- Manage sender approval policies.
- Manage stationery themes.
- Manage stamp and envelope collections.
- Manage content moderation rules.
- Review reports and take permitted moderation actions.
- Configure read-receipt policy.
- Configure retention rules.

Access to actual letter contents should be restricted to explicitly authorized moderation or legal workflows, with access logging and a documented policy.

### Branding and customization

Provide a global settings interface for:

- Application name.
- Tagline.
- Logo and favicon.
- Brand colors.
- Typography choices.
- Default theme.
- Landing-page content.
- Footer content.
- Public page metadata.
- Social links.
- Contact information.
- Default language.
- Supported languages.
- Timezone.
- Date and time formats.
- Default letter limits.
- Match threshold.
- Email sender identity.
- Maintenance mode.
- Registration availability.
- Feature flags.

Validate uploaded assets, restrict file types and sizes, and store uploads safely.

### Content management

Allow authorized admins to create and edit:

- FAQs.
- About page.
- Privacy policy.
- Terms of service.
- Community guidelines.
- Safety instructions.
- Landing-page sections.
- Notification templates.
- Email templates.
- Translation strings where supported.

Version or audit important policy changes.

### Roles and permissions

Implement role-based access control.

Suggested roles:

- Super Administrator.
- Administrator.
- Moderator.
- Support Agent.
- Content Manager.
- Analytics Viewer.

Permissions must be granular.

Examples:

- users.view
- users.manage
- users.suspend
- matching.manage
- questions.manage
- letters.moderate
- reports.manage
- themes.manage
- translations.manage
- settings.manage
- roles.manage
- audit.view
- analytics.view

Only the Super Administrator or a specifically authorized role may change critical security settings.

Never trust frontend visibility alone to enforce permissions.

Use backend authorization policies and middleware.

### Administrative audit log

Record:

- Administrator identity.
- Action performed.
- Affected record reference.
- Timestamp.
- Reason, where required.
- Relevant before-and-after settings for administrative changes.
- Request correlation ID.

Avoid storing unnecessary letter content, passwords, OAuth tokens, or secrets in audit logs.

Audit records must not be editable through the normal admin interface.

---

# 15. MULTILINGUAL AND INTERNATIONAL SUPPORT

The platform should be ready for a worldwide audience.

Implement a proper translation system from the beginning.

Initial languages:

- English.
- Bengali.
- Hindi.
- Arabic.
- Spanish.
- French.
- Portuguese.

Additional languages must be addable through an administrator-controlled workflow.

### Translation requirements

- Language selector on public pages.
- Language preference in user settings.
- Localized authentication screens.
- Localized onboarding.
- Localized questionnaires.
- Localized emails.
- Localized notifications.
- Localized date and time formatting.
- Localized admin interfaces where supported.
- Translation completeness indicators.
- Fallback language when a translation is unavailable.
- Unicode support throughout the database and application.

Use UTF-8/utf8mb4 storage.

Support right-to-left layouts for Arabic, including proper alignment, navigation, and form behavior.

Store a user's preferred language separately from the language used by an individual letter.

The sender should be able to write a letter in their chosen language.

Do not automatically translate personal letters without explicit user consent.

If translation is later added, clearly label the translated version and preserve the original.

Do not store translations of private letters with an external service unless the relevant privacy and consent requirements have been addressed.

### Currency and locale

Do not require a currency system for core friendship functionality.

If paid features are introduced later, implement currency, tax, payment, and regional legal requirements as a separate properly scoped module.

---

# 16. PRIVACY AND DATA PROTECTION

Privacy is a defining feature of Dearly, not an optional enhancement.

### Publicly visible information

By default, another user may see only:

- Nickname.
- Age or permitted age range.
- Gender, only if the user chooses to share it.
- Preferred language.
- Shared interests.
- Compatibility percentage.
- Permitted public profile details.

Never publicly expose:

- Email address.
- Google account identifier.
- Real name from OAuth.
- Exact birth date.
- Password hashes.
- Internal database IDs.
- Private questionnaire responses.
- Security tokens.
- Login history.
- IP address.
- Private correspondence belonging to other users.

The interface must not reveal private profile details in page source, JSON responses, URLs, analytics events, or error messages.

### Account controls

Provide:

- Edit profile.
- Hide profile from matching.
- Disable new correspondence.
- Block a user.
- Report a user.
- Manage notification preferences.
- Change permitted privacy settings.
- Export personal data where required.
- Request account deletion.
- Delete or anonymize personal data according to applicable requirements and legitimate retention needs.

Clearly explain the effect of account deletion on existing letters, correspondence, and recipient records.

Do not promise absolute anonymity. Explain the limited account information the operator must retain for security, legal compliance, and platform operation.

### Security requirements

Implement:

- CSRF protection.
- Output escaping and XSS prevention.
- SQL injection prevention using parameterized database access.
- Secure password handling for any local administrative credentials.
- OAuth state validation.
- Secure session cookies.
- Session regeneration.
- Rate limiting.
- Server-side validation.
- Authorization policies.
- Secure file uploads.
- Secret management through environment variables.
- Safe error pages.
- Production debug mode disabled.
- HTTPS deployment guidance.
- Dependency vulnerability review.
- Security headers where appropriate.
- Login and abuse-event logging.
- Backup and restoration guidance.
- Protection against account enumeration.
- Safe password reset or account recovery for any supported local login.
- Data retention and deletion procedures.

Never place secrets in source code or frontend assets.

Use least-privilege database credentials.

Use a supported, secure approach for sensitive application settings and encryption keys.

Treat personal correspondence as private user data.

If letter encryption at rest is implemented, document key management, backup implications, search limitations, and the threat model honestly.

---

# 17. COMMUNITY SAFETY AND MODERATION

A pen-pal community requires practical abuse-prevention features.

Implement:

- Block user.
- Report user.
- Report letter.
- Spam detection and rate limiting.
- Repeated-contact restrictions.
- Harassment and threat reporting.
- Admin moderation queue.
- User suspension.
- Appeals or review workflow where practical.
- Community guidelines.
- Configurable prohibited-content rules.
- Safe account deletion.
- Anti-enumeration protections.

A blocked user must not be able to continue sending letters through alternate UI routes.

Enforce block rules in the actual backend delivery service.

Moderation should be proportionate, auditable, and privacy-conscious.

Do not automatically expose the reporter's identity to the reported user.

Do not use a simplistic keyword filter as the only moderation system.

---

# 18. DATABASE ARCHITECTURE

Design a normalized relational schema with appropriate foreign keys, indexes, constraints, and migrations.

Include, where appropriate:

- users
- user_profiles
- user_preferences
- oauth_accounts
- roles
- permissions
- role_user
- permission_role
- questionnaires
- questionnaire_versions
- questions
- question_options
- question_translations
- questionnaire_questions
- user_questionnaire_attempts
- questionnaire_answers
- matching_rules
- matching_runs
- match_results
- correspondence_connections
- mailing_codes
- letters
- letter_threads
- letter_events
- letter_recipients
- stationery_themes
- envelope_designs
- postage_stamps
- wax_seals
- user_stationery_preferences
- notifications
- notification_preferences
- notification_deliveries
- daily_sending_counters
- blocked_users
- user_reports
- moderation_actions
- translations
- content_pages
- content_revisions
- application_settings
- feature_flags
- admin_audit_logs
- email_delivery_logs
- privacy_requests

Use Laravel's built-in tables or supported equivalents for sessions, cache, queues, jobs, and failed jobs as required by the selected deployment configuration.

Avoid unnecessary duplication. Some listed concepts may be represented through existing Laravel structures or consolidated when the data model remains clear.

For every table, define:

- Primary key.
- Relevant foreign keys.
- Required fields.
- Nullability.
- Unique constraints.
- Indexes.
- Created and updated timestamps where appropriate.
- Deletion and retention behavior.

Use database transactions for critical multi-record operations.

Add unique constraints for mailing codes and duplicate match prevention.

Ensure queries are efficient and avoid N+1 loading.

Do not store all business data in a single JSON blob.

JSON columns may be used for appropriate flexible metadata, but core relational entities must remain queryable.

Provide migrations, factories, seeders, and a documented initial setup process.

---

# 19. BACKEND ARCHITECTURE

Organize the Laravel application into clear responsibilities.

Use:

- Controllers for HTTP orchestration.
- Form Requests for validation.
- Policies for authorization.
- Services or action classes for business logic.
- Jobs for matching and outbound notifications.
- Events and listeners for relevant domain events.
- Mailables and notifications for delivery.
- Models and relationships for persistence.
- Repositories only where they provide a real architectural benefit.
- Middleware for authentication, role checks, throttling, and account restrictions.
- API resources where JSON endpoints are required.
- Scheduled commands for maintenance tasks.

Important services should include equivalents of:

- GoogleAuthenticationService
- ProfileService
- QuestionnaireService
- CompatibilityScoringService
- MatchDiscoveryService
- MailingCodeService
- LetterComposerService
- LetterDeliveryService
- ReadReceiptService
- DailySendingLimitService
- NotificationDeliveryService
- ModerationService
- PrivacyRequestService
- TranslationManagementService
- AdminAuditService

Names may be adapted to Laravel conventions.

Avoid giant controllers and duplicated business logic.

Keep authorization decisions close to the relevant operation.

---

# 20. EMAIL AND BACKGROUND JOBS

Use queued jobs for:

- Match notifications.
- New-letter notifications.
- Read receipts.
- Reply notifications.
- Moderation notifications.
- Scheduled cleanup.
- Large matching jobs.
- Bulk administrative operations.

Implement retries, backoff, failed-job visibility, and idempotency.

Do not send duplicate emails when a job is retried.

Provide email templates for each supported notification.

Create a local development mode using a safe mail-capture tool or log-based email transport.

Do not send test emails to real users unintentionally.

Document SMTP configuration, sender verification, SPF, DKIM, DMARC, and queue-worker setup.

An email appearing in the application's delivery queue must not be represented as successfully delivered to the recipient's inbox.

Distinguish queued, accepted by provider, delivered where provider feedback is available, and failed.

---

# 21. PERFORMANCE AND SCALABILITY

Design the platform to support future growth.

Implement:

- Pagination for all potentially large lists.
- Appropriate database indexes.
- Efficient matching queries.
- Queue-based background work.
- Caching for public content and safe aggregate statistics.
- Cache invalidation for editable settings.
- Optimized images.
- Lazy loading where appropriate.
- Compiled and minified frontend assets.
- Efficient letter-list rendering.
- Database query monitoring in development.
- Configurable log retention.

Do not cache private letter content across users.

Do not expose sensitive cached information through shared caches.

Do not run a full-table matching scan on every page load.

Support resumable or batched matching jobs where needed.

Provide performance recommendations and realistic capacity assumptions in the documentation.

---

# 22. ACCESSIBILITY AND USER EXPERIENCE

Aim for WCAG 2.2 AA practices where practical.

Include:

- Keyboard-accessible navigation.
- Clear focus indicators.
- Proper form labels.
- Screen-reader announcements for validation and status changes.
- Accessible dialog behavior.
- Sufficient contrast.
- Alternative text for meaningful imagery.
- Reduced-motion support.
- Mobile-friendly touch targets.
- Readable font sizes.
- Clear error messages.
- Confirmation before destructive actions.
- Accessible alternatives to animated envelope interactions.

Avoid relying only on color to indicate letter status.

Ensure the website remains usable on slower connections.

---

# 23. OPTIONAL FEATURES THAT MAKE DEARLY SPECIAL

Build the core system first. Add the following as independent, feature-flagged enhancements when they can be implemented reliably.

### A. The Letter Journey

Show a small postal timeline:

- Letter composed.
- Envelope sealed.
- Letter sent.
- Letter delivered to the recipient's mailbox.
- Letter opened.

These are digital events, not claims about physical postal delivery.

### B. Correspondence Memories

Allow users to bookmark selected letters or add private notes to their own correspondence.

Private notes must never be visible to the other participant.

### C. Anniversary Letters

Offer optional reminders of the date when a correspondence began.

Allow users to disable these reminders.

### D. Scheduled Letters

Allow a user to schedule their own letter for future delivery, subject to current permissions and limits.

Revalidate recipient eligibility, blocking, sending limits, and account status at send time.

Provide cancellation before dispatch.

### E. Letter Prompts

Offer optional writing inspiration:

- "Tell me about a memory that still makes you smile."
- "What is something you wish more people understood?"
- "Describe your perfect peaceful afternoon."
- "What song brings back a special memory?"

Make prompts configurable and available in supported languages.

### F. Postcard Mode

Allow shorter letters with postcard-style designs.

### G. Seasonal Collections

Create limited-time visual collections for appropriate seasons and holidays.

Allow administrators to schedule availability.

### H. Time Capsule

Allow a user to save a letter privately and schedule a reminder to revisit it later.

Do not allow a user to secretly access or modify another user's time capsule.

### I. Letter Statistics

Show the user personal, privacy-respecting statistics, such as letters written, drafts saved, and correspondence maintained.

Avoid manipulative streaks or notifications that create pressure to respond.

### J. Optional sound effects

Offer gentle paper, envelope, or stamp sounds only after an explicit user gesture.

Default to silent mode.

### K. Installable PWA

Where practical, implement an installable Progressive Web App with a manifest, suitable icons, and a service worker.

Never cache private letters or authenticated API responses in a shared public cache.

Ensure logout and account switching clear any user-specific cached information.

---

# 24. ADMINISTRATOR CONFIGURATION REQUIREMENTS

The administrator must be able to manage nearly all ordinary business settings without editing source code.

This includes:

- Branding.
- Theme settings.
- Public content.
- Language options.
- Question bank.
- Questionnaire lengths.
- Matching threshold.
- Matching weights.
- Match eligibility rules.
- Daily sending limits.
- Letter limits.
- Mailing-code rules.
- Notification preferences and templates.
- Email templates.
- Stationery and stamps.
- User roles.
- Moderation rules.
- Feature flags.
- Registration status.
- Maintenance mode.
- Privacy and retention settings.
- Default timezone.
- Supported locales.

Security-sensitive settings must require appropriate permissions and, where warranted, reauthentication.

Do not provide an editable admin field for arbitrary PHP execution, SQL execution, shell commands, or unrestricted JavaScript.

Keep secrets, OAuth credentials, database credentials, and encryption keys outside the ordinary settings editor.

Allow admins to edit normal text and design settings without being able to compromise the server.

---

# 25. ROUTES AND NAVIGATION

Create coherent, named Laravel routes.

Suggested public routes:

- /
- /about
- /how-it-works
- /privacy
- /terms
- /community-guidelines
- /faq
- /login
- /auth/google/redirect
- /auth/google/callback

Suggested authenticated routes:

- /post-office
- /profile
- /profile/privacy
- /find-a-pen-friend
- /questionnaire/{length}
- /matches
- /mailing-code
- /write-letter
- /drafts
- /letters/sent
- /letters/received
- /letters/{letter}
- /correspondence
- /notifications
- /settings
- /settings/language
- /settings/notifications
- /settings/privacy
- /settings/account

Suggested admin routes:

- /admin
- /admin/users
- /admin/questionnaires
- /admin/questions
- /admin/matches
- /admin/letters
- /admin/reports
- /admin/stationery
- /admin/translations
- /admin/content
- /admin/notifications
- /admin/analytics
- /admin/roles
- /admin/settings
- /admin/audit-logs

Adapt route names and structure to the final architecture.

Do not expose unauthorized records simply because a user can guess a URL.

---

# 26. TESTING REQUIREMENTS

Create automated tests that exercise real business logic.

### Authentication tests

- Google OAuth callback validation.
- Successful account creation.
- Existing-account linking rules.
- Unauthorized access prevention.
- Suspended account restrictions.

### Questionnaire tests

- Exactly 20, 30, 40, and 50 active questions for corresponding versions.
- Required-answer validation.
- Answer persistence.
- Version integrity.
- Questionnaire completion.

### Matching tests

- Correct weighted scoring.
- Threshold configuration.
- 90% eligibility behavior.
- Missing-answer handling.
- Duplicate match prevention.
- Self-matching prevention.
- Paused profile exclusion.
- Suspended account exclusion.
- Match notification idempotency.

### Mailing-code tests

- Code uniqueness.
- Recipient resolution.
- Revoked code rejection.
- Unauthorized access prevention.
- No account enumeration.
- Rotation and replacement behavior.

### Letter tests

- Draft creation and updates.
- Draft privacy.
- Successful sending.
- Invalid recipient handling.
- Blocked-user restrictions.
- Daily limit enforcement.
- Duplicate submission prevention.
- Correct recipient mailbox visibility.
- Read receipt authorization.
- Sender and recipient data isolation.

### Admin tests

- Role and permission enforcement.
- User-management restrictions.
- Configuration persistence.
- Audit logging.
- Content editing.
- Translation management.
- Unauthorized settings changes blocked.

### Security tests

- CSRF protection.
- XSS handling.
- SQL injection resistance through safe data access.
- Rate limiting.
- Private email non-disclosure.
- Cross-user IDOR prevention.
- File upload validation.
- Sensitive data redaction.

### UI and integration tests

Test the complete flow:

1. Sign in using a configured OAuth test strategy.
2. Complete onboarding.
3. Complete a questionnaire.
4. Generate a qualifying match.
5. Verify the match notification job.
6. Retrieve a valid recipient-specific mailing code.
7. Compose and save a draft.
8. Send a letter.
9. Confirm delivery in the recipient mailbox.
10. Open the letter as the recipient.
11. Confirm the sender receives the correct read receipt.
12. Verify that unauthorized users cannot access the letter.

Use isolated test accounts, a test database, and fake or captured email delivery where appropriate.

Do not require live Google credentials to execute ordinary automated test suites.

---

# 27. SEED DATA AND FIRST ADMINISTRATOR

Create a safe database seeding strategy.

Provide:

- Initial application settings.
- Default translations.
- Initial questionnaire bank.
- Default questionnaire lengths.
- Starter stationery themes.
- Starter postage stamps.
- Initial public content.
- Role and permission definitions.

Do not create fake production users or fabricated activity statistics.

If demonstration data is provided, label it clearly as demo data and ensure it is not mistaken for real users.

Create the first Super Administrator through a documented secure setup command or controlled setup procedure.

Never hardcode a production admin password.

Do not leave an unauthenticated public route that grants administrator access.

---

# 28. ENVIRONMENT CONFIGURATION

Provide a complete .env.example containing variable names, not real secrets.

Include the configuration needed for:

- Application URL and environment.
- Database.
- Google OAuth client credentials.
- Session configuration.
- Mail transport.
- Queue connection.
- Cache.
- Logging.
- Supported locale defaults.
- Timezone.
- Storage.
- Optional monitoring.
- Optional broadcasting.

Document the exact Google Cloud OAuth configuration steps, authorized callback URL, local development URL, production HTTPS URL, and required consent-screen configuration.

Do not commit the actual .env file.

Ensure production error messages never expose secrets or stack traces.

---

# 29. DEPLOYMENT AND OPERATIONS

Prepare the project for deployment on a supported Linux server or compatible hosting platform.

Provide:

- Installation instructions.
- PHP and extension requirements.
- Composer installation.
- Frontend asset build steps.
- Database creation and migration instructions.
- Initial administrator creation.
- OAuth setup.
- SMTP setup.
- Queue worker configuration.
- Scheduler configuration.
- Storage link setup.
- File permissions.
- HTTPS configuration.
- Production cache optimization.
- Logging and monitoring.
- Backup strategy.
- Database restoration steps.
- Update and rollback guidance.

Ensure the web server document root points to Laravel's public directory.

Never expose .env, source files, private storage, database dumps, or logs publicly.

Document that queue workers and scheduled tasks must actually be configured for asynchronous jobs and scheduled functionality to run in production.

Include a troubleshooting guide for common installation and email-delivery errors.

---

# 30. IMPLEMENTATION PHASES

Execute the project in phases while keeping a persistent checklist.

### Phase 1 — Discovery and architecture

- Inspect the repository.
- Confirm runtime compatibility.
- Establish the feature matrix.
- Define database relationships.
- Define authorization boundaries.
- Create the implementation plan.

### Phase 2 — Foundation

- Set up Laravel.
- Configure the database.
- Implement migrations and seeders.
- Establish reusable UI components.
- Implement authentication.
- Implement roles and permissions.

### Phase 3 — Public website and onboarding

- Build the landing page.
- Implement Google OAuth.
- Create profile setup.
- Add language selection.
- Add profile privacy settings.

### Phase 4 — Questionnaire and matching

- Build the configurable MCQ system.
- Implement versioned questionnaires.
- Create the weighted compatibility engine.
- Implement matching eligibility.
- Build the match discovery interface.
- Add matching email and in-app notifications.

### Phase 5 — Mailing codes and letters

- Create secure recipient-specific codes.
- Build the letter composer.
- Implement drafts.
- Build envelopes, stamps, and seals.
- Implement sending and recipient mailbox delivery.
- Implement read receipts and replies.

### Phase 6 — Notifications and limits

- Add notification preferences.
- Add queued email delivery.
- Implement daily limits.
- Add spam prevention and rate limiting.

### Phase 7 — Global admin panel

- Implement user management.
- Implement matching configuration.
- Implement questionnaire management.
- Implement stationery management.
- Implement localization management.
- Implement content management.
- Implement role management.
- Implement audit logs.
- Implement global settings.

### Phase 8 — Safety, privacy, and polish

- Implement reporting and blocking.
- Complete privacy controls.
- Add accessibility improvements.
- Optimize performance.
- Add PWA support if feasible.
- Refine responsive layouts and animations.

### Phase 9 — Verification

- Run migrations against a test database.
- Run automated tests.
- Run frontend builds.
- Check routes and permissions.
- Verify email capture.
- Test the complete letter workflow.
- Fix identified defects.
- Document any remaining externally blocked integrations.

### Phase 10 — Delivery

Provide a working application and the complete project source.

Include:

- README.md.
- IMPLEMENTATION_PLAN.md.
- FEATURE_CHECKLIST.md.
- DATABASE_DESIGN.md.
- SECURITY.md.
- DEPLOYMENT.md.
- TROUBLESHOOTING.md.
- .env.example.
- Migrations.
- Seeders.
- Automated tests.

Update the feature checklist with actual implementation and verification status.

---

# 31. DEFINITION OF DONE

Do not declare the project complete merely because the pages render.

The project is ready for handoff only when:

1. The application boots successfully in the documented environment.
2. Database migrations run successfully.
3. The first administrator can be created securely.
4. Google OAuth works when correctly configured.
5. The user can complete onboarding.
6. All four questionnaire lengths work with actual database-backed questions.
7. Matching scores are calculated using the configured algorithm.
8. The configured match threshold is enforced.
9. Duplicate match notifications are prevented.
10. Recipient-specific mailing codes work correctly.
11. Private profile information remains private.
12. Drafts can be created, edited, saved, and deleted.
13. Letters can be sealed and sent through a real backend workflow.
14. Recipients see only letters addressed to them.
15. Read receipts are generated by the correct recipient-side event.
16. Daily sending limits are enforced server-side.
17. Email jobs and in-app notifications work in the documented environment.
18. The admin panel can manage the promised configurable settings.
19. Language switching works for implemented translations.
20. Authorization and security tests pass.
21. The main end-to-end workflow passes.
22. Frontend assets compile successfully.
23. Deployment and setup instructions are accurate.
24. All remaining limitations and external prerequisites are documented honestly.

Do not claim that the system is bug-free or perfectly secure. Report actual test results and any known limitations.

---

# 32. FINAL EXECUTION INSTRUCTIONS

Begin by inspecting the existing project and creating the implementation plan.

Then implement the application incrementally, preserving a coherent architecture and keeping the checklist updated.

Use your available tools to edit files, execute commands, run migrations, run tests, inspect errors, and fix defects.

Do not stop after producing architecture documentation, wireframes, screenshots, or a homepage.

Do not replace functional code with a visual-only prototype.

Do not skip difficult backend features merely to make the design look finished.

If a required external credential is unavailable, finish the integration code, provide safe local testing, and document exactly what is needed to activate the real service.

If an optional enhancement would compromise core stability, prioritize the core feature and document the enhancement separately.

Prefer real, tested functionality over an exaggerated feature count.

The final experience should feel like opening a forgotten letter from another time, while the underlying engineering meets modern expectations for privacy, security, accessibility, maintainability, and reliability.

**Build Dearly as a complete, usable digital post office—not merely as a design concept.**