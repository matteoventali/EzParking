# 🧩 Account Microservice API Documentation

Comprehensive documentation for all REST endpoints of the **Account Microservice** in EzParking.

---

## Base URL
```
http://10.5.0.11:5000/
```

---

## Root
### `GET /`
**Description:** Service health check.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{ "message": "Service is active" }
```

---

## 🔐 AUTHENTICATION

### `POST /auth/signup`
**Description:** Register a new user.  
**Authentication:** None  
**Request JSON:**
```json
{
  "name": "",
  "surname": "",
  "email": "",
  "password": "",
  "phone": ""
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "User registered successfully",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": "",
    "email": "",
    "phone": "",
    "role": ""
  }
}

// 400 Bad Request
{ "error": "Missing required fields" }

// 409 Conflict
{ "desc": "Email already registered", "code": "1" }
{ "desc": "Phone already registered", "code": "2" }

// 500 Internal Server Error
{ "desc": "Database error", "code": "99", "details": "" }
```

---

### `POST /auth/login`
**Description:** Authenticate user credentials and generate a session token.  
**Authentication:** None  
**Request JSON:**
```json
{
  "email": "",
  "password": ""
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Login successful",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": "",
    "email": "",
    "phone": "",
    "role": "",
    "session_token": "",
    "account_status": true
  }
}

// 400 Bad Request
{ "desc": "Missing email or password", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid email or password", "code": "2" }

// 403 Forbidden
{ "desc": "Account disabled", "code": "3" }

// 500 Internal Server Error
{ "desc": "Database error", "code": "99", "details": "" }
```

---

### `GET /auth/logout`
**Description:** Logout the authenticated user and invalidate their session token.  
**Authentication:** User (requires `Authorization` header)  
**Responses:**
```json
// 200 OK
{ "desc": "Logout successful", "code": "0" }

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }
```

---

### `GET /auth/status`
**Description:** Check if the session token is still valid.  
**Authentication:** User  
**Responses:**
```json
// 200 OK
{ "desc": "Online", "code": "0" }

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 403 Forbidden
{ "desc": "Account disabled", "code": "3" }
```

---

## 👤 PERSONAL DATA

### `GET /pdata`
**Description:** Retrieve personal user data and average score from reviews.  
**Authentication:** User  
**Responses:**
```json
// 200 OK
{
  "desc": "User data retrieved successfully",
  "code": "0",
  "user": {
    "name": "",
    "surname": "",
    "email": "",
    "phone": "",
    "score": 0
  }
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }
```

---

### `PUT /pdata`
**Description:** Update user information (name, surname, phone, password).  
**Authentication:** User  
**Request JSON:**
```json
{
  "name": "",
  "surname": "",
  "phone": "",
  "password": ""
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "User data updated successfully",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": "",
    "email": "",
    "phone": "",
    "password": true
  }
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }
{ "desc": "Missing request body", "code": "3" }
{ "desc": "No valid fields to update", "code": "5" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 409 Conflict
{ "desc": "Phone already registered", "code": "4" }
```

---

## ⭐ REVIEWS

### `GET /reviews`
**Description:** Retrieve all reviews written and received by the user.  
**Authentication:** User  
**Responses:**
```json
// 200 OK
{
  "desc": "Reviews retrieved successfully",
  "code": "0",
  "written_reviews": [],
  "received_reviews": []
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `POST /reviews`
**Description:** Submit a review for another user.  
**Authentication:** User  
**Request JSON:**
```json
{
  "target_id": 0,
  "reservation_id": 0,
  "star": 0,
  "review_description": ""
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "Review added successfully",
  "code": "0",
  "review": {
    "id": 0,
    "writer_id": 0,
    "target_id": 0,
    "reservation_id": 0,
    "star": 0,
    "review_description": "",
    "review_date": ""
  }
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }
{ "desc": "Missing required fields", "code": "3" }
{ "desc": "Star must be an integer between 1 and 5", "code": "4" }
{ "desc": "User cannot review themselves", "code": "5" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 404 Not Found
{ "desc": "Target user not found", "code": "6" }

// 409 Conflict
{ "desc": "Review already exists for this reservation", "code": "7" }

// 500 Internal Server Error
{ "desc": "Database error : <error>", "code": "99" }
```

---

## ⚙️ ADMIN ENDPOINTS

### `GET /users`
**Description:** Retrieve list of all users.  
**Authentication:** Admin  
**Responses:**
```json
// 200 OK
{
  "desc": "Users list retrieved successfully",
  "code": "0",
  "users": []
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 403 Forbidden
{ "desc": "Access denied: admin only", "code": "3" }
```

---

### `GET /users/{user_id}`
**Description:** Retrieve a detailed dashboard for a specific user.  
**Authentication:** Admin  
**Responses:**
```json
// 200 OK
{
  "desc": "User dashboard retrieved successfully",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": "",
    "email": "",
    "phone": "",
    "role": "",
    "score": 0,
    "status": true
  },
  "received_reviews": [],
  "written_reviews": []
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 403 Forbidden
{ "desc": "Access denied: admin only", "code": "3" }

// 404 Not Found
{ "desc": "User not found", "code": "4" }
```

---

### `GET /users/{user_id}/enable`
**Description:** Enable a user account.  
**Authentication:** Admin  
**Responses:**
```json
// 200 OK
{
  "desc": "User account enabled successfully",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": "",
    "email": "",
    "account_status": true
  }
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 403 Forbidden
{ "desc": "Access denied: admin only", "code": "3" }
{ "desc": "Admin account cannot be re-enabled", "code": "5" }

// 404 Not Found
{ "desc": "User not found", "code": "4" }

// 200 OK (already enabled)
{ "desc": "User account already enabled", "code": "6" }

// 500 Internal Server Error
{ "desc": "<error>", "code": "99" }
```

---

### `GET /users/{user_id}/disable`
**Description:** Disable a user account.  
**Authentication:** Admin  
**Responses:**
```json
// 200 OK
{
  "desc": "User account disabled successfully",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": "",
    "email": "",
    "account_status": false
  }
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 403 Forbidden
{ "desc": "Access denied: admin only", "code": "3" }
{ "desc": "Admin account cannot be re-disabled", "code": "5" }

// 404 Not Found
{ "desc": "User not found", "code": "4" }

// 200 OK (already disabled)
{ "desc": "User account already disabled", "code": "6" }

// 500 Internal Server Error
{ "desc": "<error>", "code": "99" }
```

### `GET /users/active_count`
**Description:** Get the number of users currenty logged into the system
**Authentication:** Admin  
**Responses:**

```json
// 200 OK
{
  "desc": "Active user count retrieved successfully",
  "code": '0',
  "active_user_count": active_user_count
}

// 400 Bad Request
{ "desc": "Missing or invalid Authorization header", "code": "1" }

// 401 Unauthorized
{ "desc": "Invalid session token", "code": "2" }

// 403 Forbidden
{ "desc": "Access denied: admin only", "code": "3" }

---

**Author:** EzParking Development Team  
**Module:** Account Microservice  
**Language:** Python (Flask + SQLAlchemy)
