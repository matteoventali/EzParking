from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User, Label, ParkingSpot, ParkingSpotLabel, AvailabilitySlot, Reservation
from sqlalchemy import func, and_, or_
from geoalchemy2.shape import to_shape
from datetime import datetime, date
from geoalchemy2.elements import WKTElement


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



# ------------ USERS ------------
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

    try:
        existing_user = User.query.filter_by(id=user_id).first()
        if existing_user:
            return jsonify({
                'desc': f'User with id {user_id} already exists',
                'code': '2'
            }), 409

        new_user = User(id=user_id, name=name, surname=surname)

        db.session.add(new_user)
        db.session.commit()

        return jsonify({
            'desc': 'User created successfully',
            'code': '0',
            'user': {
                'id': new_user.id,
                'name': new_user.name,
                'surname': new_user.surname
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500
# ------------ USERS ------------



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

        time_slots = [
            {
                "id": slot.id,
                "slot_date": slot.slot_date.isoformat(),
                "start_time": slot.start_time.strftime("%H:%M"),
                "end_time": slot.end_time.strftime("%H:%M")
            }
            for slot in spot.availability_slots
        ]

        owner = spot.owner
        point = to_shape(spot.spot_location)
        latitude = point.y
        longitude = point.x
        
        spot_data = {
            "id": spot.id,
            "name": spot.name,
            "longitude": longitude,
            "latitude": latitude,
            "rep_treshold": spot.rep_treshold,
            "slot_price": spot.slot_price,
            "user": {
                "id": owner.id,
                "name": owner.name,
                "surname": owner.surname
            },
            "labels": labels,
            "time_slots": time_slots
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


@app.route("/parking_spots/users/<int:user_id>", methods=["GET"])
def get_user_spots(user_id):

    try:
        user = User.query.filter_by(id=user_id).first()
        if not user:
            return jsonify({'desc': 'The user doesn\'t exists', 'code': 1}), 404
        spots = ParkingSpot.query.filter_by(user_id=user_id).all()
        if not spots:
            return jsonify({'desc': 'The user has not parking spots', 
                            'code': 2, 
                            'parking_spots': []}), 404
        
        now = datetime.now()
        today = date.today()
        current_time = now.time()

        results = []

        for spot in spots: 
            point = to_shape(spot.spot_location)
            lat = point.y
            lon = point.x

            future_slots = AvailabilitySlot.query.filter(
                AvailabilitySlot.parking_spot_id == spot.id,
                or_(
                    AvailabilitySlot.slot_date > today,
                    and_(
                        AvailabilitySlot.slot_date == today,
                        AvailabilitySlot.end_time > current_time
                    )
                )
            ).all()

            if not future_slots:
                available_flag = None
            else:
                slot_ids = [s.id for s in future_slots]

                active_reservations = db.session.query(Reservation.slot_id).filter(
                    Reservation.slot_id.in_(slot_ids),
                    Reservation.reservation_status.in_(["pending", "confirmed"])
                ).all()

                active_slot_ids = {r.slot_id for r in active_reservations}

                if any(s.id not in active_slot_ids for s in future_slots):
                    available_flag = True
                else:
                    available_flag = False

            results.append({
                'spot_id': spot.id, 
                'spot_name': spot.name, 
                'latitude': lat, 
                'longitude': lon,
                'rep_treshold': spot.rep_treshold, 
                'slot_price': spot.slot_price,
                'available': available_flag
            })

        
        return jsonify({
            'desc':'Parking spots retrieved successfully', 
            'code': 0,
            'parking_spots': results 
        })

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/parking_spots", methods=["POST"])
def create_parking_spot():
    
    data = request.get_json()
    required_fields = ['name', 'latitude', 'longitude', 'slot_price', 'user_id']
    if not data or not all(field in data for field in required_fields):
        return jsonify({'desc': 'Missing required fields', 'code': '2'}), 400

    name = data['name']
    latitude = data['latitude']
    longitude = data['longitude']
    slot_price = data['slot_price']
    rep_treshold = data.get('rep_treshold', 0)
    user_id = data['user_id']
    labels = data.get('labels', [])

    if not isinstance(latitude, (int, float)) or not isinstance(longitude, (int, float)):
        return jsonify({'desc': 'Latitude and longitude must be numeric', 'code': '3'}), 400

    if rep_treshold < 0 or rep_treshold > 5:
        return jsonify({'desc': 'rep_treshold must be between 0 and 5', 'code': '4'}), 400

    if slot_price < 0:
        return jsonify({'desc': 'slot_price must be non-negative', 'code': '5'}), 400

    try:
        with db.session.begin():
            user = User.query.filter_by(id=user_id).first()
            if not user:
                return jsonify({'desc': 'Invalid user\'s id', 'code': '6'}), 401

            geom = func.ST_GeomFromText(f'POINT({longitude} {latitude})', 4326)

            new_spot = ParkingSpot(
                name=name,
                spot_location=geom,
                rep_treshold=rep_treshold,
                slot_price=slot_price,
                user_id=user.id
            )

            db.session.add(new_spot)
            db.session.flush()

            if labels:

                parking_spot_labels = [
                    ParkingSpotLabel(
                        parking_spot_id = new_spot.id,
                        label_id = l
                    ) for l in labels
                ]

                for spot_label in parking_spot_labels:
                    db.session.add(spot_label)

        
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
                'user_id': user.id,
                'labels': labels
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({'desc': f'Database error: {str(e)}', 'code': '99'}), 500


@app.route("/parking_spots/<int:spot_id>", methods=["PUT"])
def update_parking_spot(spot_id):
 
    data = request.get_json()
    if not data:
        return jsonify({'desc': 'Missing request body', 'code': '1'}), 400

    if 'name' not in data:
        return jsonify({'desc': 'Missing field: name', 'code': '2'}), 400

    new_name = data['name']
    if not isinstance(new_name, str) or len(new_name.strip()) == 0:
        return jsonify({'desc': 'Invalid name value', 'code': '3'}), 400

    try:
        with db.session.begin():
            spot = ParkingSpot.query.filter_by(id=spot_id).with_for_update().first()
            if not spot:
                return jsonify({'desc': f'Parking spot {spot_id} not found', 'code': '4'}), 404

            spot.name = new_name.strip()

        return jsonify({
            "desc": "Parking spot name updated successfully",
            "code": "0",
            "parking_spot": {
                "id": spot.id,
                "name": spot.name,
                "rep_treshold": spot.rep_treshold,
                "slot_price": spot.slot_price
            }
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/parking_spots/<int:spot_id>", methods=["DELETE"])
def delete_parking_spot(spot_id):

    try:
        with db.session.begin():
            spot = ParkingSpot.query.filter_by(id=spot_id).with_for_update().first()
            if not spot:
                return jsonify({'desc': f'Parking spot {spot_id} not found', 'code': '1'}), 404

            db.session.delete(spot)

        return jsonify({
            'desc': f'Parking spot {spot_id} deleted successfully',
            'code': '0'
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/parking_spots/<int:spot_id>/labels", methods=["GET"])
def get_parking_spot_labels(spot_id):

    try:
        spot = ParkingSpot.query.get(spot_id)
        if not spot:
            return jsonify({
                "desc": f"Parking spot {spot_id} not found",
                "code": "1"
            }), 404

        results = (
            db.session.query(
                ParkingSpotLabel.label_id,
                Label.name,
                Label.label_description
            )
            .join(Label, ParkingSpotLabel.label_id == Label.id)
            .filter(ParkingSpotLabel.parking_spot_id == spot_id)
            .all()
        )

        labels = [
            {
                "id": row.label_id,
                "name": row.name,
                "description": row.label_description
            }
            for row in results
        ]

        return jsonify({
            "desc": "Labels retrieved successfully",
            "code": "0",
            "parking_spot": {
                "id": spot.id,
                "name": spot.name
            },
            "labels": labels
        }), 200

    except Exception as e:
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500


@app.route("/parking_spots/<int:spot_id>/labels", methods=["POST"])
def add_parking_spot_label(spot_id):
 
    data = request.get_json()
    if not data:
        return jsonify({'desc': 'Missing request body', 'code': '1'}), 400

    if 'label_id' not in data:
        return jsonify({'desc': 'Missing field: label_id', 'code': '2'}), 400

    label_id = data['label_id']

    if not isinstance(label_id, int):
        return jsonify({'desc': 'Invalid label_id type (must be integer)', 'code': '3'}), 400

    try:
        with db.session.begin():
            spot = ParkingSpot.query.filter_by(id=spot_id).with_for_update().first()
            if not spot:
                return jsonify({
                    'desc': f'Parking spot {spot_id} not found',
                    'code': '4'
                }), 404

            label = Label.query.get(label_id)
            if not label:
                return jsonify({
                    'desc': f'Label {label_id} not found',
                    'code': '5'
                }), 404

            existing_link = ParkingSpotLabel.query.filter_by(
                parking_spot_id=spot.id,
                label_id=label.id
            ).first()

            if existing_link:
                return jsonify({
                    'desc': f'Label {label_id} is already associated with parking spot {spot_id}',
                    'code': '6'
                }), 409

            new_link = ParkingSpotLabel(
                parking_spot_id=spot.id,
                label_id=label.id
            )
            db.session.add(new_link)

        return jsonify({
            'desc': f'Label {label_id} successfully added to parking spot {spot_id}',
            'code': '0',
            'parking_spot_label': {
                'parking_spot_id': spot.id,
                'label_id': label.id,
                'label_name': label.name,
                'label_description': label.label_description
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/parking_spots/<int:spot_id>/labels/<int:label_id>", methods=["DELETE"])
def delete_parking_spot_label(spot_id, label_id):

    try:
        with db.session.begin():
            spot = ParkingSpot.query.filter_by(id=spot_id).with_for_update().first()
            if not spot:
                return jsonify({
                    'desc': f'Parking spot {spot_id} not found',
                    'code': '1'
                }), 404

            label = Label.query.get(label_id)
            if not label:
                return jsonify({
                    'desc': f'Label {label_id} not found',
                    'code': '2'
                }), 404

            link = ParkingSpotLabel.query.filter_by(
                parking_spot_id=spot.id,
                label_id=label.id
            ).first()

            if not link:
                return jsonify({
                    'desc': f'Label {label_id} is not associated with parking spot {spot_id}',
                    'code': '3'
                }), 404

            db.session.delete(link)

        return jsonify({
            'desc': f'Label {label_id} removed from parking spot {spot_id} successfully',
            'code': '0'
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500
# ------------ PARKING SPOTS ------------



# ------------ TIME SLOTS ------------
@app.route("/time_slots/<int:park_id>", methods=["GET"])
def get_availability_slots(park_id):

    try:
        spot = ParkingSpot.query.get(park_id)
        if not spot:
            return jsonify({
                "desc": f"Parking spot {park_id} not found",
                "code": "1"
            }), 404

        now = datetime.now()
        today = now.date()
        current_time = now.time()

        active_reservations = (
            db.session.query(Reservation.slot_id)
            .filter(Reservation.reservation_status.in_(["pending", "confirmed"]))
            .subquery()
        )

        available_slots = (
            db.session.query(AvailabilitySlot)
            .filter(
                AvailabilitySlot.parking_spot_id == spot.id,
                ~AvailabilitySlot.id.in_(active_reservations),
                or_(
                    AvailabilitySlot.slot_date > today,
                    and_(
                        AvailabilitySlot.slot_date == today,
                        AvailabilitySlot.end_time > current_time
                    )
                )
            )
            .order_by(AvailabilitySlot.slot_date.asc(), AvailabilitySlot.start_time.asc())
            .all()
        )

        available_slots_json = [
            {
                "id": slot.id,
                "slot_date": slot.slot_date.isoformat(),
                "start_time": slot.start_time.strftime("%H:%M"),
                "end_time": slot.end_time.strftime("%H:%M")
            }
            for slot in available_slots
        ]

        return jsonify({
            "desc": "Available future time slots retrieved successfully",
            "code": "0",
            "parking_spot": {
                "id": spot.id,
                "name": spot.name
            },
            "available_slots": available_slots_json,
            "count": len(available_slots_json)
        }), 200

    except Exception as e:
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500


@app.route("/time_slots/<int:park_id>", methods=["POST"])
def create_time_slot(park_id):

    data = request.get_json()
    if not data:
        return jsonify({'desc': 'Missing request body', 'code': '1'}), 400

    required_fields = ['slot_date', 'start_time', 'end_time']
    if not all(f in data for f in required_fields):
        return jsonify({'desc': 'Missing required fields', 'code': '2'}), 400

    try:
        slot_date = datetime.strptime(data['slot_date'], "%Y-%m-%d").date()
        start_time = datetime.strptime(data['start_time'], "%H:%M").time()
        end_time = datetime.strptime(data['end_time'], "%H:%M").time()
    except ValueError:
        return jsonify({'desc': 'Invalid date/time format', 'code': '3'}), 400

    if end_time <= start_time:
        return jsonify({'desc': 'Invalid time range (end_time must be after start_time)', 'code': '4'}), 400

    try:
        with db.session.begin():
            spot = ParkingSpot.query.filter_by(id=park_id).with_for_update().first()
            if not spot:
                return jsonify({'desc': f'Parking spot {park_id} not found', 'code': '5'}), 404

            overlapping_slot = (
                AvailabilitySlot.query.filter(
                    AvailabilitySlot.parking_spot_id == spot.id,
                    AvailabilitySlot.slot_date == slot_date,
                    AvailabilitySlot.start_time < end_time,
                    AvailabilitySlot.end_time > start_time
                ).first()
            )

            if overlapping_slot:
                return jsonify({
                    'desc': (
                        f"Time slot overlaps with existing slot "
                        f"{overlapping_slot.start_time.strftime('%H:%M')} - "
                        f"{overlapping_slot.end_time.strftime('%H:%M')} on {slot_date}"
                    ),
                    'code': '6'
                }), 409

            new_slot = AvailabilitySlot(
                slot_date=slot_date,
                start_time=start_time,
                end_time=end_time,
                parking_spot_id=spot.id
            )
            db.session.add(new_slot)

        return jsonify({
            "desc": "Availability slot created successfully",
            "code": "0",
            "availability_slot": {
                "id": new_slot.id,
                "slot_date": new_slot.slot_date.isoformat(),
                "start_time": new_slot.start_time.strftime("%H:%M"),
                "end_time": new_slot.end_time.strftime("%H:%M"),
                "parking_spot_id": spot.id
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/time_slots/<int:slot_id>", methods=["DELETE"])
def delete_availability_slot(slot_id):

    try:
        with db.session.begin():
            slot = AvailabilitySlot.query.filter_by(id=slot_id).with_for_update().first()
            if not slot:
                return jsonify({
                    "desc": f"Availability slot {slot_id} not found",
                    "code": "1"
                }), 404

            active_reservation = (
                db.session.query(Reservation)
                .filter(
                    Reservation.slot_id == slot.id,
                    Reservation.reservation_status.in_(["pending", "confirmed"])
                )
                .first()
            )

            if active_reservation:
                return jsonify({
                    "desc": f"Slot {slot_id} cannot be deleted because it has an active reservation "
                            f"({active_reservation.reservation_status})",
                    "code": "2"
                }), 409

            db.session.delete(slot)

        return jsonify({
            "desc": f"Availability slot {slot_id} deleted successfully",
            "code": "0"
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500
# ------------ TIME SLOTS ------------



# ------------ SEARCHING ------------
@app.route("/search", methods=["POST"])
def search_parking_spot():

    data = request.get_json()
    if not data:
        return jsonify({'desc': 'Missing request body', 'code': '1'}), 400

    required_fields = ['latitude', 'longitude', 'user_reputation']
    if not all(field in data for field in required_fields):
        return jsonify({'desc': 'Missing required fields', 'code': '2'}), 400

    try:
        latitude = float(data['latitude'])
        longitude = float(data['longitude'])
        user_reputation = float(data['user_reputation'])
        radius = data.get('radius', None)
        label_ids = data.get('labels', None)

        if radius is not None:
            radius = float(radius)
            if radius <= 0:
                return jsonify({'desc': 'Radius must be greater than zero', 'code': '4'}), 400
        else:
            radius = None
    except ValueError:
        return jsonify({'desc': 'Latitude, longitude, reputation or radius must be numeric', 'code': '3'}), 400

    try:
        now = datetime.now()
        today = date.today()
        current_time = now.time()

        user_point = WKTElement(f'POINT({longitude} {latitude})', srid=4326)

        nearby_spots_query = db.session.query(
            ParkingSpot,
            func.ST_Distance_Sphere(ParkingSpot.spot_location, user_point).label("distance_meters")
        )

        nearby_spots_query = nearby_spots_query.filter(ParkingSpot.rep_treshold <= user_reputation)

        if radius is not None:
            nearby_spots_query = nearby_spots_query.filter(
                func.ST_Distance_Sphere(ParkingSpot.spot_location, user_point) <= radius
            )

        if label_ids and isinstance(label_ids, list) and len(label_ids) > 0:
            nearby_spots_query = nearby_spots_query.join(ParkingSpot.labels).filter(
                ParkingSpotLabel.label_id.in_(label_ids)
            ).group_by(ParkingSpot.id)

        nearby_spots = nearby_spots_query.all()

        if not nearby_spots:
            return jsonify({
                'desc': 'No parking spots found within the given filters',
                'code': '5',
                'results': [],
                'user_reputation': user_reputation
            }), 200

        active_reservations = (
            db.session.query(Reservation.slot_id)
            .filter(Reservation.reservation_status.in_(["pending", "confirmed"]))
            .subquery()
        )

        next_slot_subq = (
            db.session.query(
                AvailabilitySlot.parking_spot_id.label("spot_id"),
                func.min(
                    func.timestamp(
                        AvailabilitySlot.slot_date,
                        AvailabilitySlot.start_time
                    )
                ).label("next_slot_time")
            )
            .filter(
                ~AvailabilitySlot.id.in_(active_reservations),
                or_(
                    AvailabilitySlot.slot_date > today,
                    and_(
                        AvailabilitySlot.slot_date == today,
                        AvailabilitySlot.start_time > current_time
                    )
                )
            )
            .group_by(AvailabilitySlot.parking_spot_id)
            .subquery()
        )

        results = []

        for spot, distance in nearby_spots:
            next_slot_time = db.session.query(next_slot_subq.c.next_slot_time).filter(
                next_slot_subq.c.spot_id == spot.id
            ).scalar()

            if not next_slot_time:
                continue

            next_slot = (
                db.session.query(AvailabilitySlot)
                .filter(
                    AvailabilitySlot.parking_spot_id == spot.id,
                    ~AvailabilitySlot.id.in_(active_reservations),
                    func.timestamp(AvailabilitySlot.slot_date, AvailabilitySlot.start_time) == next_slot_time
                )
                .first()
            )

            point = to_shape(spot.spot_location)
            latitude = point.y
            longitude = point.x
            
            if next_slot:
                results.append({
                    "parking_spot_id": spot.id,
                    "latitude": latitude,
                    "longitude": longitude,
                    "name": spot.name,
                    "rep_treshold": spot.rep_treshold,
                    "slot_price": spot.slot_price,
                    "distance_meters": round(distance, 2),
                    "next_slot": {
                        "id": next_slot.id,
                        "slot_date": next_slot.slot_date.isoformat(),
                        "start_time": next_slot.start_time.strftime("%H:%M"),
                        "end_time": next_slot.end_time.strftime("%H:%M")
                    }
                })

        results.sort(key=lambda x: x['distance_meters'])

        if not results:
            return jsonify({
                'desc': 'No available parking slots found within reputation or filter constraints',
                'code': '6',
                'results': [],
                'user_reputation': user_reputation
            }), 200

        return jsonify({
            'desc': 'Available parking spots retrieved successfully',
            'code': '0',
            'count': len(results),
            'user_reputation': user_reputation,
            'filters': {
                "radius": "∞" if radius is None else radius,
                "labels": label_ids if label_ids else []
            },
            'results': results
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500
# ------------ SEARCHING ------------



# ------------ RESERVATIONS ------------
@app.route("/reservations/users/<int:user_id>", methods=["GET"])
def get_reservations(user_id):

    try:
        reservations = Reservation.query.filter_by(user_id=user_id).order_by(Reservation.reservation_status, Reservation.reservation_ts.desc()).all()
        if not reservations:
            return jsonify({
                "desc": "No reservations found for this user",
                "code": "1",
                "reservations": []
            }), 200 

        slot_ids = [r.slot_id for r in reservations]
        slots = AvailabilitySlot.query.filter(AvailabilitySlot.id.in_(slot_ids)).all()
        slot_map = {s.id: s for s in slots}

        results = []
        for res in reservations:
            slot = slot_map.get(res.slot_id)
            results.append({
                "id": res.id,
                "user_id": res.user_id,
                "ts": res.reservation_ts.isoformat(),
                "status": res.reservation_status,
                "slot_id": res.slot_id,
                "plate": res.car_plate,
                "spot_name": res.slot.parking_spot.name,
                "spot_latitude": to_shape(res.slot.parking_spot.spot_location).y,
                "spot_longitude": to_shape(res.slot.parking_spot.spot_location).x,
                "resident_name": res.slot.parking_spot.owner.name,
                "resident_surname": res.slot.parking_spot.owner.surname,
                "start_time": slot.start_time.strftime("%H:%M") if slot else None,
                "end_time": slot.end_time.strftime("%H:%M") if slot else None,
                "slot_date": slot.slot_date.isoformat() if slot else None
            })

        return jsonify({
            "desc": "Reservations retrieved successfully",
            "code": "0",
            "count": len(results),
            "reservations": results
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500


@app.route("/reservations/<int:res_id>", methods=["GET"])
def get_reservation(res_id):

    try:
        res = Reservation.query.filter_by(id=res_id).first()
        if not res:
            return jsonify({
                "desc": "No reservation found with this id",
                "code": "1",
                "reservation": {}
            }), 200 

        slot = AvailabilitySlot.query.filter_by(id=res.slot_id).first()

        return jsonify({
            "desc": "Reservations retrieved successfully",
            "code": "0",
            "reservation": {
                "res_id": res.id,
                "user_id": res.user_id,
                "ts": res.reservation_ts.isoformat(),
                "status": res.reservation_status,
                "slot_id": res.slot_id,
                "start_time": slot.start_time.strftime("%H:%M") if slot else None,
                "end_time": slot.end_time.strftime("%H:%M") if slot else None,
                "slot_date": slot.slot_date.isoformat() if slot else None
            }
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500


@app.route("/reservations", methods=["POST"])
def create_reservation():

        data = request.get_json()

        required_fields = ['slot_id', 'car_plate', 'user_id']
        if not all(field in data for field in required_fields):
            return jsonify({'desc': 'Missing required fields', 'code': '2'}), 400
        
        slot_id = data['slot_id']
        car_plate = data['car_plate'].upper().strip()
        user_id = data['user_id']
        
        if not isinstance(slot_id, int):
            return jsonify({'desc': 'slot_id must be an integer', 'code': '3'}), 400
        if not isinstance(car_plate, str) or len(car_plate) != 7:
            return jsonify({'desc': 'car_plate must be 7 characters', 'code': '4'}), 400

        try:
            with db.session.begin():
                slot = AvailabilitySlot.query.filter_by(id=slot_id).with_for_update().first()
                spot = ParkingSpot.query.filter_by(id=slot.parking_spot_id).first()

                if user_id == spot.user_id:
                    return jsonify({'desc': 'This is your parking spot, impossible to book it', 'code': 5}), 400
                if not slot:
                    return jsonify({'desc': f'Slot {slot_id} not found', 'code': '6'}), 404

                existing_reservation = (
                    db.session.query(Reservation)
                    .filter(
                        Reservation.slot_id == slot.id,
                        Reservation.reservation_status.in_(["pending", "confirmed"])
                    )
                    .with_for_update(read=True).first()
                )

                if existing_reservation:
                    return jsonify({
                        'desc': f'Slot {slot_id} is already reserved (pending or confirmed)',
                        'code': '7'
                    }), 409

                new_reservation = Reservation(
                    reservation_ts=datetime.now(),
                    reservation_status='pending', 
                    car_plate=car_plate,
                    slot_id=slot.id,
                    user_id=user_id
                )

                db.session.add(new_reservation)

            return jsonify({
                'desc': 'Reservation created successfully (pending approval)',
                'code': '0',
                'reservation': {
                    'id': new_reservation.id,
                    'slot_id': new_reservation.slot_id,
                    'user_id': new_reservation.user_id,
                    'car_plate': new_reservation.car_plate,
                    'reservation_status': new_reservation.reservation_status,
                    'reservation_ts': new_reservation.reservation_ts.isoformat()
                }
            }), 201

        except Exception as e:
            db.session.rollback()
            return jsonify({
                'desc': f'Database error: {str(e)}',
                'code': '99'
            }), 500


@app.route("/reservations/<int:res_id>/status", methods=["PUT"])
def update_reservation(res_id):

    try:
        data = request.get_json()
        if not data or "new_status" not in data or "user_id" not in data: 
            return jsonify({
                'desc': 'Missing new_status or user_id in request body',
                'code': '1'
            }), 400

        new_status = data["new_status"].lower().strip()
        user_id = data["user_id"]

        valid_statuses = ["pending", "confirmed", "cancelled", "completed"]
        if new_status not in valid_statuses:
            return jsonify({
                'desc': f"Invalid reservation_status value: '{new_status}'",
                'code': '2'
            }), 400

        allowed_transitions = {
            "pending": ["confirmed", "cancelled"],
            "confirmed": ["completed", "cancelled"],
            "cancelled": [],
            "completed": []
        }

        with db.session.begin():
            reservation = Reservation.query.filter_by(id=res_id).with_for_update().first()
            if not reservation:
                return jsonify({
                    'desc': f"Reservation {res_id} not found",
                    'code': '3'
                }), 404

            slot_id = reservation.slot_id
            slot = AvailabilitySlot.query.filter_by(id=slot_id).with_for_update().first()
            spot_id = slot.parking_spot_id
            spot = ParkingSpot.query.filter_by(id=spot_id).with_for_update().first()

            resident_id = spot.user_id
            driver_id = reservation.user_id
            allowed_users = [resident_id, driver_id]

            if user_id not in allowed_users:
                return jsonify({
                    'desc': "operation not allowed: neither driver nor resident.",
                    'code': '4'
                }), 400
            current_status = reservation.reservation_status

            if new_status == current_status:
                return jsonify({
                    'desc': f"Reservation already in status '{new_status}'",
                    'code': '5'
                }), 200

            if new_status not in allowed_transitions.get(current_status, []):
                return jsonify({
                    'desc': f"Transition from '{current_status}' to '{new_status}' not allowed",
                    'code': '6'
                }), 400

            reservation.reservation_status = new_status

        return jsonify({
            'desc': 'Reservation status updated successfully',
            'code': '0',
            'reservation': {
                'id': reservation.id,
                'slot_id': reservation.slot_id,
                'user_id': reservation.user_id,
                'car_plate': reservation.car_plate,
                'previous_status': current_status,
                'new_status': reservation.reservation_status,
                'reservation_ts': reservation.reservation_ts.isoformat()
            }
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500
# ------------ RESERVATIONS ------------



# ------------ REQUESTS ------------
@app.route("/requests/<int:user_id>", methods=["GET"])
def get_requests(user_id):
    try:
        reservations = (
            db.session.query(Reservation, AvailabilitySlot, ParkingSpot)
            .join(AvailabilitySlot, Reservation.slot_id == AvailabilitySlot.id)
            .join(ParkingSpot, AvailabilitySlot.parking_spot_id == ParkingSpot.id)
            .filter(
                ParkingSpot.user_id == user_id,
                Reservation.reservation_status == "pending"
            )
            .all()
        )

        if not reservations:
            return jsonify({
                "desc": "No pending reservation requests for your parking spots",
                "code": "1",
                "requests": []
            }), 200

        results = []
        for res, slot, spot in reservations:
            results.append({
                "reservation_id": res.id,
                "driver_id": res.user_id,
                "parking_spot_id": spot.id,
                "parking_spot_name": spot.name,
                "slot_id": slot.id,
                "slot_date": slot.slot_date.isoformat(),
                "start_time": slot.start_time.strftime("%H:%M"),
                "end_time": slot.end_time.strftime("%H:%M"),
                "car_plate": res.car_plate,
                "status": res.reservation_status,
                "reservation_ts": res.reservation_ts.isoformat()
            })

        return jsonify({
            "desc": "Pending reservation requests retrieved successfully",
            "code": "0",
            "count": len(results),
            "requests": results
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500
# ------------ REQUESTS ------------



# ------------ LABELS ------------
@app.route("/labels", methods=["GET"])
def get_labels():

    try:
        labels = Label.query.all()

        if not labels:
            return jsonify({
                "desc": "No labels found",
                "code": "1",
                "labels": []
            }), 200

        label_list = [
            {
                "id": label.id,
                "name": label.name,
                "description": label.label_description
            }
            for label in labels
        ]

        return jsonify({
            "desc": "Labels retrieved successfully",
            "code": "0",
            "labels": label_list
        }), 200

    except Exception as e:
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500


@app.route("/labels", methods=["POST"])
def create_label():

    data = request.get_json()
    if not data:
        return jsonify({'desc': 'Missing request body', 'code': '1'}), 400

    required_fields = ['name', 'description']
    if not all(field in data for field in required_fields):
        return jsonify({'desc': 'Missing required fields', 'code': '2'}), 400

    name = data['name'].strip() if isinstance(data['name'], str) else None
    description = data['description'].strip() if isinstance(data['description'], str) else None

    if not name or not description:
        return jsonify({'desc': 'Invalid name or description', 'code': '3'}), 400

    try:
        with db.session.begin():
            existing = Label.query.filter(func.lower(Label.name) == func.lower(name)).first()
            if existing:
                return jsonify({
                    'desc': f'Label with name "{name}" already exists',
                    'code': '4'
                }), 409

            new_label = Label(name=name, label_description=description)
            db.session.add(new_label)

        return jsonify({
            "desc": "Label created successfully",
            "code": "0",
            "label": {
                "id": new_label.id,
                "name": new_label.name,
                "description": new_label.label_description
            }
        }), 201

    except Exception as e:
        db.session.rollback()
        return jsonify({
            "desc": f"Database error: {str(e)}",
            "code": "99"
        }), 500
# ------------ LABELS ------------



# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5002, debug=True)
