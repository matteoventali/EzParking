#### REST API of the *Notification microservice*

| **Method** | **Endpoint** | **Description** |
|:------------|:-------------|:----------------|
| **POST** | `/user` | Insert a new user when registration occurs |
| **PUT** | `/user/{user_id}/` | Update user's information (name, lastname) |
| **PUT** | `/user/{user_id}/sync` | Update user's last login timestamp and last known position |
| **POST** | `/notifications/parking_available` | Send notifications to users near a newly available parking spot |
| **GET** | `/notifications/{user_id}/account_disabled` | Send notification when a user's account is disabled |
| **GET** | `/notifications/{user_id}/account_enabled` | Send notification when a user's account is re-enabled |