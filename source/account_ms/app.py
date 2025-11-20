from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User, Review
from werkzeug.security import generate_password_hash, check_password_hash
import string
import random
from datetime import date
import statistics



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



# ------------ AUTH ------------
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
            
            if not user.account_status:
                return jsonify({ 'desc': 'Account disabled',
                                 'code': '3'}), 403

            user.lastlogin_ts = db.func.current_timestamp()
            session_token = ''.join(random.choices(string.ascii_letters + string.digits, k=32))
            user.session_token = session_token

            reviews = list_reviews(user)
            received_reviews = reviews["received_reviews"]
            if ( len(received_reviews) == 0 ):
                score = 0
            else:
                score = statistics.mean([r["star"] for r in received_reviews])

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
                'session_token': user.session_token,
                'account_status' : user.account_status,
                'score': score
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
    
    if not user.account_status:
        return jsonify({ 'desc': 'Account disabled',
                            'code': '3'}), 403

    return jsonify({'desc': 'Online',
                    'code': '0'}), 200



# ------------ PERSONAL DATA ------------
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

    reviews = list_reviews(user)
    received_reviews = reviews["received_reviews"]
    if ( len(received_reviews) == 0 ):
        score = 0
    else:
        score = statistics.mean([r["star"] for r in received_reviews])

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

    print(data['password']);

    if 'password' in data and (data['password'] != None):
        user.password_hash = generate_password_hash(data['password'])
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



# ------------ REVIEWS ------------
def list_reviews(user):
        
    written = Review.query.filter_by(writer_id=user.id).all()
    received = Review.query.filter_by(target_id=user.id).all()

    written_reviews = [{
        'id': r.id,
        'review_date': str(r.review_date),
        'star': r.star,
        'review_description': r.review_description,
        'reservation_id': r.reservation_id,
        'other_side_id': r.target.id, 
        'other_side_name': r.target.name,
        'other_side_surname': r.target.surname

    } for r in written]

    received_reviews = [{
        'id': r.id,
        'review_date': str(r.review_date),
        'star': r.star,
        'review_description': r.review_description,
        'other_side_id': r.writer.id, 
        'other_side_name': r.writer.name,
        'other_side_surname': r.writer.surname,
        'reservation_id': r.reservation_id
    } for r in received]

    return {
            'desc': 'Reviews retrieved successfully',
            'code': '0',
            'written_reviews': written_reviews,
            'received_reviews': received_reviews
            }

@app.route("/reviews", methods=["GET"])
def get_review():

    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header',
                        'code': '1'}), 400

    session_token = auth_header
    user = User.query.filter_by(session_token=session_token).first()

    if not user:
        return jsonify({'desc': 'Invalid session token',
                        'code': '2'}), 401

    try:
        return jsonify(list_reviews(user)), 200
    except Exception as e:
        return jsonify({'desc': f'Database error: {e}',
                        'code': '99'}), 500


@app.route("/reviews", methods=["POST"])
def add_review():

    try:
        with db.session.begin():

            auth_header = request.headers.get('Authorization')
            if not auth_header:
                return jsonify({'desc': 'Missing or invalid Authorization header',
                                'code': '1'}), 400

            session_token = auth_header
            writer = User.query.filter_by(session_token=session_token).first()
            if not writer:
                return jsonify({'desc': 'Invalid session token',
                                'code': '2'}), 401

            data = request.get_json()
            required_fields = ["target_id", "reservation_id", "star", "review_description"]
            if not data or not all(f in data for f in required_fields):
                return jsonify({'desc': 'Missing required fields',
                                'code': '3'}), 400

            target_id = data["target_id"]
            reservation_id = data["reservation_id"]
            star = data["star"]
            review_description = data["review_description"]

            if not isinstance(star, int) or star < 1 or star > 5:
                return jsonify({'desc': 'Star must be an integer between 1 and 5',
                                'code': '4'}), 400

            if writer.id == int(target_id):
                return jsonify({'desc': 'User cannot review themselves',
                                'code': '5'}), 400

            target = User.query.get(target_id)
            if not target:
                return jsonify({'desc': 'Target user not found',
                                'code': '6'}), 404

            existing_review = Review.query.filter_by(
                writer_id=writer.id,
                target_id=target_id,
                reservation_id=reservation_id
            ).with_for_update(read=True).first()

            if existing_review:
                return jsonify({'desc': 'Review already exists for this reservation',
                                'code': '7'}), 409

            new_review = Review(
                review_date=date.today(),
                star=star,
                review_description=review_description,
                writer_id=writer.id,
                target_id=target_id,
                reservation_id=reservation_id
            )

            db.session.add(new_review)

        return jsonify({
            'desc': 'Review added successfully',
            'code': '0',
            'review': {
                'id': new_review.id,
                'writer_id': new_review.writer_id,
                'target_id': new_review.target_id,
                'reservation_id': new_review.reservation_id,
                'star': new_review.star,
                'review_description': new_review.review_description,
                'review_date': str(new_review.review_date)
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({'desc': f'Database error : {e}', 
                        'code': '99'}), 500



# ------------ ADMIN ------------+
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
        'role': user.user_role,
        'status' : user.account_status
    } for user in users]

    return jsonify({
        'desc': 'Users list retrieved successfully',
        'code': '0',
        'users': users_list
    }), 200

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

    #if admin_user.user_role != 'admin':
    #    return jsonify({'desc': 'Access denied: admin only',
    #                    'code': '3'}), 403

    target_user = User.query.filter_by(id=user_id).first()

    if not target_user:
        return jsonify({'desc': 'User not found',
                        'code': '4'}), 404

    reviews = list_reviews(target_user)
    received_reviews = reviews["received_reviews"]

    if ( len(received_reviews) == 0 ):
        score = 0
    else:
        score = statistics.mean([r["star"] for r in received_reviews])
    
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
            'score': score,
            'status': target_user.account_status
        },
        'received_reviews': reviews["received_reviews"],
        'written_reviews': reviews["written_reviews"]
    }), 200

@app.route("/users/<int:user_id>/enable", methods=["GET"])
def enable_user_account(user_id):
    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header',
                        'code': '1'}), 400

    session_token = auth_header
    
    try:
        with db.session.begin():
            admin_user = User.query.filter_by(session_token=session_token).first()

            if not admin_user:
                return jsonify({'desc': 'Invalid session token',
                                'code': '2'}), 401

            if admin_user.user_role != 'admin':
                return jsonify({'desc': 'Access denied: admin only',
                                'code': '3'}), 403

            user = User.query.filter_by(id=user_id).with_for_update().first()

            if not user:
                return jsonify({'desc': 'User not found',
                                'code': '4'}), 404

            if user.user_role != 'user':
                return jsonify({'desc': 'Admin account cannot be re-enabled',
                                'code': '5'}), 403

            if user.account_status:
                return jsonify({'desc': 'User account already enabled',
                                'code': '6'}), 200

            user.account_status = True
            user.session_token = None;

        return jsonify({
            'desc': 'User account enabled successfully',
            'code': '0',
            'user': {
                'id': user.id,
                'name': user.name,
                'surname': user.surname,
                'email': user.email,
                'account_status': user.account_status
            }
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({'desc': f'{str(e)}',
                        'code': '99',}), 500

@app.route("/users/<int:user_id>/disable", methods=["GET"])
def disable_user_account(user_id):
    auth_header = request.headers.get('Authorization')

    if not auth_header:
        return jsonify({'desc': 'Missing or invalid Authorization header',
                        'code': '1'}), 400

    session_token = auth_header
    
    try:
        with db.session.begin():
            admin_user = User.query.filter_by(session_token=session_token).first()

            if not admin_user:
                return jsonify({'desc': 'Invalid session token',
                                'code': '2'}), 401

            if admin_user.user_role != 'admin':
                return jsonify({'desc': 'Access denied: admin only',
                                'code': '3'}), 403

            user = User.query.filter_by(id=user_id).with_for_update().first()

            if not user:
                return jsonify({'desc': 'User not found',
                                'code': '4'}), 404

            if user.user_role != 'user':
                return jsonify({'desc': 'Admin account cannot be re-disbaled',
                                'code': '5'}), 403

            if not user.account_status:
                return jsonify({'desc': 'User account already disabled',
                                'code': '6'}), 200

            user.account_status = False
            user.session_token = None;

        return jsonify({
            'desc': 'User account disabled successfully',
            'code': '0',
            'user': {
                'id': user.id,
                'name': user.name,
                'surname': user.surname,
                'email': user.email,
                'account_status': user.account_status
            }
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({'desc': f'{str(e)}',
                        'code': '99',}), 500 

@app.route("/users/active_count", methods=["GET"])
def get_count_user():
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

    active_user_count = User.query.filter(User.session_token.isnot(None)).count()
    return jsonify({
        'desc': 'Active user count retrieved successfully',
        'code': '0',
        'active_user_count': active_user_count
    }), 200


# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
