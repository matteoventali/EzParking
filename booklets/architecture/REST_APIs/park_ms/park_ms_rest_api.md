#### REST API of the *Park microservice*

| Method | Endpoint | Description |
|:--------|:----------|:-------------|
| **GET** | `/parking_spots/{id}` | Retrieve details of a specific parking spot. |
| **POST** | `/parking_spots` | Create a new parking spot owned by the authenticated user. |
| **PUT** | `/parking_spots/{id}` | Update parking spot information.|
| **DELETE** | `/parking_spots/{id}` | Delete a parking spot. Requires Authorization header. |
| **GET** | `/parking_spots/{id}/labels` | Get all labels associated with a parking spot. |
| **DELETE** | `/parking_spots/{id}/labels/{label_id}` | Remove a label from a parking spot. |
| **GET** | `/availability/{park_id}` | Retrieve availability time slots of a parking spot. |
| **POST** | `/availability` | Add a new availability slot for a parking spot. |
| **POST** | `/availability/search` | Retrieve all parking spots in a specific radius near me. |
| **DELETE** | `/availability` | Delete an availability slot. |
| **GET** | `/reservations` | Retrieve all reservations of the authenticated user. |
| **GET** | `/reservations/{id}` | Retrieve details of a specific reservation. |
| **POST** | `/reservations` | Create a new reservation for a parking slot. |
| **PUT** | `/reservations/{id}` | Update the status of a reservation (pending, confirmed, cancelled, completed). |
| **DELETE** | `/reservations/{id}` | Cancel a reservation. |
| **GET** | `/labels` | Retrieve all available labels in the system. |
| **POST** | `/labels` | Create a new label. Requires Authorization headerCH **_ONLY FOR ADMIN_**. |