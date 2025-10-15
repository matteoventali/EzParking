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

    hashed_password = generate_password_hash(password)

    try:
        with db.session.begin():
            if User.query.filter_by(email=email).with_for_update(read=True).first():
                return jsonify({'desc': 'Email already registered',
                                'code': '1'}), 409

            if User.query.filter_by(phone=phone).with_for_update(read=True).first():
                return jsonify({'desc': 'Phone already registered',
                                'code': '2'}), 409

            new_user = User(
                name=name,
                surname=surname,
                email=email,
                password_hash=hashed_password,
                phone=phone,
                user_role='user'
            )

            db.session.add(new_user)

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

    except Exception as e:
        db.session.rollback()
        return jsonify({'desc': 'Database error', 'code': '99', 'details': str(e)}), 500


@app.route('/auth/login', methods=['POST'])
def login():
    data = request.get_json()

    if not data or 'email' not in data or 'password' not in data:
        return jsonify({'desc': 'Missing email or password',
                        'code': '1'}), 400

    email = data['email']
    password = data['password']

    try:
        with db.session.begin():
            user = User.query.filter_by(email=email).with_for_update().first()

            if not user or not check_password_hash(user.password_hash, password):
                return jsonify({'desc': 'Invalid email or password',
                                'code': '2'}), 401

            if user.session_token:
                return jsonify({'desc': 'User already logged',
                                'code': '3'}), 401

            user.lastlogin_ts = db.func.current_timestamp()
            session_token = ''.join(random.choices(string.ascii_letters + string.digits, k=32))
            user.session_token = session_token

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
                'session_token': user.session_token
            }
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({'desc': 'Database error', 'code': '99', 'details': str(e)}), 500


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
    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header',
                        'code': '1'}), 400

    session_token = auth_header
    user = User.query.filter_by(session_token=session_token).first()

    if not user:
        return jsonify({'desc': 'Invalid session token',
                        'code': '2'}), 401

    score = None

    return jsonify({
        'desc': 'User data retrieved successfully',
        'code': '0',
        'user': {
            'name': user.name,
            'surname': user.surname,
            'email': user.email,
            'phone': user.phone,
            'score': score
        }
    }), 200


@app.route("/pdata", methods=["PUT"])
def update_personal_data():
    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header',
                        'code': '1'}), 400

    session_token = auth_header
    user = User.query.filter_by(session_token=session_token).first()

    if not user:
        return jsonify({'desc': 'Invalid session token',
                        'code': '2'}), 401

    data = request.get_json()
    if not data:
        return jsonify({'desc': 'Missing request body',
                        'code': '3'}), 400

    updated = False
    updated_pwd = False

    if 'name' in data:
        user.name = data['name']
        updated = True

    if 'surname' in data:
        user.surname = data['surname']
        updated = True

    if 'password' in data and (data['password'] != None):
        user.password = generate_password_hash(data['password'])
        updated_pwd = True

    if 'phone' in data:
        existing_user = User.query.filter_by(phone=data['phone']).first()
        if existing_user and existing_user.id != user.id:
            return jsonify({'desc': 'Phone already registered',
                            'code': '4'}), 409
        user.phone = data['phone']
        updated = True

    if not (updated or updated_pwd):
        return jsonify({'desc': 'No valid fields to update',
                        'code': '5'}), 400

    db.session.commit()

    return jsonify({
        'desc': 'User data updated successfully',
        'code': '0',
        'user': {
            'id': user.id,
            'name': user.name,
            'surname': user.surname,
            'email': user.email,
            'phone': user.phone,
            'password': updated_pwd
        }
    }), 200


# -------- REVIEWS --------

@app.route("/reviews", methods=["GET"])
def get_review(res_id):
    # TODO: implement get reviews that hit me
    return jsonify({"message": f"get review handler for reservation {res_id}"}), 200


@app.route("/reviews", methods=["POST"])
def add_review(res_id):
    # TODO: implement add review that I write on a reservation
    return jsonify({"message": f"add review handler for reservation {res_id}"}), 200


# -------- ADMIN --------
@app.route("/users", methods=["GET"])
def get_users_list():
    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header',
                        'code': '1'}), 400

    session_token = auth_header
    admin_user = User.query.filter_by(session_token=session_token).first()

    if not admin_user:
        return jsonify({'desc': 'Invalid session token',
                        'code': '2'}), 401

    if admin_user.user_role != 'admin':
        return jsonify({'desc': 'Access denied: admin only',
                        'code': '3'}), 403

    users = User.query.all()

    users_list = [{
        'id': user.id,
        'name': user.name,
        'surname': user.surname,
        'email': user.email,
        'role': user.user_role
    } for user in users]

    return jsonify({
        'desc': 'Users list retrieved successfully',
        'code': '0',
        'users': users_list
    }), 200

# TODO
@app.route("/users/<int:user_id>", methods=["GET"])
def get_user_dashboard(user_id):
    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header',
                        'code': '1'}), 400

    session_token = auth_header
    admin_user = User.query.filter_by(session_token=session_token).first()

    if not admin_user:
        return jsonify({'desc': 'Invalid session token',
                        'code': '2'}), 401

    if admin_user.user_role != 'admin':
        return jsonify({'desc': 'Access denied: admin only',
                        'code': '3'}), 403

    target_user = User.query.filter_by(id=user_id).first()

    if not target_user:
        return jsonify({'desc': 'User not found',
                        'code': '4'}), 404

    score = None
    # TODO: add reviews list
    return jsonify({
        'desc': 'User dashboard retrieved successfully',
        'code': '0',
        'user': {
            'id': target_user.id,
            'name': target_user.name,
            'surname': target_user.surname,
            'email': target_user.email,
            'phone': target_user.phone,
            'role': target_user.user_role,
            'score': score
        }
    }), 200


# -------------------------------
# MAIN
# -------------------------------

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
