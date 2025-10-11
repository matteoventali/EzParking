#### REST API of the *Account microservice*

| **Method** | **Endpoint** | **Description** |
|:------------|:-------------|:----------------|
| **POST** | `/auth/signup` | Signup of a new user |
| **POST** | `/auth/login` | Login |
| **POST** | `/auth/logout` | Logout |
| **PUT** | `/pdata` | Update personal user's data |
| **GET** | `/pdata` | Obtain personal user's data |
| **GET** | `/reservations` | Get previews of all personal reservations |
| **POST** | `/reservations` | Add a confirmed reservation |
| **GET** | `/reservations/{res_id}` | Get details of a specific personal reservation |
| **DELETE** | `/reservations/{res_id}` | Delete a specific personal reservation |
| **GET** | `/reservations/{res_id}/review` | Get details of the review done to a specific reservation |
| **POST** | `/reservations/{res_id}/review` | Add a review to a terminated reservation |
| **DELETE** | `/reservations/{res_id}/review` | Delete the review done to a specific reservation |
| **GET** | `/users/{user_id}` | Get the dashboard of the selected user **_ONLY FOR ADMIN_** |
