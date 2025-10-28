# Park Microservice API Documentation

Comprehensive documentation for all REST endpoints of the **Park Microservice** in EzParking.

---

## Base URL
```
http://10.5.0.12:5002/
```

---

## Root
### `GET /`
**Description:** Service health check.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{ "message": "Parking Service is active" }
```

---

## USERS

### `POST /users`
**Description:** Create a new user in the parking microservice.  
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
  "desc": "User created successfully",
  "code": "0",
  "user": {
    "id": 0,
    "name": "",
    "surname": ""
  }
}

// 400 Bad Request
{ "desc": "Missing required fields", "code": "1" }

// 409 Conflict
{ "desc": "User with id {user_id} already exists", "code": "2" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

## PARKING SPOTS

### `GET /parking_spots/{spot_id}`
**Description:** Retrieve detailed information about a specific parking spot.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Parking spot retrieved successfully",
  "code": "0",
  "parking_spot": {
    "id": 0,
    "name": "",
    "longitude": 0.0,
    "latitude": 0.0,
    "rep_treshold": 0,
    "slot_price": 0.0,
    "user": {
      "id": 0,
      "name": "",
      "surname": ""
    },
    "labels": [],
    "time_slots": []
  }
}

// 404 Not Found
{ "desc": "Parking spot {spot_id} not found", "code": "1" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `GET /parking_spots/users/{user_id}`
**Description:** Retrieve all parking spots owned by a specific user.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Parking spots retrieved successfully",
  "code": "0",
  "parking_spots": []
}

// 404 Not Found
{ 
  "desc": "The user has not parking spots", 
  "code": "2", 
  "parking_spots": [] 
}

// 404 Not Found
{ "desc": "The user doesn't exists", "code": "1" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `POST /parking_spots`
**Description:** Create a new parking spot.  
**Authentication:** None  
**Request JSON:**
```json
{
  "name": "",
  "latitude": 0.0,
  "longitude": 0.0,
  "slot_price": 0.0,
  "rep_treshold": 0,
  "user_id": 0
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "Parking spot created successfully",
  "code": "0",
  "parking_spot": {
    "id": 0,
    "name": "",
    "latitude": 0.0,
    "longitude": 0.0,
    "slot_price": 0.0,
    "rep_treshold": 0,
    "user_id": 0
  }
}

// 400 Bad Request
{ "desc": "Missing required fields", "code": "2" }
{ "desc": "Latitude and longitude must be numeric", "code": "3" }
{ "desc": "rep_treshold must be between 0 and 5", "code": "4" }
{ "desc": "slot_price must be non-negative", "code": "5" }

// 401 Unauthorized
{ "desc": "Invalid user's id", "code": "6" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `PUT /parking_spots/{spot_id}`
**Description:** Update parking spot name.  
**Authentication:** None  
**Request JSON:**
```json
{
  "name": ""
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Parking spot name updated successfully",
  "code": "0",
  "parking_spot": {
    "id": 0,
    "name": "",
    "rep_treshold": 0,
    "slot_price": 0.0
  }
}

// 400 Bad Request
{ "desc": "Missing request body", "code": "1" }
{ "desc": "Missing field: name", "code": "2" }
{ "desc": "Invalid name value", "code": "3" }

// 404 Not Found
{ "desc": "Parking spot {spot_id} not found", "code": "4" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `DELETE /parking_spots/{spot_id}`
**Description:** Delete a parking spot.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{ "desc": "Parking spot {spot_id} deleted successfully", "code": "0" }

// 404 Not Found
{ "desc": "Parking spot {spot_id} not found", "code": "1" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `GET /parking_spots/{spot_id}/labels`
**Description:** Retrieve all labels associated with a parking spot.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Labels retrieved successfully",
  "code": "0",
  "parking_spot": {
    "id": 0,
    "name": ""
  },
  "labels": []
}

// 404 Not Found
{ "desc": "Parking spot {spot_id} not found", "code": "1" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `POST /parking_spots/{spot_id}/labels`
**Description:** Add a label to a parking spot.  
**Authentication:** None  
**Request JSON:**
```json
{
  "label_id": 0
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "Label {label_id} successfully added to parking spot {spot_id}",
  "code": "0",
  "parking_spot_label": {
    "parking_spot_id": 0,
    "label_id": 0,
    "label_name": "",
    "label_description": ""
  }
}

// 400 Bad Request
{ "desc": "Missing request body", "code": "1" }
{ "desc": "Missing field: label_id", "code": "2" }
{ "desc": "Invalid label_id type (must be integer)", "code": "3" }

// 404 Not Found
{ "desc": "Parking spot {spot_id} not found", "code": "4" }
{ "desc": "Label {label_id} not found", "code": "5" }

// 409 Conflict
{ "desc": "Label {label_id} is already associated with parking spot {spot_id}", "code": "6" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `DELETE /parking_spots/{spot_id}/labels/{label_id}`
**Description:** Remove a label from a parking spot.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{ "desc": "Label {label_id} removed from parking spot {spot_id} successfully", "code": "0" }

// 404 Not Found
{ "desc": "Parking spot {spot_id} not found", "code": "1" }
{ "desc": "Label {label_id} not found", "code": "2" }
{ "desc": "Label {label_id} is not associated with parking spot {spot_id}", "code": "3" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

## TIME SLOTS

### `GET /time_slots/{park_id}`
**Description:** Retrieve all available future time slots for a parking spot.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Available future time slots retrieved successfully",
  "code": "0",
  "parking_spot": {
    "id": 0,
    "name": ""
  },
  "available_slots": [],
  "count": 0
}

// 404 Not Found
{ "desc": "Parking spot {park_id} not found", "code": "1" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `POST /time_slots/{park_id}`
**Description:** Create a new availability slot for a parking spot.  
**Authentication:** None  
**Request JSON:**
```json
{
  "slot_date": "YYYY-MM-DD",
  "start_time": "HH:MM",
  "end_time": "HH:MM"
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "Availability slot created successfully",
  "code": "0",
  "availability_slot": {
    "id": 0,
    "slot_date": "YYYY-MM-DD",
    "start_time": "HH:MM",
    "end_time": "HH:MM",
    "parking_spot_id": 0
  }
}

// 400 Bad Request
{ "desc": "Missing request body", "code": "1" }
{ "desc": "Missing required fields", "code": "2" }
{ "desc": "Invalid date/time format", "code": "3" }
{ "desc": "Invalid time range (end_time must be after start_time)", "code": "4" }

// 404 Not Found
{ "desc": "Parking spot {park_id} not found", "code": "5" }

// 409 Conflict
{ "desc": "Time slot overlaps with existing slot <time> on <date>", "code": "6" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `DELETE /time_slots/{slot_id}`
**Description:** Delete an availability slot if no active reservations exist.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{ "desc": "Availability slot {slot_id} deleted successfully", "code": "0" }

// 404 Not Found
{ "desc": "Availability slot {slot_id} not found", "code": "1" }

// 409 Conflict
{ "desc": "Slot {slot_id} cannot be deleted because it has an active reservation (<status>)", "code": "2" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

## SEARCHING

### `POST /search`
**Description:** Search for available parking spots based on location, reputation, radius, and labels.  
**Authentication:** None  
**Request JSON:**
```json
{
  "latitude": 0.0,
  "longitude": 0.0,
  "user_reputation": 0.0,
  "radius": 0.0,
  "labels": []
}
```
**Responses:**
```json
// 200 OK
{
  "desc": "Available parking spots retrieved successfully",
  "code": "0",
  "count": 0,
  "user_reputation": 0.0,
  "filters": {
    "radius": 0.0,
    "labels": []
  },
  "results": []
}

// 200 OK (no results)
{ "desc": "No parking spots found within the given filters", "code": "5", "results": [], "user_reputation": 0.0 }
{ "desc": "No available parking slots found within reputation or filter constraints", "code": "6", "results": [], "user_reputation": 0.0 }

// 400 Bad Request
{ "desc": "Missing request body", "code": "1" }
{ "desc": "Missing required fields", "code": "2" }
{ "desc": "Latitude, longitude, reputation or radius must be numeric", "code": "3" }
{ "desc": "Radius must be greater than zero", "code": "4" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

## RESERVATIONS

### `GET /reservations/users/{user_id}`
**Description:** Retrieve all reservations made by a specific user.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Reservations retrieved successfully",
  "code": "0",
  "count": 0,
  "reservations": []
}

// 200 OK (no reservations)
{ "desc": "No reservations found for this user", "code": "1", "reservations": [] }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `GET /reservations/{res_id}`
**Description:** Retrieve details of a specific reservation.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Reservations retrieved successfully",
  "code": "0",
  "reservation": {
    "res_id": 0,
    "user_id": 0,
    "ts": "",
    "status": "",
    "slot_id": 0,
    "start_time": "HH:MM",
    "end_time": "HH:MM",
    "slot_date": "YYYY-MM-DD"
  }
}

// 200 OK (not found)
{ "desc": "No reservation found with this id", "code": "1", "reservation": {} }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `POST /reservations`
**Description:** Create a new reservation for a parking slot.  
**Authentication:** None  
**Request JSON:**
```json
{
  "slot_id": 0,
  "car_plate": "",
  "user_id": 0
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "Reservation created successfully (pending approval)",
  "code": "0",
  "reservation": {
    "id": 0,
    "slot_id": 0,
    "user_id": 0,
    "car_plate": "",
    "reservation_status": "pending",
    "reservation_ts": ""
  }
}

// 400 Bad Request
{ "desc": "Missing required fields", "code": "2" }
{ "desc": "slot_id must be an integer", "code": "3" }
{ "desc": "car_plate must be 7 characters", "code": "4" }
{ "desc": "This is your parking spot, impossible to book it", "code": "5" }

// 404 Not Found
{ "desc": "Slot {slot_id} not found", "code": "6" }

// 409 Conflict
{ "desc": "Slot {slot_id} is already reserved (pending or confirmed)", "code": "7" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `PUT /reservations/{res_id}/status`
**Description:** Update reservation status with state machine validation.  
**Authentication:** None  
**Request JSON:**
```json
{
  "new_status": ""
}
```
**Valid status transitions:**
- `pending` → `confirmed`, `cancelled`
- `confirmed` → `completed`, `cancelled`

**Responses:**
```json
// 200 OK
{
  "desc": "Reservation status updated successfully",
  "code": "0",
  "reservation": {
    "id": 0,
    "slot_id": 0,
    "user_id": 0,
    "car_plate": "",
    "previous_status": "",
    "new_status": "",
    "reservation_ts": ""
  }
}

// 200 OK (already in status)
{ "desc": "Reservation already in status '<status>'", "code": "4" }

// 400 Bad Request
{ "desc": "Missing new_status in request body", "code": "1" }
{ "desc": "Invalid reservation_status value: '<status>'", "code": "2" }
{ "desc": "Transition from '<old>' to '<new>' not allowed", "code": "5" }

// 404 Not Found
{ "desc": "Reservation {res_id} not found", "code": "3" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

## REQUESTS

### `GET /requests/{user_id}`
**Description:** Retrieve pending reservation requests for parking spots owned by the user.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Pending reservation requests retrieved successfully",
  "code": "0",
  "count": 0,
  "requests": []
}

// 200 OK (no requests)
{ "desc": "No pending reservation requests for your parking spots", "code": "1", "requests": [] }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

## LABELS

### `GET /labels`
**Description:** Retrieve all available labels.  
**Authentication:** None  
**Responses:**
```json
// 200 OK
{
  "desc": "Labels retrieved successfully",
  "code": "0",
  "labels": []
}

// 200 OK (no labels)
{ "desc": "No labels found", "code": "1", "labels": [] }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

### `POST /labels`
**Description:** Create a new label.  
**Authentication:** None  
**Request JSON:**
```json
{
  "name": "",
  "description": ""
}
```
**Responses:**
```json
// 201 Created
{
  "desc": "Label created successfully",
  "code": "0",
  "label": {
    "id": 0,
    "name": "",
    "description": ""
  }
}

// 400 Bad Request
{ "desc": "Missing request body", "code": "1" }
{ "desc": "Missing required fields", "code": "2" }
{ "desc": "Invalid name or description", "code": "3" }

// 409 Conflict
{ "desc": "Label with name \"<name>\" already exists", "code": "4" }

// 500 Internal Server Error
{ "desc": "Database error: <error>", "code": "99" }
```

---

**Author:** EzParking Development Team  
**Module:** Park Microservice  
**Language:** Python (Flask + SQLAlchemy + GeoAlchemy2)
