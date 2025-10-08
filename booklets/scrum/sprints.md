# Sprints organizations

The following section describes the sprint-based organization of the EzParking project, developed following the Scrum framework. Each sprint focuses on specific user stories and goals, gradually delivering core functionalities of the system.

---

## 1st Sprint - Account management


> #### Sprint goal 
> Enable users to create and manage their accounts, while providing the admin with basic visibility.

**Involved user stories:**

- Registration (#1)

- Login and logout (#2, #3)

- Account data modification (#4)

- Basic dashboard visualization (#5, #6)

**Dependecies**: none

---

## 2nd Sprint - Parking search

> #### Sprint goal
> Enable drivers to effectively find available parking spots using search and filtering features.

**Involved user stories:**

- Search for available parking spots (#7)

- Search filtering by labels (#8)

- Search nearby parking spots (#9)

- Notify a driver when a near parking spot is available (#18)

**Dependecies**: User sessions must be available and functional (_Sprint 1_)

---

## 3rd Sprint - Booking and notification systems


> #### Sprint goal
> Enable users to book parking spots, complete reservations through integrated payment systems, and receive notifications related to their bookings.

**Involved user stories:**

- Booking parking spots for specific time intervals (#10)  

- Resident's booking request queue to manage the incoming reservations (#24)

- Sending booking confirmation emails to drivers (#11)  

- Implementing the payment system and visual confirmation (#12, #13)  

- Allowing drivers to cancel reservations (#14, #15)  

- Providing resident contact details after a confirmed booking (#16)

**Dependencies:** User sessions must be available and functional (_Sprint 1_), Parking spots must be searchable (_Sprint 2_).

---

## 4th Sprint - Resident functionalities


> #### Sprint goal
> Enable residents to publish and manage their parking spots.

**Involved user stories:**

- Insertion of a parking spot (#19)

- Assignment of labels to parking spots (#20)

- Resident real time dashboard to visualize status of it's parking spots (#21)

- Possibility of elimination of confirmed reservation from resident part (#22)

- Notification of new booking request (#25)

- Possibility of change the availablity status of the parkig spot (#27)

**Dependencies:** User sessions must be available and functional (_Sprint 1_), Parking spots must be bookable (_Sprint 3_).

---

## 5th Sprint — Reputation and rating system

> #### Sprint goal
> Enable users to rate parking spots and drivers, and provide access to a reputation system.

**Involved user stories:**

- Drivers can rate the parking service (#17)

- Residents can rate the drivers (#26)

- Reputation threshold to perform reservations (#23)

- Reputation score visualizable into the user's dashboard (#28)

Dipendenze: storico transazioni (Sprint 3) per calcolare reputazione; base resident attiva (Sprint 4).

**Dependencies:** User sessions must be available and functional (_Sprint 1_), Parking spots must be bookable and residents can accept the reservation's requests (_Sprint 3, Sprint 4_).

---
