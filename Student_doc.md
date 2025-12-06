# SYSTEM DESCRIPTION
The goal of this system is to create a parking sharing application that enables
users to find and share parking spaces in urban areas in order to reduce traffic congestion 
and environmental impact, and promoting collaboration among users.

In particular, the system will allow users to:
-   Share information about available parking spaces in real-time, including private and
    public spots.
-   Search for parking spaces near a specific location, with updates on availability (exploiting the geolocalization).
-   Reserve parking spaces, if applicable, based on user permissions.

The most important use cases are:
1. Finding a Parking Spot:
    A user opens the (web)app to locate available parking spaces within a specific radius.
    The app displays nearby spaces shared by other users, including details such
    as size, cost, and estimated walking distance.
2. Sharing a Parking Spot:
    A resident or business updates the app to mark their parking space as available.
3. Community Features:
    Users rate and review shared parking spots, contributing to a reputation system
    that helps identify reliable contributors of the system.


# USER STORIES
1.	As an administrator I want to be able to visualize the status of my application's microservices, so I know if there are some problems.
2.	As an administrator I want to view all the users and search for them so I can manage their accounts.
3.	As an administrator I want to have access to a user’s information so I can decide to disable or enable their account.
4.	As a user I want to register to the system so that I can access to the application.
5.	As a user I want to receive a confirmation mail after I registered so that I know that all has gone fine.
6.	As a user I want to login into the system so that I can authenticate myself to the platform and use app’s functionalities.
7.	As a user I want to logout from the system so that I can exit from the platform while I don’t use it.
8.	As a user I want to modify personal data so that I can keep updated my account.
9.	As a user I want to have a personal dashboard so that I can check my personal data and history.
10.	As a user I want to see all the functionalities of the app in my homepage so that I can access easily to them.
11.	As user I want to see my statistics in the personal dashboard, so I can know the number of my active reservations, my available parking spots and more.
12.	As a user I want to receive a notification if my account has been disabled or enabled by administrators so that I know if I can use the app.

13.	As a driver I want a map in the homepage that displays all the available parking spots so that I can precisely choose the parking spot position.
14.	As a driver I want to search for available parking spots so that I can book it.
15.	As a driver I want to be able to search parking spots through filters so that I can find the best parking for my needs.
16.	As a driver I want to search for a parking spot selecting a distance range so that I can find the parking space in the best possible position.
17.	As a driver I want to book a parking spot for a specific interval of time and pay for it so that I can easily park my car.
18.	As a driver I want to see all parking spots available when I search for them, in order to decide myself which I want to book.
19.	As a driver I want to receive the booking confirmation by email when the resident accepts my request, so that I can check the reservation details.
20.	As a driver I want to receive a notification when a resident rejects my request, so I can know whether I have to book another parking spot.
21.	As a driver I want to choose the payment method so that I can pay the parking spot in several digital ways.
22.	As a driver I want to see the list of all my reservations requests, so that I can see which are still waiting to be accepted by the resident and which have been already accepted or rejected. 
23.	As a driver I want to be able to delete a reservation request so that I can free a parking spot if I don’t need it anymore.
24.	As a driver I want to rate and comment the parking service so that I can share my parking experience with other drivers.
25.	As a driver I want to access my reputation score on my dashboard so I can know which parking spaces I can book.
26.	As a driver I want to see all my bookings in a calendar view, so I can quickly understand my schedule and have a clear overview of my reservations.
27. As a driver I want to receive the details of the resident after a booking so that I can contact them if needed.
28.	As a driver I want to see all the reviews I have received from the residents so that I can evaluate my behaviour.
29.	As a driver I want to rate a resident that used one of my parking spots, so that I can share the related experience.

30.	As a resident I want to insert into the system my parking space so that I can share it with other users and gain revenues.
31.	As a resident I want to assign labels to my parking spots so that drivers can easily search for them.
32. As a resident I want to view a list of all my parking spots and add availability time slots, so I can decide when each spot can be booked by the drivers.
33.	As a resident I want to view all booking requests for my parking spots so that I can choose which drivers to authorize for each specific spot and time slot.
34.	As a resident I want to see driver's details and reviews in a request so that I can choose if accept the request.
35.	As a resident I want to reject a reservation request so that I can have the full control of my parking spaces.
36.	As a resident I want to set a reputation treshold to reserve a parking spot, so that I can give priority to higher reputation users.
37.	As a resident I want to rate a driver that used one of my parking spots, so that I can share the related experience.
38.	As a resident I want to receive notifications when a driver asks for a booking so that I’m informed about it.
39.	As a resident I want to receive a notification if a driver cancels their booking request on my parking spot, so I can be updated in real time about the demand for my parking spots.
40. As a resident I want to see all the reviews I have received from the drivers so that I can evaluate my behaviour.




# CONTAINERS

## CONTAINER_NAME: dbms

### DESCRIPTION: 
Database container running MariaDB. It stores data for all EzParking microservices and initializes schemas on startup.

### PORTS: 
3306:3306

### DESCRIPTION:
This container provides the persistent storage layer of the application. It initializes all required schemas for the microservices and provides access to data through SQL connections.

### PERSISTENCE EVALUATION
Persistent — data stored in volume `mariadb_data` is preserved across container restarts.

### EXTERNAL SERVICES CONNECTIONS
None. It is accessed internally by the backend microservices via the Docker network.

---

## CONTAINER_NAME: account_ms

### DESCRIPTION: 
Backend microservice handling user accounts, authentication, personal data management, and review system between users.

### USER STORIES:
- As an administrator I want to view all the users and search for them so I can manage their accounts.
- As an administrator I want to have access to a user’s information so I can decide to disable or enable their account.
- As a user I want to register to the system so that I can access to the application.
- As a user I want to receive a confirmation mail after I registered so that I know that all has gone fine.
- As a user I want to login into the system so that I can authenticate myself to the platform and use app’s functionalities.
- As a user I want to logout from the system so that I can exit from the platform while I don’t use it.
- As a user I want to modify personal data so that I can keep updated my account.
- As a user I want to have a personal dashboard so that I can check my personal data and history.
- As user I want to see my statistics in the personal dashboard, so I can know the number of my active reservations, my available parking - spots and more.
- As a driver I want to rate and comment the parking service so that I can share my parking experience with other drivers.
- As a driver I want to access my reputation score on my dashboard so I can know which parking spaces I can book.
- As a driver I want to see all the reviews I have received from the residents so that I can evaluate my behaviour.
- As a driver I want to rate a resident that used one of my parking spots, so that I can share the related experience.
- As a resident I want to rate a driver that used one of my parking spots, so that I can share the related experience.
- As a resident I want to see all the reviews I have received from the drivers so that I can evaluate my behaviour.

### PORTS: 
5000:5000

### DESCRIPTION:
Implements all account-related operations through RESTful APIs, including signup, login, profile management, and reviews. Communicates with the MariaDB instance inside `dbms` for persistence.

### PERSISTENCE EVALUATION
Stateless container. All persistent data is stored in `dbms`.

### EXTERNAL SERVICES CONNECTIONS
Connects to the MariaDB service hosted in `dbms` (IP 10.5.0.10).

### MICROSERVICES:

#### MICROSERVICE: account_ms
- TYPE: backend
- DESCRIPTION: Handles user account lifecycle and reviews.
- PORTS: 5000
- TECHNOLOGICAL SPECIFICATION:
  Python 3, Flask, Flask-SQLAlchemy, MySQL Connector, REST APIs returning JSON.
- SERVICE ARCHITECTURE: 
  Stateless RESTful service communicating with a shared database layer.

- ENDPOINTS:
	| **Method** | **Endpoint** | **Description** |
	|------------|-------------|----------------|
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
	| **GET** | `/users/active_count` | Get the numbers of users currently logged into the system **_ONLY FOR ADMIN_** |

- DB STRUCTURE:
	**_Users_** : | **_id_** | name | surname | password_hash | email | lastlogin_ts | session_token | phone | user_role | cc_number |

	**_Review_** : | **_id_** | review_date | star | review_description | writer_id | target_id | reservation_id |

---

## CONTAINER_NAME: notification_ms

### DESCRIPTION: 
Backend microservice responsible for sending emails to users regarding their reservations and account activities.

### USER STORIES:
- As a user I want to receive a confirmation mail after I registered so that I know that all has gone fine.
- As a user I want to receive a notification if my account has been disabled or enabled by administrators so that I know if I can use the app.
- As a driver I want to receive the booking confirmation by email when the resident accepts my request, so that I can check the reservation details.
- As a driver I want to receive a notification when a resident rejects my request, so I can know whether I have to book another parking spot.
- As a resident I want to receive notifications when a driver asks for a booking so that I’m informed about it.
- As a resident I want to receive a notification if a driver cancels their booking request on my parking spot, so I can be updated in real time about the demand for my parking spots.

### PORTS: 
5001:5001

### DESCRIPTION:
Listens for triggers from other microservices (like reservation confirmations or account alerts) to send notifications.

### PERSISTENCE EVALUATION
Stateless container. Persistent user's contact info are mantained into the dbms.

### EXTERNAL SERVICES CONNECTIONS
Connects to the MariaDB service hosted in `dbms` (IP 10.5.0.10).

### MICROSERVICES:

#### MICROSERVICE: notification_ms
- TYPE: backend
- DESCRIPTION: Manages communication channels (Email) towards users.
- PORTS: 5001
- TECHNOLOGICAL SPECIFICATION:
  Python 3, Flask, Flask-SQLAlchemy, REST APIs returning JSON.
- SERVICE ARCHITECTURE: 
  Stateless RESTful service.

- ENDPOINTS:
	| **Method** | **Endpoint** | **Description** |
	|:-----------|-------------|----------------|
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

- DB STRUCTURE:
	**_Users_** : | **_id_** | lastlogin_ts | last_position | name | surname | email |

---

## CONTAINER_NAME: park_ms

### DESCRIPTION: 
Core backend microservice managing parking spots, availability, and reservation logic.

### USER STORIES:
- As a driver I want a map in the homepage that displays all the available parking spots so that I can precisely choose the parking spot position.
- As a driver I want to search for available parking spots so that I can book it.
- As a driver I want to be able to search parking spots through filters so that I can find the best parking for my needs.
- As a driver I want to search for a parking spot selecting a distance range so that I can find the parking space in the best possible position.
- As a driver I want to book a parking spot for a specific interval of time and pay for it so that I can easily park my car.
- As a driver I want to see all parking spots available when I search for them, in order to decide myself which I want to book.
- As a driver I want to see the list of all my reservations requests, so that I can see which are still waiting to be accepted by the resident and which have been already accepted or rejected. 
- As a driver I want to be able to delete a reservation request so that I can free a parking spot if I don’t need it anymore.
- As a driver I want to see all my bookings in a calendar view, so I can quickly understand my schedule and have a clear overview of my reservations.
- As a driver I want to receive the details of the resident after a booking so that I can contact them if needed.
- As a resident I want to insert into the system my parking space so that I can share it with other users and gain revenues.
- As a resident I want to assign labels to my parking spots so that drivers can easily search for them.
- As a resident I want to view a list of all my parking spots and add availability time slots, so I can decide when each spot can be booked by the drivers.
- As a resident I want to view all booking requests for my parking spots so that I can choose which drivers to authorize for each specific spot and time slot.
- As a resident I want to see driver's details and reviews in a request so that I can choose if accept the request.
- As a resident I want to reject a reservation request so that I can have the full control of my parking spaces.
- As a resident I want to set a reputation treshold to reserve a parking spot, so that I can give priority to higher reputation users.

### PORTS: 
5002:5002

### DESCRIPTION:
Handles the inventory of parking spots and the logic for booking them. It ensures no double bookings occur and serves search requests.

### PERSISTENCE EVALUATION
Stateless container. Parking data and reservations are stored in `dbms`.

### EXTERNAL SERVICES CONNECTIONS
Connects to the MariaDB service hosted in `dbms` (IP 10.5.0.10).

### MICROSERVICES:

#### MICROSERVICE: park_ms
- TYPE: backend
- DESCRIPTION: Handles parking inventory and booking logic.
- PORTS: 5002
- TECHNOLOGICAL SPECIFICATION:
  Python 3, Flask, Flask-SQLAlchemy, REST APIs returning JSON.
- SERVICE ARCHITECTURE: 
  Stateless RESTful service.

- ENDPOINTS:
	| Method | Endpoint | Description |
	|--------|----------|-------------|
	| **POST** | `/users` | Add a user in the park_ms db. |
	| **GET** | `/users/{user_id}/statistics` | Retrieve statistics for a specific user (owned spots, reservations, etc.). |
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

- DB STRUCTURE:
	**_Users_** : | **_id_** | name | surname |

	**_Labels_** : | **_id_** | name | label_description |

	**_Parking_Spots_** : | **_id_** | name | spot_location | rep_treshold | slot_price | user_id |

	**_Availability_Slots_** : | **_id_** | slot_date | start_time | end_time | parking_spot_id |

	**_Parking_Spot_Labels_** : | **_parking_spot_id_** | **_label_id_** |

	**_Reservations_** : | **_id_** | reservation_ts | reservation_status | car_plate | slot_id | user_id | payment_id |

---

## CONTAINER_NAME: payment_ms

### DESCRIPTION: 
Backend microservice handling financial transactions payments for reservations and get analytics about that.

### USER STORIES:
- As a driver I want to choose the payment method so that I can pay the parking spot in several digital ways

### PORTS: 
5003:5003

### DESCRIPTION:
Processes payments for bookings created in `park_ms`. It ensures transactions are recorded.

### PERSISTENCE EVALUATION
Stateless container. Transaction history is stored in `dbms`.

### EXTERNAL SERVICES CONNECTIONS
Connects to the MariaDB service hosted in `dbms` (IP 10.5.0.10).

### MICROSERVICES:

#### MICROSERVICE: payment_ms
- TYPE: backend
- DESCRIPTION: Manages payments and transaction history.
- PORTS: 5003
- TECHNOLOGICAL SPECIFICATION:
  Python 3, Flask, Flask-SQLAlchemy, REST APIs returning JSON.
- SERVICE ARCHITECTURE: 
  Stateless RESTful service.

- ENDPOINTS:
	| **Method** | **Endpoint** | **Description** |
	|------------|-------------|----------------|
	| **POST** | `/payments/users` | Add a user in the payment_ms database |
	| **POST** | `/payments/request` | Register a new payment for a reservation made by a user |
	| **GET** | `/payments/user/{user_id}/earnings` | Retrieve total earnings and payment history for a specific user |
	| **PUT** | `/payments/{payment_id}` | Update the status of an existing payment (e.g., from pending to completed) |


- DB STRUCTURE:
	**_Users_** : | **_id_** | name | surname |

	**_Payments_** : | **_id_** | payment_ts | amount | payment_status | method | reservation_id | user_id | resident_id | reservation_date | reservation_start | reservation_end |

---

## CONTAINER_NAME: ui_ms

### DESCRIPTION: 
Frontend container serving static web content or a web application for EzParking users.

### USER STORIES:
To be completed in future.

### PORTS: 
80:80, 443:443

### DESCRIPTION:
Provides the user interface for accessing EzParking services. It communicates with backend APIs (like account_ms, park_ms) over HTTP.

### PERSISTENCE EVALUATION
Stateless. No data persistence; static files are mounted as a volume.

### EXTERNAL SERVICES CONNECTIONS
Connects to backend REST APIs through HTTP requests.

### MICROSERVICES:

#### MICROSERVICE: ui_ms
- TYPE: frontend
- DESCRIPTION: Provides the graphical user interface for EzParking.
- PORTS: 80, 443
- TECHNOLOGICAL SPECIFICATION:
  Web server Apache, HTML, CSS, JavaScript, PHP.
- SERVICE ARCHITECTURE:
  Multi-page or single-page web application consuming backend REST APIs.