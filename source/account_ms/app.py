from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db

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

@app.route("/auth/signup", methods=["POST"])
def signup():
    # TODO: implement user registration
    return jsonify({"message": "signup handler"}), 200


@app.route("/auth/login", methods=["POST"])
def login():
    # TODO: implement login logic
    return jsonify({"message": "login handler"}), 200


@app.route("/auth/logout", methods=["POST"])
def logout():
    # TODO: implement logout logic
    return jsonify({"message": "logout handler"}), 200


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
