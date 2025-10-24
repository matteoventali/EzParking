from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User, Reservation, Payment

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
    return jsonify({"message": "Payment Service is active"}), 200


# ------------ PAYMENTS ------------
@app.route("/payments", methods=["POST"])
def create_payment():
    """Register a new payment for a reservation made by a user"""
    data = request.get_json()
    
    required_fields = ['amount', 'method', 'reservation_id', 'user_id']
    if not data or not all(field in data for field in required_fields):
        return jsonify({
            'desc': 'Missing required fields',
            'code': '1'
        }), 400
    
    amount = data['amount']
    method = data['method']
    reservation_id = data['reservation_id']
    user_id = data['user_id']
    
    # Validate payment method
    valid_methods = ['credit_card', 'paypal', 'bank_transfer']
    if method not in valid_methods:
        return jsonify({
            'desc': f'Invalid payment method. Must be one of: {", ".join(valid_methods)}',
            'code': '2'
        }), 400
    
    try:
        # Check if user exists
        user = User.query.filter_by(id=user_id).first()
        if not user:
            return jsonify({
                'desc': 'User not found',
                'code': '3'
            }), 404
        
        # Check if reservation exists
        reservation = Reservation.query.filter_by(id=reservation_id).first()
        if not reservation:
            return jsonify({
                'desc': 'Reservation not found',
                'code': '4'
            }), 404
        
        # Check if payment already exists for this reservation
        existing_payment = Payment.query.filter_by(reservation_id=reservation_id).first()
        if existing_payment:
            return jsonify({
                'desc': 'Payment already exists for this reservation',
                'code': '5'
            }), 409
        
        # Create new payment
        new_payment = Payment(
            amount=str(amount),
            method=method,
            reservation_id=reservation_id,
            user_id=user_id,
            payment_status='pending'
        )
        
        db.session.add(new_payment)
        db.session.commit()
        
        return jsonify({
            'desc': 'Payment created successfully',
            'code': '0',
            'payment': {
                'id': new_payment.id,
                'amount': new_payment.amount,
                'method': new_payment.method,
                'payment_status': new_payment.payment_status,
                'payment_ts': new_payment.payment_ts.isoformat(),
                'reservation_id': new_payment.reservation_id,
                'user_id': new_payment.user_id
            }
        }), 201
        
    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/payments/user/<int:user_id>", methods=["GET"])
def get_payments_by_user(user_id):
    """Retrieve all payments made by a specific user"""
    try:
        # Check if user exists
        user = User.query.filter_by(id=user_id).first()
        if not user:
            return jsonify({
                'desc': 'User not found',
                'code': '1'
            }), 404
        
        # Get all payments for the user
        payments = Payment.query.filter_by(user_id=user_id).all()
        
        payments_list = [
            {
                'id': payment.id,
                'amount': payment.amount,
                'method': payment.method,
                'payment_status': payment.payment_status,
                'payment_ts': payment.payment_ts.isoformat(),
                'reservation_id': payment.reservation_id
            }
            for payment in payments
        ]
        
        return jsonify({
            'desc': 'Payments retrieved successfully',
            'code': '0',
            'payments': payments_list
        }), 200
        
    except Exception as e:
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/payments/reservation/<int:reservation_id>", methods=["GET"])
def get_payment_by_reservation(reservation_id):
    """Retrieve the payment associated with a specific reservation"""
    try:
        # Check if reservation exists
        reservation = Reservation.query.filter_by(id=reservation_id).first()
        if not reservation:
            return jsonify({
                'desc': 'Reservation not found',
                'code': '1'
            }), 404
        
        # Get payment for the reservation
        payment = Payment.query.filter_by(reservation_id=reservation_id).first()
        
        if not payment:
            return jsonify({
                'desc': 'Payment not found for this reservation',
                'code': '2'
            }), 404
        
        return jsonify({
            'desc': 'Payment retrieved successfully',
            'code': '0',
            'payment': {
                'id': payment.id,
                'amount': payment.amount,
                'method': payment.method,
                'payment_status': payment.payment_status,
                'payment_ts': payment.payment_ts.isoformat(),
                'reservation_id': payment.reservation_id,
                'user_id': payment.user_id
            }
        }), 200
        
    except Exception as e:
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/payments/<int:payment_id>", methods=["PUT"])
def update_payment_status(payment_id):
    """Update the status of an existing payment (e.g., from pending to completed)"""
    data = request.get_json()
    
    if not data or 'payment_status' not in data:
        return jsonify({
            'desc': 'Missing payment_status field',
            'code': '1'
        }), 400
    
    new_status = data['payment_status']
    
    # Validate payment status
    valid_statuses = ['pending', 'completed', 'failed']
    if new_status not in valid_statuses:
        return jsonify({
            'desc': f'Invalid payment status. Must be one of: {", ".join(valid_statuses)}',
            'code': '2'
        }), 400
    
    try:
        # Get payment
        payment = Payment.query.filter_by(id=payment_id).first()
        
        if not payment:
            return jsonify({
                'desc': 'Payment not found',
                'code': '3'
            }), 404
        
        # Update payment status
        payment.payment_status = new_status
        db.session.commit()
        
        return jsonify({
            'desc': 'Payment status updated successfully',
            'code': '0',
            'payment': {
                'id': payment.id,
                'amount': payment.amount,
                'method': payment.method,
                'payment_status': payment.payment_status,
                'payment_ts': payment.payment_ts.isoformat(),
                'reservation_id': payment.reservation_id,
                'user_id': payment.user_id
            }
        }), 200
        
    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


# -------------------------------
# MAIN
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5003, debug=True)
