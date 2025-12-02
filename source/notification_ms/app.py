from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User
from sqlalchemy import func
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

def load_app_password(file):
    with open(file, "r", encoding="utf-8") as f:
        return f.read().strip()

key_file = "password.key" 
template_path = "templates/mail_template.html"
SENDER = "ezparking.notifications@gmail.com"
APP_PASSWORD = load_app_password(key_file)

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
@app.route("/notifications/users", methods=["POST"])
def add_user(): 
    try:
        data = request.get_json()

        if not data or not all(f in data for f in ["id", "name", "surname", "email"]):
            return jsonify({
                'desc': "Missing required fields", 
                'code': 1
            }), 404

        id = data["id"]
        name = data["name"] 
        surname = data["surname"]
        email = data["email"]

        new_user = User(
            email = email, 
            id = id,
            name = name, 
            surname = surname
        )

        db.session.add(new_user)
        db.session.commit()

        return jsonify({
            'desc': "New user successfully inserted", 
            'code': 0, 
            'user': {
                'name': new_user.name,
                'surname': new_user.surname,
                'id': new_user.id,
                'email': new_user.email 
            }
        }), 201

    except Exception as e: 
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/user/<int:user_id>/", methods=["PUT"])
def update_user(user_id):
    try:
        data = request.get_json()
        user = User.query.filter_by(id = user_id)

        user.lastlogin_ts = db.func.current_timestamp()

        if data["lat"]: 
            lat = data["lat"]

        if data["lon"]:
            lon = data["lon"]

        if not data["lon"] or not data["lat"]: 
            return jsonify({
                'desc': "missing position coordinates", 
                'code': 1
            }), 400
        
        position = func.ST_GeomFromText(f'POINT({lon} {lat})', 4326)

        user.lastposition = position

        db.session.commit()

        return jsonify({
            'desc': "User login info updated", 
            'code': 0, 
            'info': {
                'lastlogin_ts': user.lastlogin_ts, 
                'lastposition': user.lastposition
            }
        }), 200

    except Exception as e: 
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500
# ------------ USERS ------------


# ------------ NOTIFICATIONS ------------
@app.route("/notifications/parking_available", methods=["POST"])
def notify_parking_available():
    
    data = request.get_json()

    if not data or "email" not in data:
        return jsonify({
            'desc': "Missing email",
            'code': 0
        }), 400
    
    to_email = data["email"]
    with open(template_path, "r", encoding="utf-8") as f:
        html_content = f.read()

    smtp_server = "smtp.gmail.com"
    smtp_port = 587

    msg = MIMEMultipart("alternative")
    msg["From"] = SENDER
    msg["To"] = to_email
    msg["Subject"] = "Parking Spot available close to you!!"
    msg.attach(MIMEText(html_content, "html"))

    with smtplib.SMTP(smtp_server, smtp_port) as server:
        server.starttls()
        server.login(SENDER, APP_PASSWORD)
        result = server.sendmail(SENDER, to_email, msg.as_string())
        if result == {}:
            result = None

    return jsonify({
        'desc': "Mail sent successfully", 
        'code': 0, 
        'null_check': result 
    }), 250


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
    
    user = User.query.filter_by(id = user_id).first()
    if not user: 
        return jsonify({
            'desc': "Invalid user", 
            'code': "1"
        }), 404

    to_email = user.email
    
    with open(template_path, "r", encoding="utf-8") as f:
        html_content = f.read()

    smtp_server = "smtp.gmail.com"
    smtp_port = 587

    msg = MIMEMultipart("alternative")
    msg["From"] = SENDER
    msg["To"] = to_email
    msg["Subject"] = "Your account has been disabled!"
    msg.attach(MIMEText(html_content, "html"))

    with smtplib.SMTP(smtp_server, smtp_port) as server:
        server.starttls()
        server.login(SENDER, APP_PASSWORD)
        result = server.sendmail(SENDER, to_email, msg.as_string())
        if result == {}:
            result = None

    return jsonify({
        'desc': "Mail sent successfully", 
        'code': "0", 
        'null_check': result 
    }), 250


@app.route("/notifications/<int:user_id>/account_enabled", methods=["GET"])
def notify_account_enabled(user_id):
    
    user = User.query.filter_by(id = user_id).first()
    if not user: 
        return jsonify({
            'desc': "Invalid user", 
            'code': "1"
        }), 404

    to_email = user.email
    
    with open(template_path, "r", encoding="utf-8") as f:
        html_content = f.read()

    smtp_server = "smtp.gmail.com"
    smtp_port = 587

    msg = MIMEMultipart("alternative")
    msg["From"] = SENDER
    msg["To"] = to_email
    msg["Subject"] = "Your account has been enabled!"
    msg.attach(MIMEText(html_content, "html"))

    with smtplib.SMTP(smtp_server, smtp_port) as server:
        server.starttls()
        server.login(SENDER, APP_PASSWORD)
        result = server.sendmail(SENDER, to_email, msg.as_string())
        if result == {}:
            result = None

    return jsonify({
        'desc': "Mail sent successfully", 
        'code': "0", 
        'null_check': result 
    }), 250
# ------------ NOTIFICATIONS ------------

# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5001, debug=True)
