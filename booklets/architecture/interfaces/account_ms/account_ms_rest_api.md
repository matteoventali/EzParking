## REST API of the *Account microservice*
### Endpoints list

| **Method** | **Endpoint** | **Description** |
|:----------:|:-------------|:----------------|
| **POST**   | `/auth/signup`                    | Register a new user |
| **POST**   | `/auth/login`                     | Authenticate user and generate session token |
| **GET**    | `/auth/logout`                    | Logout the user and invalidate session token |
| **GET**    | `/auth/status`                    | Check validity of the session token |
| **GET**    | `/pdata`                          | Retrieve personal user data and score |
| **PUT**    | `/pdata`                          | Update personal user data |
| **GET**    | `/reviews`                        | Retrieve reviews written and received by the user |
| **GET**    | `/reviews/{user_id}`              | Retrieve reviews for a specific user |
| **POST**   | `/reviews`                        | Submit a new review |
| **GET**    | `/users`                          | Retrieve list of all users (admin only) |
| **GET**    | `/users/{user_id}`                | Retrieve dashboard for a specific user |
| **GET**    | `/users/{user_id}/enable`         | Enable a user account (admin only) |
| **GET**    | `/users/{user_id}/disable`        | Disable a user account (admin only) |
| **GET**    | `/users/active_count`             | Count users currently logged in (admin only) |
