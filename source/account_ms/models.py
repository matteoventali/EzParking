from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

db = SQLAlchemy()

# -------------------------------
#               USERS
# -------------------------------
class User(db.Model):
    __tablename__ = 'Users'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name = db.Column(db.String(50), nullable=False)
    surname = db.Column(db.String(50), nullable=False)
    password_hash = db.Column(db.String(255), nullable=False)
    email = db.Column(db.String(100), nullable=False, unique=True)
    lastlogin_ts = db.Column(db.TIMESTAMP, default=None)
    session_token = db.Column(db.String(32), default=None)
    phone = db.Column(db.String(15), nullable=False, unique=True)
    user_role = db.Column(db.Enum('admin', 'user'), default='user')

    reviews = db.relationship("Review", back_populates="user", cascade="all, delete-orphan")

    def __repr__(self):
        return f"<User {self.id}: {self.name} {self.surname}>"


# -------------------------------
#           RESERVATIONS
# -------------------------------
class Reservation(db.Model):
    __tablename__ = 'Reservation'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    resident_name = db.Column(db.String(50), nullable=False)
    resident_surname = db.Column(db.String(50), nullable=False)
    reservation_ts = db.Column(db.TIMESTAMP, nullable=False)

    reviews = db.relationship("Review", back_populates="reservation", cascade="all, delete-orphan")

    def __repr__(self):
        return f"<Reservation {self.id} - {self.resident_name} {self.resident_surname}>"


# -------------------------------
#            REVIEWS
# -------------------------------
class Review(db.Model):
    __tablename__ = 'Review'

    from datetime import datetime, timezone
    review_date = db.Column(db.Date, nullable=False)
    star = db.Column(db.Integer, nullable=False)
    review_description = db.Column(db.Text, nullable=False)

    user_id = db.Column(db.Integer, db.ForeignKey('Users.id'), primary_key=True)
    reservation_id = db.Column(db.Integer, db.ForeignKey('Reservation.id'), primary_key=True)

    user = db.relationship("User", back_populates="reviews")
    reservation = db.relationship("Reservation", back_populates="reviews")

    def __repr__(self):
        return f"<Review user={self.user_id}, reservation={self.reservation_id}, stars={self.star}>"
