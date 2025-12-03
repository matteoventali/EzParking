#### REST API of the *Payment microservice*

| **Method** | **Endpoint** | **Description** |
|:------------|:-------------|:----------------|
| **POST** | `/payments` | Register a new payment for a reservation made by a user |
| **GET** | `/payments/user/{user_id}` | Retrieve all payments made by a specific user |
| **GET** | `/payments/reservation/{reservation_id}` | Retrieve the payment associated with a specific reservation |
| **PUT** | `/payments/{payment_id}` | Update the status of an existing payment (e.g., from pending to completed) |
