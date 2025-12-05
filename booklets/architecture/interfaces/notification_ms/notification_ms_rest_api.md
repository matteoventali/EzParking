## REST API of the *Notification microservice*
### Endpoints list

| **Method** | **Endpoint** | **Description** |
|:------------|:-------------|:----------------|
| **POST** | `/notifications/users` | Insert a new user when registration occurs |
| **PUT** | `/user/{user_id}` | Update user's last login timestamp and last known position |
| **POST** | `/notifications/nearby_alert` | Send notifications to users near a newly available parking spot |
| **POST** | `/notifications/reservation_accepted` | Send notification when a reservation is accepted |
| **POST** | `/notifications/reservation_rejected` | Send notification when a reservation is rejected |
| **POST** | `/notifications/reservation_cancelled` | Send notification when a reservation is cancelled |
| **POST** | `/notifications/reservation_request` | Send notification when a new reservation request is received |
| **POST** | `/notifications/registration_successfull` | Send welcome email when a new user successfully registers |
| **POST** | `/notifications/received_review` | Send notification when a user receives a new review |
| **POST** | `/notifications/account_disabled` | Send notification when a user's account is disabled |
| **POST** | `/notifications/account_enabled` | Send notification when a user's account is re-enabled |
