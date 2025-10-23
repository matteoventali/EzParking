from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User, Reservation, Payment

# -------------------------------
# Init
# -------------------------------
app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = (
    f"mysql+mysqlconnector://{DB_CONFIG['user']}:{DB_CONFIG['password']}@"
    f"{DB_CONFIG['host']}/{DB_CONFIG['database']}"
)
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db.init_app(app)

# -------------------------------
# ROUTES
# -------------------------------
@app.route("/")
def index():
    return jsonify({"message": "Payment Service is active"}), 200


# ------------ PAYMENTS ------------
@app.route("/payments", methods=["POST"])
def create_payment():
    """Register a new payment for a reservation made by a user"""
    pass


@app.route("/payments/user/<int:user_id>", methods=["GET"])
def get_payments_by_user(user_id):
    """Retrieve all payments made by a specific user"""
    pass


@app.route("/payments/reservation/<int:reservation_id>", methods=["GET"])
def get_payment_by_reservation(reservation_id):
    """Retrieve the payment associated with a specific reservation"""
    pass


@app.route("/payments/<int:payment_id>", methods=["PUT"])
def update_payment_status(payment_id):
    """Update the status of an existing payment (e.g., from pending to completed)"""
    pass


# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5003, debug=True)
