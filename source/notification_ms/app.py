from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User
from datetime import datetime

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
    return jsonify({"message": "Notification Service is active"}), 200


# ------------ USERS ------------
@app.route("/user", methods=["POST"])
def create_user():
    pass


@app.route("/user/<int:user_id>/", methods=["PUT"])
def update_user(user_id):
    pass


@app.route("/user/<int:user_id>/sync", methods=["PUT"])
def sync_user(user_id):
    pass


# ------------ NOTIFICATIONS ------------
@app.route("/notifications/parking_available", methods=["POST"])
def notify_parking_available():
    pass


@app.route("/notifications/reservation_accepted", methods=["POST"])
def notify_reservation_accepted():
    pass


@app.route("/notifications/reservation_rejected", methods=["POST"])
def notify_reservation_rejected():
    pass


@app.route("/notifications/reservation_cancelled", methods=["POST"])
def notify_reservation_cancelled():
    pass


@app.route("/notifications/payment_success", methods=["POST"])
def notify_payment_success():
    pass


@app.route("/notifications/payment_failure", methods=["POST"])
def notify_payment_failure():
    pass


@app.route("/notifications/<int:user_id>/account_disabled", methods=["GET"])
def notify_account_disabled(user_id):
    pass


@app.route("/notifications/<int:user_id>/account_enabled", methods=["GET"])
def notify_account_enabled(user_id):
    pass


# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5001, debug=True)
