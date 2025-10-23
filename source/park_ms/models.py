from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()

# -------------------------------
#               USERS
# -------------------------------
class User(db.Model):
    __tablename__ = 'Users'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(50), nullable=False)
    surname = db.Column(db.String(50), nullable=False)

    parking_spots = db.relationship(
        "ParkingSpot",
        back_populates="owner",
        cascade="all, delete-orphan"
    )

    reservations = db.relationship(
        "Reservation",
        back_populates="user",
        cascade="all, delete-orphan"
    )

    def __repr__(self):
        return f"<User {self.id}: {self.name} {self.surname}>"


# -------------------------------
#              LABELS
# -------------------------------
class Label(db.Model):
    __tablename__ = 'Labels'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name = db.Column(db.String(50), nullable=False)
    label_description = db.Column(db.Text, nullable=False)

    parking_spots = db.relationship(
        "ParkingSpotLabel",
        back_populates="label",
        cascade="all, delete-orphan"
    )

    def __repr__(self):
        return f"<Label {self.id}: {self.name}>"


# -------------------------------
#          PARKING SPOTS
# -------------------------------
class ParkingSpot(db.Model):
    __tablename__ = 'Parking_Spots'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name = db.Column(db.String(50), nullable=False)
    spot_location = db.Column(db.String(255), nullable=False)  # MySQL POINT type handled as string
    rep_treshold = db.Column(db.Integer, nullable=False, default=0)
    slot_price = db.Column(db.Float, nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('Users.id'), nullable=False)

    owner = db.relationship("User", back_populates="parking_spots")
    availability_slots = db.relationship(
        "AvailabilitySlot",
        back_populates="parking_spot",
        cascade="all, delete-orphan"
    )
    labels = db.relationship(
        "ParkingSpotLabel",
        back_populates="parking_spot",
        cascade="all, delete-orphan"
    )

    def __repr__(self):
        return f"<ParkingSpot {self.id}: {self.name}, owner={self.user_id}>"


# -------------------------------
#        AVAILABILITY SLOTS
# -------------------------------
class AvailabilitySlot(db.Model):
    __tablename__ = 'Availability_Slots'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    slot_date = db.Column(db.Date, nullable=False)
    start_time = db.Column(db.Time, nullable=False)
    end_time = db.Column(db.Time, nullable=False)
    parking_spot_id = db.Column(db.Integer, db.ForeignKey('Parking_Spots.id'), nullable=False)

    parking_spot = db.relationship("ParkingSpot", back_populates="availability_slots")
    reservations = db.relationship(
        "Reservation",
        back_populates="slot",
        cascade="all, delete-orphan"
    )

    __table_args__ = (
        db.UniqueConstraint('slot_date', 'start_time', 'parking_spot_id', name='unique_slot'),
        db.CheckConstraint('end_time > start_time', name='check_time_valid'),
    )

    def __repr__(self):
        return f"<AvailabilitySlot {self.id}: {self.slot_date} {self.start_time}-{self.end_time}>"


# -------------------------------
#       PARKING SPOT LABELS
# -------------------------------
class ParkingSpotLabel(db.Model):
    __tablename__ = 'Parking_Spot_Labels'

    parking_spot_id = db.Column(db.Integer, db.ForeignKey('Parking_Spots.id'), primary_key=True)
    label_id = db.Column(db.Integer, db.ForeignKey('Labels.id'), primary_key=True)

    parking_spot = db.relationship("ParkingSpot", back_populates="labels")
    label = db.relationship("Label", back_populates="parking_spots")

    def __repr__(self):
        return f"<ParkingSpotLabel spot={self.parking_spot_id}, label={self.label_id}>"


# -------------------------------
#          RESERVATIONS
# -------------------------------
class Reservation(db.Model):
    __tablename__ = 'Reservations'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    reservation_ts = db.Column(db.TIMESTAMP, nullable=False)
    reservation_status = db.Column(
        db.Enum('pending', 'confirmed', 'cancelled', 'completed'),
        default='pending'
    )
    car_plate = db.Column(db.String(7), nullable=False)
    slot_id = db.Column(db.Integer, db.ForeignKey('Availability_Slots.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('Users.id'), nullable=False)

    slot = db.relationship("AvailabilitySlot", back_populates="reservations")
    user = db.relationship("User", back_populates="reservations")

    def __repr__(self):
        return f"<Reservation {self.id}: user={self.user_id}, slot={self.slot_id}, status={self.reservation_status}>"
