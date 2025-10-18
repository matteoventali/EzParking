#### REST API of the *Account microservice*

| **Method** | **Endpoint** | **Description** |
|:------------|:-------------|:----------------|
| **POST** | `/auth/signup` | Signup of a new user |
| **POST** | `/auth/login` | Login |
| **GET** | `/auth/logout` | Logout |
| **GET** | `/auth/status` | Get status of the current session |
| **PUT** | `/pdata` | Update personal user's data |
| **GET** | `/pdata` | Obtain personal user's data |
| **GET** | `/reviews` | Get the list of reviews that hit the invoker |
| **POST** | `/reviews` | Add a new review from the invoker  |
| **GET** | `/users` | Get the list of all users in the system **_ONLY FOR ADMIN_** |
| **GET** | `/users/{user_id}` | Get the dashboard of the selected user **_ONLY FOR ADMIN_** |
| **GET** | `/users/{user_id}/enable` | Enable the account of the selected user **_ONLY FOR ADMIN_** |
| **GET** | `/users/{user_id}/disable` | Disable the account of the selected user **_ONLY FOR ADMIN_** |