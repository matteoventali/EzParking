# Payment Microservice API Documentation

Comprehensive documentation for all REST endpoints of the **Payment Microservice** in EzParking.

---

## Base URL
```
http://10.5.0.14:5003/
```

---

## Root
### `GET /`
**Description:** Service health check.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{ "message": "Payment Service is active" }
```

---

## USERS

### `POST /payments/users`
**Description:** Add a user in the payment_ms database.  
**Authentication:** None  
**Request JSON:**
```json
{
  "id": 0,
  "name": "",
  "surname": ""
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "New user successfully inserted",
  "code": 0,
  "user": {
    "id": 0,
    "name": "",
    "surname": ""
  }
}

// 404 Not Found
{ "desc": "Missing required fields", "code": 1 }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

## PAYMENTS

### `POST /payments/request`
**Description:** Register a new payment for a reservation made by a user.  
**Authentication:** None  
**Request JSON:**
```json
{
  "amount": 0.0,
  "method": "",
  "reservation_id": 0,
  "user_id": 0,
  "resident_id": 0,
  "reservation_date": "YYYY-MM-DD",
  "reservation_start": "HH:MM",
  "reservation_end": "HH:MM"
}
```
**Valid payment methods:** `applepay`, `paypal`, `googlepay`, `creditcard`

**Responses:**
```json
// 201 Created
{
  "desc": "Payment created successfully",
  "code": "0",
  "payment": {
    "id": 0,
    "amount": "0.00",
    "method": "",
    "payment_status": "pending",
    "payment_ts": "YYYY-MM-DDTHH:MM:SS",
    "reservation_id": 0,
    "user_id": 0,
    "resident_id": 0
  }
}

// 400 Bad Request
{ "desc": "Missing required fields", "code": "1" }
{ "desc": "Invalid payment method. Must be one of: applepay, paypal, googlepay, creditcard", "code": "2" }

// 404 Not Found
{ "desc": "User not found", "code": "3" }
{ "desc": "Resident not found", "code": "4" }

// 409 Conflict
{ "desc": "Payment already exists for this reservation", "code": "5" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `GET /payments/user/{user_id}/earnings`
**Description:** Retrieve total earnings, incomes, and outcomes for a specific user, along with the complete payment history.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Total earnigns retrieved successfully",
  "code": "0",
  "earnings": 0.0,
  "payments_list": [
    {
      "id": 0,
      "amount": "0.00",
      "method": "",
      "payment_status": "",
      "payment_ts": "YYYY-MM-DDTHH:MM:SS",
      "reservation_id": 0,
      "modality": "made|received",
      "resident": ""
    }
  ]
}

// 404 Not Found
{ "desc": "Invalid user", "code": "1" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `PUT /payments/{payment_id}`
**Description:** Update the status of an existing payment (e.g., from pending to completed or failed).  
**Authentication:** None  
**Request JSON:**
```json
{
  "payment_status": ""
}
```
**Valid payment statuses:** `completed`, `failed`

**Responses:**
```json
// 200 OK
{
  "desc": "Payment status updated successfully",
  "code": "0",
  "payment": {
    "id": 0,
    "amount": "0.00",
    "method": "",
    "payment_status": "",
    "payment_ts": "YYYY-MM-DDTHH:MM:SS",
    "reservation_id": 0,
    "user_id": 0
  }
}

// 400 Bad Request
{ "desc": "Missing payment_status field", "code": "1" }
{ "desc": "Invalid payment status. Must be one of: completed, failed", "code": "2" }

// 404 Not Found
{ "desc": "Payment not found", "code": "3" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

**Author:** EzParking Development Team  
**Module:** Payment Microservice  
**Language:** Python (Flask + SQLAlchemy)
