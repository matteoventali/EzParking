# Notification Microservice API Documentation

Comprehensive documentation for all REST endpoints of the **Notification Microservice** in EzParking.

---

## Base URL
```
http://10.5.0.13:5001/
```

---

## Root
### `GET /`
**Description:** Service health check.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{ "message": "Notification Service is active" }
```

---

## USERS

### `POST /notifications/users`
**Description:** Insert a new user when registration occurs.  
**Authentication:** None  
**Request JSON:**
```json
{
  "id": 0,
  "name": "",
  "surname": "",
  "email": ""
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "New user successfully inserted",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": "",
    "email": ""
  }
}

// 404 Not Found
{ "desc": "Missing required fields", "code": "1" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `PUT /user/{user_id}`
**Description:** Update user's last login timestamp and last known position.  
**Authentication:** None  
**Request JSON:**
```json
{
  "lat": 0.0,
  "lon": 0.0
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "User login info updated",
  "code": 0,
  "info": {
    "lastposition": [0.0, 0.0],
    "last_login": "YYYY-MM-DDTHH:MM:SS"
  }
}

// 400 Bad Request
{ "desc": "missing position coordinates", "code": 1 }

// 404 Not Found
{ "desc": "User not found", "code": 1 }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": 99 }
```

---

## NOTIFICATIONS

### `POST /notifications/nearby_alert`
**Description:** Send notifications to users near a newly available parking spot (within 1km radius and logged in within the last 30 minutes).  
**Authentication:** None  
**Request JSON:**
```json
{
  "lat": 0.0,
  "lon": 0.0,
  "owner_id": 0,
  "address": "",
  "spot_name": "",
  "slot_date": "YYYY-MM-DD",
  "start_time": "HH:MM",
  "end_time": "HH:MM"
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Notifications scheduled",
  "code": "0",
  "users_found": 0
}

// 400 Bad Request
{ "desc": "Missing parameters", "code": "1" }

// 500 Internal Server Error
{ "desc": "Error: <error>", "code": "99" }
```

---

### `POST /notifications/reservation_accepted`
**Description:** Send notification when a reservation is accepted by the parking spot owner.  
**Authentication:** None  
**Request JSON:**
```json
{
  "spot_name": "",
  "date": "YYYY-MM-DD",
  "resident_id": 0,
  "driver_id": 0,
  "start_time": "HH:MM",
  "end_time": "HH:MM",
  "plate": "",
  "cost": 0.0,
  "address": ""
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Reservation acceptance notification scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing parameters", "code": "1" }

// 404 Not Found
{ "desc": "Invalid resident_id", "code": "2" }
{ "desc": "Invalid driver_id", "code": "3" }
```

---

### `POST /notifications/reservation_rejected`
**Description:** Send notification when a reservation is rejected by the parking spot owner.  
**Authentication:** None  
**Request JSON:**
```json
{
  "user_id": 0,
  "resident_id": 0,
  "spot_name": "",
  "date": "YYYY-MM-DD"
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Reservation rejected notification scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing parameters", "code": "1" }

// 404 Not Found
{ "desc": "Invalid user", "code": "2" }
{ "desc": "Invalid resident", "code": "3" }
```

---

### `POST /notifications/reservation_cancelled`
**Description:** Send notification when a reservation is cancelled.  
**Authentication:** None  
**Request JSON:**
```json
{
  "user_id": 0,
  "date": "YYYY-MM-DD",
  "spot_name": ""
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Reservation cancelled notification scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing parameters", "code": "1" }

// 404 Not Found
{ "desc": "Invalid user", "code": "2" }
```

---

### `POST /notifications/reservation_request`
**Description:** Send notification when a new reservation request is received.  
**Authentication:** None  
**Request JSON:**
```json
{
  "user_id": 0
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Reservation request notification scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing user_id", "code": "1" }

// 404 Not Found
{ "desc": "Invalid user", "code": "2" }
```

---

### `POST /notifications/registration_successfull`
**Description:** Send welcome email when a new user successfully registers.  
**Authentication:** None  
**Request JSON:**
```json
{
  "user_id": 0
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Registration email scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing user_id", "code": "1" }

// 404 Not Found
{ "desc": "Invalid user", "code": "2" }
```

---

### `POST /notifications/received_review`
**Description:** Send notification when a user receives a new review.  
**Authentication:** None  
**Request JSON:**
```json
{
  "reviewer_id": 0,
  "user_id": 0,
  "spot_name": ""
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Notification scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing parameters", "code": "1" }

// 404 Not Found
{ "desc": "Invalid target user", "code": "2" }
{ "desc": "Invalid reviewer user", "code": "3" }
```

---

### `POST /notifications/account_disabled`
**Description:** Send notification when a user's account is disabled by an admin.  
**Authentication:** None  
**Request JSON:**
```json
{
  "user_id": 0
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Notification scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing user id", "code": "1" }

// 404 Not Found
{ "desc": "Invalid user", "code": "2" }
```

---

### `POST /notifications/account_enabled`
**Description:** Send notification when a user's account is re-enabled by an admin.  
**Authentication:** None  
**Request JSON:**
```json
{
  "user_id": 0
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Notification scheduled",
  "code": "0"
}

// 400 Bad Request
{ "desc": "Missing user id", "code": "1" }

// 404 Not Found
{ "desc": "Invalid user", "code": "2" }
```

---

**Author:** EzParking Development Team  
**Module:** Notification Microservice  
**Language:** Python (Flask + SQLAlchemy + SMTP)
