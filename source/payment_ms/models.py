from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

db = SQLAlchemy()

# ---------------------------------------
#                 USERS
# ---------------------------------------
class User(db.Model):
    __tablename__ = 'Users'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(50), nullable=False)
    surname = db.Column(db.String(50), nullable=False)

    # Payments effettuati dall’utente
    payments_made = db.relationship(
        "Payment",
        foreign_keys="Payment.user_id",
        back_populates="payer"
    )

    # Payments ricevuti dal residente
    payments_received = db.relationship(
        "Payment",
        foreign_keys="Payment.resident_id",
        back_populates="resident"
    )

    def __repr__(self):
        return f"<User {self.id}: {self.name} {self.surname}>"


# ---------------------------------------
#                PAYMENTS
# ---------------------------------------
class Payment(db.Model):
    __tablename__ = 'Payments'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    payment_ts = db.Column(db.DateTime, nullable=False, default=datetime.utcnow)
    amount = db.Column(db.String(50), nullable=False)

    payment_status = db.Column(
        db.Enum('pending', 'completed', 'failed', name='payment_status_enum'),
        nullable=False,
        default='pending'
    )

    method = db.Column(
        db.Enum('applepay', 'paypal', 'googlepay', 'creditcard', name='payment_method_enum'),
        nullable=False
    )

    reservation_id = db.Column(db.Integer, nullable=False)

    user_id = db.Column(
        db.Integer,
        db.ForeignKey('Users.id'),
        nullable=False
    )

    resident_id = db.Column(
        db.Integer,
        db.ForeignKey('Users.id'),
        nullable=False
    )

    # Relazioni distinte per evitare conflitti
    payer = db.relationship(
        "User",
        foreign_keys=[user_id],
        back_populates="payments_made"
    )

    resident = db.relationship(
        "User",
        foreign_keys=[resident_id],
        back_populates="payments_received"
    )

    reservation_date    = db.Column(db.Date, nullable=False)
    reservation_start   = db.Column(db.Time, nullable=False)
    reservation_end     = db.Column(db.Time, nullable=False)

    def __repr__(self):
        return f"<Payment {self.id}: {self.amount} {self.payment_status}>"
