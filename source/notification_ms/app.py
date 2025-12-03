from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User
from sqlalchemy import func, text
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

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

        user.lastlogin_ts = db.func.current_timestamp()

        position = func.ST_GeomFromText(f'POINT({lon} {lat})')

        user.last_position = position

        db.session.commit()

        return jsonify({
            'desc': "User login info updated",
            'code': 0,
            'info': {
                'lastposition': (lat, lon)
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
@app.route("/notifications/nearby_alert", methods=["POST"])
def notify_nearby_users():
    data = request.get_json()

    required = ["lat", "lon", "owner_id", "spot_name"]
    if not all(k in data for k in required):
        return jsonify({"desc": "Missing parameters", "code": "1"}), 400

    spot_name = data["spot_name"]
    lat = float(data["lat"])
    lon = float(data["lon"])
    owner_id = data["owner_id"]

    content_path = "templates/parking_available_mail.html"

    with open(template_path, "r", encoding="utf-8") as f:
        template = f.read()

    with open(content_path, "r", encoding="utf-8") as f:
        content = f.read()

    radius_meters = 1000

    nearby_users = (
        db.session.query(User)
        .filter(User.id != owner_id)
        .filter(User.last_position.isnot(None))
        .filter(
            func.ST_Distance_Sphere(
                User.last_position,
                func.Point(lon, lat)
            ) <= radius_meters
        )
        .all()
    )


    notified_count = 0

    smtp_server = "smtp.gmail.com"
    smtp_port = 587

    for user in nearby_users:

        content_vars = {
            "USER_NAME": user.name,
            "SPOT_NAME": spot_name 
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
        msg["Subject"] = "Alert: Something happened near you!"
        msg.attach(MIMEText(filled_template, "html"))

        try:
            with smtplib.SMTP(smtp_server, smtp_port) as server:
                server.starttls()
                server.login(SENDER, APP_PASSWORD)
                server.sendmail(SENDER, user.email, msg.as_string())
                notified_count += 1
        except:
            continue 

    return jsonify({
        "desc": "Notifications sent",
        "code": "0",
        "notified_users": notified_count
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
        return jsonify({
            'desc':"Missing user id", 
            'code': "1"
        })
    
    user_id = data["user_id"]
    mail_path = "templates/disable_mail.html"
    
    user = User.query.filter_by(id = user_id).first()
    if not user: 
        return jsonify({
            'desc': "Invalid user", 
            'code': "2"
        }), 404
    
    with open(template_path, "r", encoding="utf-8") as f:
        template = f.read()

    with open(mail_path, "r", encoding="utf-8") as f:
        content = f.read()

    content_vars = {"USER_NAME": user.name}

    filled_content = fill_template(content, content_vars)

    template_vars = {
        "NOTIFICATION_TYPE": "ACCOUNT ENABLED",
        "NOTIFICATION_CONTENT": filled_content
        }
    
    filled_template = fill_template(template, template_vars)
    
    to_email = user.email

    smtp_server = "smtp.gmail.com"
    smtp_port = 587

    msg = MIMEMultipart("alternative")
    msg["From"] = SENDER
    msg["To"] = to_email
    msg["Subject"] = "Your account has been disabled!"
    msg.attach(MIMEText(filled_template, "html"))

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
    }), 200

@app.route("/notifications/account_enabled", methods=["POST"])
def notify_account_enabled():

    data = request.get_json()

    if not data or "user_id" not in data:
        return jsonify({
            'desc':"Missing user id", 
            'code': "1"
        })
    
    user_id = data["user_id"]
    mail_path = "templates/enable_mail.html"
    
    user = User.query.filter_by(id = user_id).first()
    if not user: 
        return jsonify({
            'desc': "Invalid user", 
            'code': "2"
        }), 404
    
    with open(template_path, "r", encoding="utf-8") as f:
        template = f.read()

    with open(mail_path, "r", encoding="utf-8") as f:
        content = f.read()

    content_vars = {"USER_NAME": user.name}

    filled_content = fill_template(content, content_vars)

    template_vars = {
        "NOTIFICATION_TYPE": "ACCOUNT ENABLED",
        "NOTIFICATION_CONTENT": filled_content
        }
    
    filled_template = fill_template(template, template_vars)
    
    to_email = user.email

    smtp_server = "smtp.gmail.com"
    smtp_port = 587

    msg = MIMEMultipart("alternative")
    msg["From"] = SENDER
    msg["To"] = to_email
    msg["Subject"] = "Your account has been enabled!"
    msg.attach(MIMEText(filled_template, "html"))

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
    }), 200
# ------------ NOTIFICATIONS ------------

# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5001, debug=True)
