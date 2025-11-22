from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User
from datetime import datetime
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart



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

        smtp_server = "smtp.gmail.com"
        smtp_port = 587
        gmail_user = ""
        app_password = ""  

        to_email = ""

        msg = MIMEMultipart()
        msg["From"] = gmail_user
        msg["To"] = to_email
        msg["Subject"] = "Test SMTP Gmail"
        body = "Funziona!"
        msg.attach(MIMEText(body, "plain"))

        with smtplib.SMTP(smtp_server, smtp_port) as server:
            server.starttls()
            server.login(gmail_user, app_password)
            server.sendmail(gmail_user, to_email, msg.as_string())

        return jsonify({
            'ok':"ok"
        }), 200


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
