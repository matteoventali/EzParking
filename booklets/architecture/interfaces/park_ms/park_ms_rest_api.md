#### REST API of the *Park microservice*

| Method | Endpoint | Description |
|:--------|:----------|:-------------|
| **POST** | `/users` | Add a user in the park_ms db. |
| **GET** | `/parking_spots/{id}` | Retrieve details of a specific parking spot. |
| **GET** | `/parking_spots/users/{user_id}` | Retrieve details of all parking spots of a specific user. |
| **POST** | `/parking_spots` | Create a new parking spot owned by the authenticated user. |
| **PUT** | `/parking_spots/{id}` | Update parking spot information.|
| **DELETE** | `/parking_spots/{id}` | Delete a parking spot. Requires Authorization header. |
| **GET** | `/parking_spots/{id}/labels` | Get all labels associated with a parking spot. |
| **DELETE** | `/parking_spots/{id}/labels/{label_id}` | Remove a label from a parking spot. |
| **GET** | `/time_slots/{park_id}` | Retrieve availability time slots of a parking spot. |
| **POST** | `/time_slots` | Add a new availability slot for a parking spot. |
| **DELETE** | `/time_slots/{slot_id}` | Delete an availability slot. |
| **POST** | `/search` | Retrieve all parking spots in a specific radius near me. |
| **GET** | `/reservations` | Retrieve all reservations of the authenticated user. |
| **GET** | `/reservations/{id}` | Retrieve details of a specific reservation. |
| **POST** | `/reservations` | Create a new reservation for a parking slot. |
| **PUT** | `/reservations/{id}` | Update the status of a reservation (pending, confirmed, cancelled, completed). |
| **DELETE** | `/reservations/{id}` | Cancel a reservation. |
| **GET** | `/requests` | Retrieve all booking requests. |
| **GET** | `/labels` | Retrieve all available labels in the system. |
| **POST** | `/labels` | Create a new label.**_ONLY FOR ADMIN_**. |