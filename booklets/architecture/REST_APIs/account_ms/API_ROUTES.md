# EzParking API — Routes Documentation

This document describes all available API routes, including HTTP method, endpoint, headers, JSON request format, and JSON response format.

---

## Service status

**Method:** `GET`
**Endpoint:** `/`
**Headers:** None

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "message": "Service is active"
}
```

---

## User signup

**Method:** `POST`
**Endpoint:** `/auth/signup`
**Headers:** `Content-Type: application/json`

**Request JSON:**

```json
{
  "name": "<string>",
  "surname": "<string>",
  "email": "<string>",
  "password": "<string>",
  "phone": "<string>"
}
```

**Response JSON:**

```json
{
  "desc": "User registered successfully",
  "code": "0",
  "user": {
    "id": "<int>",
    "name": "<string>",
    "surname": "<string>",
    "email": "<string>",
    "phone": "<string>",
    "role": "user"
  }
}
```

---

## User login

**Method:** `POST`
**Endpoint:** `/auth/login`
**Headers:** `Content-Type: application/json`

**Request JSON:**

```json
{
  "email": "<string>",
  "password": "<string>"
}
```

**Response JSON:**

```json
{
  "desc": "Login successful",
  "code": "0",
  "user": {
    "id": "<int>",
    "name": "<string>",
    "surname": "<string>",
    "email": "<string>",
    "phone": "<string>",
    "role": "<string>",
    "session_token": "<string>",
    "account_status": "<bool>"
  }
}
```

---

## User logout

**Method:** `GET`
**Endpoint:** `/auth/logout`
**Headers:** `Authorization: <session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "Logout successful",
  "code": "0"
}
```

---

## Session status

**Method:** `GET`
**Endpoint:** `/auth/status`
**Headers:** `Authorization: <session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "Online",
  "code": "0"
}
```

---

## Get personal data

**Method:** `GET`
**Endpoint:** `/pdata`
**Headers:** `Authorization: <session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "User data retrieved successfully",
  "code": "0",
  "user": {
    "name": "<string>",
    "surname": "<string>",
    "email": "<string>",
    "phone": "<string>",
    "score": "<float>"
  }
}
```

---

## Update personal data

**Method:** `PUT`
**Endpoint:** `/pdata`
**Headers:** `Authorization: <session_token>, Content-Type: application/json`

**Request JSON:**

```json
{
  "name": "<string>",
  "surname": "<string>",
  "phone": "<string>",
  "password": "<string>"
}
```

**Response JSON:**

```json
{
  "desc": "User data updated successfully",
  "code": "0",
  "user": {
    "id": "<int>",
    "name": "<string>",
    "surname": "<string>",
    "email": "<string>",
    "phone": "<string>",
    "password": "<bool>"
  }
}
```

---

## List reviews

**Method:** `GET`
**Endpoint:** `/reviews`
**Headers:** `Authorization: <session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "Reviews retrieved successfully",
  "code": "0",
  "written_reviews": [
    {
      "id": "<int>",
      "review_date": "<date>",
      "star": "<int>",
      "review_description": "<string>",
      "reservation_id": "<int>",
      "other_side_id": "<int>",
      "other_side_name": "<string>",
      "other_side_surname": "<string>"
    }
  ],
  "received_reviews": [
    {
      "id": "<int>",
      "review_date": "<date>",
      "star": "<int>",
      "review_description": "<string>",
      "other_side_id": "<int>",
      "other_side_name": "<string>",
      "other_side_surname": "<string>",
      "reservation_id": "<int>"
    }
  ]
}
```

---

## Add review

**Method:** `POST`
**Endpoint:** `/reviews`
**Headers:** `Authorization: <session_token>, Content-Type: application/json`

**Request JSON:**

```json
{
  "target_id": "<int>",
  "reservation_id": "<int>",
  "star": "<int>",
  "review_description": "<string>"
}
```

**Response JSON:**

```json
{
  "desc": "Review added successfully",
  "code": "0",
  "review": {
    "id": "<int>",
    "writer_id": "<int>",
    "target_id": "<int>",
    "reservation_id": "<int>",
    "star": "<int>",
    "review_description": "<string>",
    "review_date": "<date>"
  }
}
```

---

## Admin: Get users list

**Method:** `GET`
**Endpoint:** `/users`
**Headers:** `Authorization: <admin_session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "Users list retrieved successfully",
  "code": "0",
  "users": [
    {
      "id": "<int>",
      "name": "<string>",
      "surname": "<string>",
      "email": "<string>",
      "role": "<string>"
    }
  ]
}
```

---

## Admin: Get user dashboard

**Method:** `GET`
**Endpoint:** `/users/<user_id>`
**Headers:** `Authorization: <admin_session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "User dashboard retrieved successfully",
  "code": "0",
  "user": {
    "id": "<int>",
    "name": "<string>",
    "surname": "<string>",
    "email": "<string>",
    "phone": "<string>",
    "role": "<string>",
    "score": "<float>"
  },
  "received_reviews": [],
  "written_reviews": []
}
```

---

## Admin: Enable user account

**Method:** `GET`
**Endpoint:** `/users/<user_id>/enable`
**Headers:** `Authorization: <admin_session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "User account enabled successfully",
  "code": "0",
  "user": {
    "id": "<int>",
    "name": "<string>",
    "surname": "<string>",
    "email": "<string>",
    "account_status": true
  }
}
```

---

## Admin: Disable user account

**Method:** `GET`
**Endpoint:** `/users/<user_id>/disable`
**Headers:** `Authorization: <admin_session_token>`

**Request JSON:**

```json
{}
```

**Response JSON:**

```json
{
  "desc": "User account disabled successfully",
  "code": "0",
  "user": {
    "id": "<int>",
    "name": "<string>",
    "surname": "<string>",
    "email": "<string>",
    "account_status": false
  }
}
```
