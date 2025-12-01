# Event Registration System — Project Structure & Interaction

## Folder Structure

```
EventRegistration/
├── backend/
│   ├── config/
│   │   └── db.php                # Database connection setup (PDO)
│   ├── public/                   # API endpoints (PHP)
│   │   ├── addevent.php          # Add new event (POST)
│   │   ├── addparticipant.php    # Add new participant (POST)
│   │   ├── check_auth.php        # Check admin login status (GET)
│   │   ├── deleteevent.php       # Delete event (POST)
│   │   ├── deleteparticipant.php # Delete participant (POST)
│   │   ├── events.php            # List events (GET)
│   │   ├── health.php            # Health check (GET)
│   │   ├── login.php             # Admin login (POST)
│   │   ├── logout.php            # Admin logout (GET)
│   │   ├── participants.php      # List participants (GET)
│   │   ├── register.php          # Register participant to event (POST)
│   │   ├── registrations.php     # List registrations for event (GET)
│   │   ├── updatevent.php        # Update event (POST)
│   ├── src/
│   │   ├── auth.php              # Authentication/session logic
│   │   ├── events.php            # Event CRUD functions
│   │   ├── participants.php      # Participant CRUD functions
│   │   ├── registrations.php     # Registration logic
├── frontend/
│   ├── admin.html                # Admin login panel
│   ├── events.html               # Event management UI
│   ├── global.css                # Shared styles
│   ├── index.html                # Home page
│   ├── participants.html         # Participant management UI
│   ├── registrations.html        # Registration management UI
├── README.md
├── projectPhp.md
```

## How Frontend & Backend Interact

### 1. Frontend (HTML/JS)
- Each page uses JavaScript (`fetch`) to call backend API endpoints.
- Data is sent/received as JSON.
- Example: Adding an event from `events.html`:
  ```js
  fetch('http://localhost/EventRegistration/backend/public/addevent.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title, description, date })
  })
  ```

### 2. Backend (PHP API)
- Each endpoint in `backend/public/` is a REST-like API.
- Reads JSON input using:
  ```php
  $data = json_decode(file_get_contents('php://input'), true);
  ```
- Calls functions from `src/` for business logic (CRUD, validation).
- Responds with JSON and sets headers:
  ```php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  ```

### 3. Database
- All data is stored in MySQL, accessed via PDO in `config/db.php`.
- Prepared statements are used for security.

### 4. Authentication
- Admin login/logout handled via AJAX to `login.php`/`logout.php`.
- Session managed in PHP (`$_SESSION`).
- Frontend checks login status via `check_auth.php`.

## Example Data Flow

1. **User submits form in frontend** → JS sends AJAX request to backend endpoint.
2. **Backend endpoint** reads JSON, validates, interacts with DB, returns JSON response.
3. **Frontend JS** receives response, updates UI accordingly.

## Why This Method?
- Decoupled: Frontend and backend communicate only via JSON APIs.
- Modern: No page reloads, SPA-like experience.
- Secure: Prepared statements, CORS headers, session management.

---
*This file describes the folder structure and explains in detail how the frontend and backend interact in your project.*
