# Dearly — Security Policy, Data Protection & Safeguarding Specification

**Brand Tagline:** *"Letters Find Their Way."*  
**Security Standard:** Privacy by design, defense in depth, zero credential leakage.

---

## 1. Privacy Architecture & Public Pseudonymity

Dearly is designed from the ground up to protect individual identity:
1. **Public Pseudonyms (Nicknames)**: All members interact exclusively using unique public nicknames (e.g., `VintageDreamer`, `MidnightWriter`).
2. **Hidden Account Credentials**: Under no circumstances does the frontend or any API endpoint reveal:
   - User email addresses.
   - Google OAuth subject identifiers (`sub`).
   - Real names imported from Google profiles.
   - Internal relational database IDs (`user_id`).
   - Exact birth dates (only permitted age range / confirmed adult status).
   - Password hashes or OAuth tokens.
3. **Recipient-Specific Mailing Codes**: Letters are addressed using unique codes (format `DEARLY-XXXX-YYYY`). The code resolves to the recipient's internal mailbox on the server side, preventing account enumeration.

---

## 2. Safeguarding & Age Verification

- **Adult-Only Policy**: By default, Dearly operates as an adult service (minimum age 18).
- **No Adult-Minor Matching**: The compatibility matching engine enforces age verification before an attempt is eligible for matching.
- **Abuse Reporting & Suspension**: Any reported user is flagged in the administrator moderation queue. Admins have immediate one-click suspension powers. Suspended users are instantly disconnected, and their mailing codes become inactive.

---

## 3. Letter Privacy & Read Receipts

- **End-to-End Mailbox Isolation**: Letters are scoped strictly to `sender_id` and `recipient_id`. Blade views and controller queries enforce `where(function($q) { $q->where('sender_id', auth()->id())->orWhere('recipient_id', auth()->id()); })`.
- **Authentic Read Receipts**: Read receipts are triggered **only** when an authorized recipient successfully renders the letter content. No invasive third-party tracking pixels or email opening heuristics are used.
- **Sender/Recipient Deletion Independence**: If a sender deletes their copy of a sent letter, the recipient's copy remains intact in their mailbox, and vice versa.

---

## 4. Threat Model & Countermeasures

| Threat Vector | Mitigation Strategy |
|---|---|
| **SQL Injection** | Exclusively parameterized Eloquent ORM & Query Builder queries. Zero raw concatenated strings. |
| **Cross-Site Scripting (XSS)** | Blade auto-escaping `{{ $variable }}` on all user inputs (letter body, titles, nicknames, bios). Strict sanitization. |
| **Cross-Site Request Forgery (CSRF)** | Laravel CSRF token middleware enforced on all POST/PUT/DELETE requests. |
| **Insecure Direct Object Reference (IDOR)** | Laravel Authorization Policies (`LetterPolicy`, `ProfilePolicy`) verifying ownership before reading, editing, or deleting records. |
| **Spam / Flood Abuse** | Concurrency-safe daily sending limits (`daily_sending_counters`), rate-limiting middleware (`throttle:60,1`). |
| **Harassment** | Instant `blocked_users` relationship, completely suppressing letter delivery and matching visibility. |
| **Credential & Secret Exposure** | Strict `.env` isolation. No secrets, keys, or credentials committed to source control. `.env.example` provided. |

---

## 5. Administrative Audit Logging

All administrative actions (user suspensions, role modifications, question edits, stationery changes, application setting modifications) are recorded immutably in `admin_audit_logs` with:
- `admin_id`: ID of the executing administrator.
- `action`: Specific operation performed.
- `target_type` & `target_id`: Affected entity.
- `ip_address`: Originating IP address.
- `changes`: JSON snapshot of before/after values.
- `created_at`: UTC timestamp.
