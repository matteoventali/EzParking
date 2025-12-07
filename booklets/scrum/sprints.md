# Sprints organizations

The following section describes the sprint-based organization of the EzParking project, developed following the Scrum framework. Each sprint focuses on specific user stories and goals, gradually delivering core functionalities of the system.

---

## 1st Sprint - Documentation management


> #### Sprint goal 
> Create all necessary documentation to begin the implementation of the application

**Dependecies**: none

---

## 2nd Sprint - Account managment
> Enable users to create and manage their accounts.

**Involved user stories:**

- DB setup

- Registration (#4)

- Login and logout (#6, #7)

- Account data modification (#8)

- Basic dashboard visualization (#9)

**Dependecies**: none

---

## 3rd Sprint - Resident functionalities

> #### Sprint goal
> Enable residents to publish and manage their parking spots.

**Involved user stories:**

- Insertion of a parking spot (#32)

- Assignment of labels to parking spots (#33)

- Possibility to add availability time slots to parking spot (#34)

- Resident real time dashboard to visualize status of their parking spots (#11, #32)

**Dependencies:** User sessions must be available and functional (_Sprint 2_).
---

## 4th Sprint - Parking search and drivers' functionalites

> #### Sprint goal
> Enable drivers to effectively find available parking spots using search and filtering features.

**Involved user stories:**

- Search for available parking spots (#15)

- Search filtering by labels (#17)

- Search parking spots according to a distance range (#18)

- View all available parking spots when searching (#20)

- Homepage with functionalities list (#10)

**Dependecies**: User sessions must be available and functional (_Sprint 2_), It must be possible to add parking spots (_Sprint 3_)

---

## 5th Sprint - Booking and payment systems

> #### Sprint goal
> Enable users to book parking spots, complete reservations through integrated payment systems.

**Involved user stories:**

- View parking spots from map (#14)

- Booking parking spots for specific time intervals (#19)  

- Resident's booking request queue to manage the incoming reservations (#35)

- Possibility for resident to reject a reservation request from a driver (#37)

- Drivers' info inside the reservation request (#36)

- Allowing drivers to cancel reservations requests (#25)  

- Implementing payment system (#19, #23) 

- Calendar view for parking spots availability and bookings (#13)

- List driver's reservation requests (#24)

**Dependencies:** User sessions must be available and functional (_Sprint 2_), Parking spots must be searchable and present(_Sprint 3_, _Sprint 4_).

---

## 6th Sprint - Notification system

> #### Sprint goal
> Enable users to receive notifications for parking spot availability and booking.

**Involved user stories:**

- Notification of new booking request (#40) 

- Booking confirmation emails to drivers (#21)  

- Providing resident contact details after a confirmed booking (#21, #28)

- Notification of rejected booking request (#22)

- Notification of cancelled booking request (#41)

- Notification for near parking spot available (#16)

- Confirmation of sign up  (#5)

- Notify users if their account has been disabled/enabled (#12)

- Notification when receiving a review and rating from other users (#31)

**Dependencies:** User sessions must be available and functional (_Sprint 2_), 
Parking spots must be searchable, bookable and residents must be able to accept the reservation's requests ( _Sprint 3_, _Sprint 4_, _Sprint 5_).

---

## 7th Sprint — Reputation and rating system

> #### Sprint goal
> Enable users to rate other users based on their sharing experience, and provide access to a reputation system.

**Involved user stories:**

- Drivers can rate the parking service (#26, #30)

- Residents can rate the drivers (#39)

- Reputation threshold to perform reservations (#38)

- Reputation score available into the user's dashboard (#27)

- Received reviews available to read on user's dashboard (#29, #42)

- Notification for received review and rating (#39)

- Finalization of users dashboard view with statistics (#11)

**Dependencies:** User sessions must be available and functional (_Sprint 2_), Parking spots must be bookable and residents must be able to accept the reservation's requests (_Sprint 3, Sprint 4_), Notification service must be available (_Sprint 5_).

---

## 8th Sprint - Admin functionalities

> #### Sprint goal
> Enable admins to enable and disable users' accounts and monitoring system

**Involved user stories:**

- Creating an admin dashboard for microservices (#1)

- Creating an admin dashboard to view and search registered users (#2)

- Allowing admins to view users' information (#3)

- Allowing admins to enable and disable users (#3)



**Dependencies:** User sessions must be available and functional (_Sprint 2_), Notifications, reviews and reputation have to be available (_Sprint 6_, _Sprint 7_).

---
