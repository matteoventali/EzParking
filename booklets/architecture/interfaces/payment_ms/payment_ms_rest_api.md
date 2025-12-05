## REST API of the *Payment microservice*
### Endpoints list

| **Method** | **Endpoint** | **Description** |
|:------------|:-------------|:----------------|
| **POST** | `/payments/users` | Add a user in the payment_ms database |
| **POST** | `/payments/request` | Register a new payment for a reservation made by a user |
| **GET** | `/payments/user/{user_id}/earnings` | Retrieve total earnings and payment history for a specific user |
| **PUT** | `/payments/{payment_id}` | Update the status of an existing payment (e.g., from pending to completed) |
