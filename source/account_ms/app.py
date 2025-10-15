from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User
from werkzeug.security import generate_password_hash, check_password_hash
import string
import random


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
    return jsonify({"message": "Service is active"}), 200

# -------- AUTH --------
@app.route('/auth/signup', methods=['POST'])
def signup():
    data = request.get_json()

    required_fields = ['name', 'surname', 'email', 'password', 'phone']
    if not all(field in data for field in required_fields):
        return jsonify({'error': 'Missing required fields'}), 400

    name = data['name']
    surname = data['surname']
    email = data['email']
    phone = data['phone']
    password = data['password']

    if User.query.filter_by(email=email).first():
        return jsonify({'desc': 'Email already registered',
                        'code': '1'}), 409

    if User.query.filter_by(phone=phone).first():
        return jsonify({'desc': 'Phone already registered',
                        'code': '2'}), 409

    hashed_password = generate_password_hash(password)

    new_user = User(
        name=name,
        surname=surname,
        email=email,
        password_hash=hashed_password,
        phone=phone,
        user_role='user'
    )

    db.session.add(new_user)
    db.session.commit()

    return jsonify({
        'desc': 'User registered successfully',
        'code': '0',
        'user': {
            'id': new_user.id,
            'name': new_user.name,
            'surname': new_user.surname,
            'email': new_user.email,
            'phone': new_user.phone,
            'role': new_user.user_role
        }
    }), 201

@app.route('/auth/login', methods=['POST'])
def login():
    data = request.get_json()

    if not data or 'email' not in data or 'password' not in data:
        return jsonify({'desc': 'Missing email or password', 
                        'code': '1'}), 400

    email = data['email']
    password = data['password']

    user = User.query.filter_by(email=email).first()
    if not user or not check_password_hash(user.password_hash, password):
        return jsonify({'desc': 'Invalid email or password', 
                        'code': '2'}), 401
    
    if user.session_token:
        return jsonify({'desc': 'User already logged', 
                        'code': '3'}), 401

    user.lastlogin_ts = db.func.current_timestamp()

    session_token = ''.join(random.choices(string.ascii_letters + string.digits, k=32))
    user.session_token = session_token

    db.session.commit()

    return jsonify({
        'desc': 'Login successful',
        'code': '0',
        'user': {
            'id': user.id,
            'name': user.name,
            'surname': user.surname,
            'email': user.email,
            'phone': user.phone,
            'role': user.user_role,
            'session_token': session_token
        }
    }), 200

@app.route("/auth/logout", methods=["GET"])
def logout():

    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header', 
                        'code': '1'}), 400
    
    session_token = auth_header
    user = User.query.filter_by(session_token=session_token).first()

    if not user:
        return jsonify({'desc': 'Invalid session token', 
                        'code': '2'}), 401

    user.session_token = None
    db.session.commit()

    return jsonify({'desc': 'Logout successful',
                    'code': '0'}), 200

@app.route("/auth/status", methods=["GET"])
def status():

    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header', 
                        'code': '1'}), 400
    
    session_token = auth_header
    user = User.query.filter_by(session_token=session_token).first()

    if not user:
        return jsonify({'desc': 'Invalid session token', 
                        'code': '2'}), 401

    return jsonify({'desc': 'Online',
                    'code': '0'}), 200
    
# -------- PERSONAL DATA --------

@app.route("/pdata", methods=["GET"])
def get_personal_data():
    # TODO: implement get personal data logic
    return jsonify({"message": "get personal data handler"}), 200


@app.route("/pdata", methods=["PUT"])
def update_personal_data():
    # TODO: implement update personal data logic
    return jsonify({"message": "update personal data handler"}), 200


# -------- RESERVATIONS --------

@app.route("/reservations", methods=["GET"])
def get_reservations():
    # TODO: implement get all personal reservations
    return jsonify({"message": "get reservations handler"}), 200


@app.route("/reservations", methods=["POST"])
def add_reservation():
    # TODO: implement add confirmed reservation
    return jsonify({"message": "add reservation handler"}), 200


@app.route("/reservations/<int:res_id>", methods=["GET"])
def get_reservation(res_id):
    # TODO: implement get details of a specific reservation
    return jsonify({"message": f"get reservation handler for {res_id}"}), 200


@app.route("/reservations/<int:res_id>", methods=["DELETE"])
def delete_reservation(res_id):
    # TODO: implement delete specific reservation
    return jsonify({"message": f"delete reservation handler for {res_id}"}), 200


# -------- REVIEWS --------

@app.route("/reservations/<int:res_id>/review", methods=["GET"])
def get_review(res_id):
    # TODO: implement get review of specific reservation
    return jsonify({"message": f"get review handler for reservation {res_id}"}), 200


@app.route("/reservations/<int:res_id>/review", methods=["POST"])
def add_review(res_id):
    # TODO: implement add review to terminated reservation
    return jsonify({"message": f"add review handler for reservation {res_id}"}), 200


@app.route("/reservations/<int:res_id>/review", methods=["DELETE"])
def delete_review(res_id):
    # TODO: implement delete review of specific reservation
    return jsonify({"message": f"delete review handler for reservation {res_id}"}), 200


# -------- ADMIN --------

@app.route("/users/<int:user_id>", methods=["GET"])
def get_user_dashboard(user_id):
    # TODO: implement admin dashboard logic
    return jsonify({"message": f"user dashboard handler for user {user_id}"}), 200


# -------------------------------
# MAIN
# -------------------------------

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
