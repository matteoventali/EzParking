from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User, Label, ParkingSpot, ParkingSpotLabel, AvailabilitySlot, Reservation
from werkzeug.security import generate_password_hash, check_password_hash


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


# ------------ PARKING SPOTS ------------
@app.route("/parking-spots/<int:spot_id>", methods=["GET"])
def get_parking_spot(spot_id):
    return jsonify({"desc": "Retrieve details of parking spot", "id": spot_id}), 200


@app.route("/parking-spots", methods=["POST"])
def create_parking_spot():
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": "Create new parking spot"}), 201


@app.route("/parking-spots/<int:spot_id>", methods=["PUT"])
def update_parking_spot(spot_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Update parking spot {spot_id}"}), 200


@app.route("/parking-spots/<int:spot_id>", methods=["DELETE"])
def delete_parking_spot(spot_id):
    auth_header = request.headers.get('Authorization')
    if not auth_header:
        return jsonify({'desc': 'Missing Authorization header', 'code': '1'}), 400
    return jsonify({"desc": f"Delete parking spot {spot_id}"}), 200


@app.route("/parking-spots/<int:spot_id>/labels", methods=["GET"])
def get_parking_spot_labels(spot_id):
    return jsonify({"desc": f"Retrieve labels for parking spot {spot_id}"}), 200


@app.route("/parking-spots/<int:spot_id>/labels/<int:label_id>", methods=["DELETE"])
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
