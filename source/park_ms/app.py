from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User, Label, ParkingSpot, ParkingSpotLabel, AvailabilitySlot, Reservation
from werkzeug.security import generate_password_hash, check_password_hash
from sqlalchemy import func
from geoalchemy2.shape import to_shape

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
    return jsonify({"message": "Parking Service is active"}), 200


@app.route("/users", methods=["POST"])
def add_user():
    
    data = request.get_json()

    required_fields = ['id', 'name', 'surname']
    if not data or not all(field in data for field in required_fields):
        return jsonify({
            'desc': 'Missing required fields',
            'code': '1'
        }), 400

    user_id = data['id']
    name = data['name']
    surname = data['surname']
    session_token = data['session_token']

    try:
        existing_user = User.query.filter_by(id=user_id).first()
        if existing_user:
            return jsonify({
                'desc': f'User with id {user_id} already exists',
                'code': '2'
            }), 409

        new_user = User(id=user_id, name=name, surname=surname, session_token=session_token)

        db.session.add(new_user)
        db.session.commit()

        return jsonify({
            'desc': 'User created successfully',
            'code': '0',
            'user': {
                'id': new_user.id,
                'name': new_user.name,
                'surname': new_user.surname,
                'session_token': new_user.session_token
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500



# ------------ PARKING SPOTS ------------
@app.route("/parking_spots/<int:spot_id>", methods=["GET"])
def get_parking_spot(spot_id):
    try:
        spot = ParkingSpot.query.get(spot_id)

        if not spot:
            return jsonify({
                "desc": f"Parking spot {spot_id} not found",
                "code": "1"
            }), 404

        labels = [
            {
                "id": rel.label.id,
                "name": rel.label.name,
                "description": rel.label.label_description
            }
            for rel in spot.labels
        ]

        owner = spot.owner
        point = to_shape(spot.spot_location)
        latitude = point.y
        longitude = point.x
        
        spot_data = {
            "id": spot.id,
            "name": spot.name,
            "longitude": latitude,
            "latitude": longitude,
            "rep_treshold": spot.rep_treshold,
            "slot_price": spot.slot_price,
            "user": {
                "id": owner.id,
                "name": owner.name,
                "surname": owner.surname
            },
            "labels": labels
        }

        return jsonify({
            "desc": "Parking spot retrieved successfully",
            "code": "0",
            "parking_spot": spot_data
        }), 200

    except Exception as e:
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500


@app.route("/parking_spots", methods=["POST"])
def create_parking_spot():
    
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    
    session_token = auth_header
    data = request.get_json()
    required_fields = ['name', 'latitude', 'longitude', 'slot_price']
    if not data or not all(field in data for field in required_fields):
        return jsonify({'desc': 'Missing required fields', 'code': '2'}), 400

    name = data['name']
    latitude = data['latitude']
    longitude = data['longitude']
    slot_price = data['slot_price']
    rep_treshold = data.get('rep_treshold', 0)

    if not isinstance(latitude, (int, float)) or not isinstance(longitude, (int, float)):
        return jsonify({'desc': 'Latitude and longitude must be numeric', 'code': '3'}), 400

    if rep_treshold < 0 or rep_treshold > 5:
        return jsonify({'desc': 'rep_treshold must be between 0 and 5', 'code': '4'}), 400

    if slot_price < 0:
        return jsonify({'desc': 'slot_price must be non-negative', 'code': '5'}), 400

    try:
        with db.session.begin():
            user = User.query.filter_by(session_token=session_token).first()
            if not user:
                return jsonify({'desc': 'Invalid session_token in Authorization header', 'code': '6'}), 401

            geom = func.ST_GeomFromText(f'POINT({longitude} {latitude})', 4326)

            new_spot = ParkingSpot(
                name=name,
                spot_location=geom,
                rep_treshold=rep_treshold,
                slot_price=slot_price,
                user_id=user.id
            )

            db.session.add(new_spot)

        return jsonify({
            'desc': 'Parking spot created successfully',
            'code': '0',
            'parking_spot': {
                'id': new_spot.id,
                'name': new_spot.name,
                'latitude': latitude,
                'longitude': longitude,
                'slot_price': new_spot.slot_price,
                'rep_treshold': new_spot.rep_treshold,
                'user_id': user.id
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({'desc': f'Database error: {str(e)}', 'code': '99'}), 500


@app.route("/parking_spots/<int:spot_id>", methods=["PUT"])
def update_parking_spot(spot_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Update parking spot {spot_id}"}), 200


@app.route("/parking_spots/<int:spot_id>", methods=["DELETE"])
def delete_parking_spot(spot_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Delete parking spot {spot_id}"}), 200


@app.route("/parking_spots/<int:spot_id>/labels", methods=["GET"])
def get_parking_spot_labels(spot_id):
    return jsonify({"desc": f"Retrieve labels for parking spot {spot_id}"}), 200


@app.route("/parking_spots/<int:spot_id>/labels/<int:label_id>", methods=["DELETE"])
def delete_parking_spot_label(spot_id, label_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Remove label {label_id} from parking spot {spot_id}"}), 200


# ------------ AVAILABILITY ------------
@app.route("/availability/<int:park_id>", methods=["GET"])
def get_availability_slots(park_id):
    return jsonify({"desc": f"Retrieve availability slots for parking spot {park_id}"}), 200


@app.route("/availability", methods=["POST"])
def create_availability_slot():
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": "Create new availability slot"}), 201


@app.route("/availability/search", methods=["POST"])
def search_availability():
    return jsonify({"desc": "Search parking spots within radius"}), 200


@app.route("/availability", methods=["DELETE"])
def delete_availability_slot():
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": "Delete availability slot"}), 200


# ------------ RESERVATIONS ------------
@app.route("/reservations", methods=["GET"])
def get_reservations():
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": "Retrieve all reservations for authenticated user"}), 200


@app.route("/reservations/<int:reservation_id>", methods=["GET"])
def get_reservation(reservation_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Retrieve details of reservation {reservation_id}"}), 200


@app.route("/reservations", methods=["POST"])
def create_reservation():
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": "Create new reservation"}), 201


@app.route("/reservations/<int:reservation_id>", methods=["PUT"])
def update_reservation(reservation_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Update reservation {reservation_id} status"}), 200


@app.route("/reservations/<int:reservation_id>", methods=["DELETE"])
def delete_reservation(reservation_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Cancel reservation {reservation_id}"}), 200


# ------------ LABELS ------------
@app.route("/labels", methods=["GET"])
def get_labels():
    return jsonify({"desc": "Retrieve all available labels"}), 200


@app.route("/labels", methods=["POST"])
def create_label():
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": "Create new label (admin only)"}), 201


# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5002, debug=True)
