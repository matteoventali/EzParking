from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

db = SQLAlchemy()


class User(db.Model):
    __tablename__ = 'Users'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(50), nullable=False)
    surname = db.Column(db.String(50), nullable=False)

    payments = db.relationship('Payment', back_populates='user')

    def __repr__(self):
        return f"<User {self.name} {self.surname}>"



class Reservation(db.Model):
    __tablename__ = 'Reservations'

    id = db.Column(db.Integer, primary_key=True)
    reservation_ts = db.Column(db.DateTime, nullable=False)

    payments = db.relationship('Payment', back_populates='reservation')

    def __repr__(self):
        return f"<Reservation {self.id} at {self.reservation_ts}>"



class Payment(db.Model):
    __tablename__ = 'Payments'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    payment_ts = db.Column(db.DateTime, nullable=False, default=datetime.utcnow)
    amount = db.Column(db.String(50), nullable=False)
    payment_status = db.Column(
        db.Enum('pending', 'completed', 'failed', name='payment_status_enum'),
        default='pending'
    )
    method = db.Column(
        db.Enum('credit_card', 'paypal', 'bank_transfer', name='payment_method_enum'),
        nullable=False
    )
    reservation_id = db.Column(db.Integer, db.ForeignKey('Reservations.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('Users.id'), nullable=False)

    reservation = db.relationship('Reservation', back_populates='payments')
    user = db.relationship('User', back_populates='payments')

    def __repr__(self):
        return f"<Payment {self.id} - {self.amount} {self.payment_status}>"
