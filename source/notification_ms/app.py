from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User
from sqlalchemy import func, text
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from threading import Thread
from datetime import datetime, timedelta
from zoneinfo import ZoneInfo

def load_app_password(file: str):
    with open(file, "r", encoding="utf-8") as f:
        return f.read().strip()
    
def fill_template(template: str, variables: dict):
    for key, value in variables.items():
        placeholder = f"%{key}%"
        template = template.replace(placeholder, str(value))
    return template


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
                'code': "1"
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
            'code': "0", 
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


@app.route("/user/<int:user_id>", methods=["PUT"])
def update_user(user_id):
    try:
        data = request.get_json()
        user = User.query.filter_by(id=user_id).first()

        if not user:
            return jsonify({"desc": "User not found", "code": 1}), 404

        if "lat" not in data or "lon" not in data:
            return jsonify({
                "desc": "missing position coordinates",
                "code": 1
            }), 400

        lat = float(data["lat"])
        lon = float(data["lon"])

        user.lastlogin_ts = datetime.now(ZoneInfo("Europe/Rome"))

        position = func.ST_GeomFromText(f'POINT({lon} {lat})')

        user.last_position = position

        db.session.commit()

        return jsonify({
            'desc': "User login info updated",
            'code': 0,
            'info': {
                'lastposition': (lat, lon), 
                'last_login': user.lastlogin_ts
            }
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': 99
        }), 500
# ------------ USERS ------------


# ------------ NOTIFICATIONS ------------

def send_email(to_email, subject, mail_content_path, content_vars, main_template_path):

    try:
        # Load main template
        with open(main_template_path, "r", encoding="utf-8") as f:
            main_template = f.read()

        # Load content template
        with open(mail_content_path, "r", encoding="utf-8") as f:
            content_template = f.read()

        # Fill content template
        filled_content = fill_template(content_template, content_vars)

        # Fill main template
        final_html = fill_template(
            main_template,
            {
                "NOTIFICATION_TYPE": subject.upper(),
                "NOTIFICATION_CONTENT": filled_content
            }
        )

        # Build email
        msg = MIMEMultipart("alternative")
        msg["From"] = SENDER
        msg["To"] = to_email
        msg["Subject"] = subject
        msg.attach(MIMEText(final_html, "html"))

        # Send email
        with smtplib.SMTP("smtp.gmail.com", 587) as server:
            server.starttls()
            server.login(SENDER, APP_PASSWORD)
            server.sendmail(SENDER, to_email, msg.as_string())

    except Exception as e:
        print(f"[EMAIL ERROR] Cannot send mail to {to_email}: {e}")

def send_email_async(to_email, subject, mail_content_path, content_vars, main_template_path):
    worker = Thread(
        target=send_email,
        args=(to_email, subject, mail_content_path, content_vars, main_template_path)
    )
    worker.daemon = True
    worker.start()

def send_nearby_notifications(users, template, content, address):
    smtp_server = "smtp.gmail.com"
    smtp_port = 587

    for user in users:
        try:
            content_vars = {
                "USER_NAME": user.name,
                "ADDRESS": address
            }
            filled_content = fill_template(content, content_vars)

            template_vars = {
                "NOTIFICATION_TYPE": "NEW PARKING AVAILABLE",
                "NOTIFICATION_CONTENT": filled_content
            }
            filled_template = fill_template(template, template_vars)

            msg = MIMEMultipart("alternative")
            msg["From"] = SENDER
            msg["To"] = user.email
            msg["Subject"] = "New parking spot available near you!"
            msg.attach(MIMEText(filled_template, "html"))

            with smtplib.SMTP(smtp_server, smtp_port) as server:
                server.starttls()
                server.login(SENDER, APP_PASSWORD)
                server.sendmail(SENDER, user.email, msg.as_string())

        except Exception as e:
            print(f"[ERROR] Cannot send mail to {user.email}: {e}")
            continue

@app.route("/notifications/nearby_alert", methods=["POST"])
def notify_nearby_users():

    try:
        data = request.get_json()
        
        now = datetime.now(ZoneInfo("Europe/Rome"))
        thirty_minutes_ago = now - timedelta(minutes=1)

        required = ["lat", "lon", "owner_id", "address"]
        if not all(k in data for k in required):
            return jsonify({"desc": "Missing parameters", "code": "1"}), 400

        address = data["address"]
        lat = float(data["lat"])
        lon = float(data["lon"])
        owner_id = int(data["owner_id"])

        content_path = "templates/parking_available_mail.html"

        with open(template_path, "r", encoding="utf-8") as f:
            template = f.read()

        with open(content_path, "r", encoding="utf-8") as f:
            content = f.read()

        nearby_users = (
            db.session.query(User)
            .filter(User.id != owner_id)
            .filter(User.last_position.isnot(None))
            .filter(
                func.ST_Distance_Sphere(
                    User.last_position,
                    func.Point(lon, lat)
                ) <= 10000000000000000000000
            )
            .filter(User.lastlogin_ts >= thirty_minutes_ago)
            .all()
        )

        worker = Thread(
            target=send_nearby_notifications,
            args=(nearby_users, template, content, address)
        )
        worker.daemon = True  
        worker.start()

        return jsonify({
            "desc": "Notifications scheduled",
            "code": "0",
            "users_found": len(nearby_users)
        }), 200
    
    except Exception as e:
        return jsonify({
            'desc': f"Error: {e}", 
            'code': "99"
        })


@app.route("/notifications/reservation_accepted", methods=["POST"])
def notify_reservation_accepted():
    pass


@app.route("/notifications/reservation_rejected", methods=["POST"])
def notify_reservation_rejected():
    pass


@app.route("/notifications/reservation_cancelled", methods=["POST"])
def notify_reservation_cancelled():
    pass


@app.route("/notifications/reservation_request", methods=["POST"])
def notify_reservation_request():
    pass


@app.route("/notifications/registration_successfull", methods=["POST"])
def notify_registration_successfull():
    pass


@app.route("/notifications/received_review", methods=["POST"])
def notify_received_review():
    pass


@app.route("/notifications/account_disabled", methods=["POST"])
def notify_account_disabled():

    data = request.get_json()

    if not data or "user_id" not in data:
        return jsonify({"desc": "Missing user id", "code": "1"}), 400
    
    user = User.query.filter_by(id=data["user_id"]).first()
    if not user:
        return jsonify({"desc": "Invalid user", "code": "2"}), 404

    content_vars = {"USER_NAME": user.name}

    send_email_async(
        to_email=user.email,
        subject="Account Disabled",
        mail_content_path="templates/disable_mail.html",
        content_vars=content_vars,
        main_template_path=template_path 
    )

    return jsonify({
        "desc": "Notification scheduled",
        "code": "0"
    }), 200


@app.route("/notifications/account_enabled", methods=["POST"])
def notify_account_enabled():

    data = request.get_json()

    if not data or "user_id" not in data:
        return jsonify({
            'desc': "Missing user id",
            'code': "1"
        }), 400
    
    user = User.query.filter_by(id=data["user_id"]).first()
    if not user:
        return jsonify({
            'desc': "Invalid user",
            'code': "2"
        }), 404

    content_vars = {"USER_NAME": user.name}

    send_email_async(
        to_email=user.email,
        subject="Account Enabled",
        mail_content_path="templates/enable_mail.html",
        content_vars=content_vars,
        main_template_path=template_path 
    )

    return jsonify({
        'desc': "Notification scheduled",
        'code': "0"
    }), 200
# ------------ NOTIFICATIONS ------------

# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5001, debug=True)
