from flask_sqlalchemy import SQLAlchemy
from datetime import date

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
    cc_number = db.Column(db.String(16), nullable=False, unique=True)
    lastlogin_ts = db.Column(db.TIMESTAMP, default=None)
    session_token = db.Column(db.String(32), default=None)
    phone = db.Column(db.String(15), nullable=False, unique=True)
    user_role = db.Column(db.Enum('admin', 'user'), default='user')
    account_status = db.Column(db.Boolean, default=True)

    written_reviews = db.relationship(
        "Review",
        foreign_keys="Review.writer_id",
        back_populates="writer",
        cascade="all, delete-orphan"
    )

    received_reviews = db.relationship(
        "Review",
        foreign_keys="Review.target_id",
        back_populates="target",
        cascade="all, delete-orphan"
    )

    def __repr__(self):
        return f"<User {self.id}: {self.name} {self.surname} ({self.user_role})>"


# -------------------------------
#             REVIEWS
# -------------------------------
class Review(db.Model):
    __tablename__ = 'Review'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    review_date = db.Column(db.Date, nullable=False, default=date.today)
    star = db.Column(db.Integer, nullable=False)
    review_description = db.Column(db.Text, nullable=False)
    writer_id = db.Column(db.Integer, db.ForeignKey('Users.id'), nullable=False)
    target_id = db.Column(db.Integer, db.ForeignKey('Users.id'), nullable=False)
    reservation_id = db.Column(db.Integer, nullable=False)

    __table_args__ = (
        db.UniqueConstraint('writer_id', 'target_id', 'reservation_id', name='unique_review'),
    )

    writer = db.relationship(
        "User",
        foreign_keys=[writer_id],
        back_populates="written_reviews"
    )

    target = db.relationship(
        "User",
        foreign_keys=[target_id],
        back_populates="received_reviews"
    )

    def __repr__(self):
        return f"<Review id={self.id}, writer={self.writer_id}, target={self.target_id}, stars={self.star}>"
