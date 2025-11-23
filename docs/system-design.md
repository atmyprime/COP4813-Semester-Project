# System Design Document

## 1. Use Cases

### Use Case List
- UC-1: User registers an account
- UC-2: User logs in and submits <request/post/etc.>
- UC-3: Admin reviews and approves/rejects submissions
- UC-4: Admin views analytics dashboard

### Example Use Case Description (UC-1)
- **Actors:** Visitor
- **Precondition:** User is not logged in
- **Main Flow:**
  1. Visitor opens registration page.
  2. Fills in required fields.
  3. Clicks "Register".
  4. System validates and creates account.
  5. System redirects to login page.
- **Postcondition:** New user account stored in DB.

## 2. ER Diagram / Data Description
Describe tables or collections:
- `users(user_id, name, email, password_hash, role, status, created_at)`
- `posts/post_requests/...`
- Any other tables.

(You can draw an ER diagram in draw.io or similar, export as PNG, and link it here.)

## 3. Basic Wireframes / UI Mockups
Describe or link simple sketches/screenshots:
- Landing / Home page
- Registration/Login page
- User dashboard / main action page
- Admin panel
- Analytics dashboard

## 4. Architecture Diagram (3-Tier)
Explain 3-tier:
- **Presentation Layer (Frontend):** Browser UI, forms, pages.
- **Application Layer (Backend):** API routes, business logic.
- **Data Layer (Database):** Tables, queries, persistence.

Describe how data flows:  
User → Browser → Backend → Database → Backend → Browser.
