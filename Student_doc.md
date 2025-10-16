# CONTAINERS

## CONTAINER_NAME: dbms

### DESCRIPTION: 
Database container running MariaDB. It stores data for all EzParking microservices and initializes schemas on startup.

### USER STORIES:
To be completed in future.

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
To be completed in future.

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
		
	| HTTP METHOD | URL | Description |
	| ----------- | --- | ----------- |
	| POST | /auth/signup | Register new user |
	| POST | /auth/login | Authenticate user |
	| GET | /auth/logout | Logout user |
	| GET | /auth/status | Check session validity |
	| GET | /pdata | Retrieve personal user data |
	| PUT | /pdata | Update personal user data |
	| GET | /reviews | Retrieve written and received reviews |
	| POST | /reviews | Add new review |
	| GET | /users | Retrieve all users (admin only) |
	| GET | /users/<user_id> | Retrieve a specific user dashboard (admin only) |

- DB STRUCTURE:

	**_Users_** : | **_id_** | name | surname | password_hash | email | lastlogin_ts | session_token | phone | user_role |

	**_Review_** : | **_id_** | review_date | star | review_description | writer_id | target_id | reservation_id |

---

## CONTAINER_NAME: ui_ms

### DESCRIPTION: 
Frontend container serving static web content or a web application for EzParking users.

### USER STORIES:
To be completed in future.

### PORTS: 
80:80, 443:443

### DESCRIPTION:
Provides the user interface for accessing EzParking services. It communicates with backend APIs (like account_ms) over HTTP.

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
  Web server (Apache or Nginx), HTML, CSS, JavaScript.
- SERVICE ARCHITECTURE:
  Multi-page or single-page web application consuming backend REST APIs.
