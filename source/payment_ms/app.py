from flask import Flask, jsonify, request
from config import DB_CONFIG
from models import db, User, Payment
from sqlalchemy import func
from decimal import Decimal



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


# ------------ PAYMENTS ------------- #
@app.route("/payments/request", methods=["POST"])
def create_payment():
    """Register a new payment for a reservation made by a user"""
    data = request.get_json()
    
    required_fields = ['amount', 'method', 'reservation_id', 'user_id', 'reservation_date', 'reservation_start', 'reservation_end', 'resident_id']
    if not data or not all(field in data for field in required_fields):
        return jsonify({
            'desc': 'Missing required fields',
            'code': '1'
        }), 400
    
    amount = data['amount']
    method = data['method']
    reservation_id = data['reservation_id']
    user_id = data['user_id']
    resident_id = data['resident_id']
    reservation_date = data['reservation_date']
    reservation_start = data['reservation_start']
    reservation_end = data['reservation_end']
    
    # Validate payment method
    valid_methods = ['applepay', 'paypal', 'googlepay', 'creditcard']
    if method not in valid_methods:
        return jsonify({
            'desc': f'Invalid payment method. Must be one of: {", ".join(valid_methods)}',
            'code': '2'
        }), 400
    
    try:
        user = User.query.filter_by(id=user_id).first()
        if not user:
            return jsonify({
                'desc': 'User not found',
                'code': '3'
            }), 404
        
        resident = User.query.filter_by(id=resident_id).first()
        if not user:
            return jsonify({
                'desc': 'Resident not found',
                'code': '4'
            }), 404
        
        existing_payment = Payment.query.filter_by(reservation_id=reservation_id).first()
        if existing_payment:
            return jsonify({
                'desc': 'Payment already exists for this reservation',
                'code': '5'
            }), 409
        
        new_payment = Payment(
            amount=str(amount),
            method=method,
            reservation_id=reservation_id,
            user_id=user_id,
            resident_id = resident_id,
            payment_status='pending',
            reservation_date=reservation_date,
            reservation_start=reservation_start,
            reservation_end=reservation_end
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
                'user_id': new_payment.user_id,
                'resident_id': new_payment.resident_id
            }
        }), 201
        
    except Exception as e:
        db.session.rollback()
        return jsonify({
            'desc': f'Database error: {str(e)}',
            'code': '99'
        }), 500


@app.route("/payments/user/<int:user_id>/earnings", methods=["GET"])
def get_tot_earnings(user_id):
    try:

        user = User.query.filter_by(id = user_id).first()
        if not user: 
            return jsonify({
                'desc': "Invalid user", 
                'code': "1"
            }), 404

        incomes = sum(
            (p.amount for p in user.payments_received if p.payment_status == "completed"),
            Decimal("0")
        )

        outcomes = sum(
            (p.amount for p in user.payments_made if p.payment_status == "completed"),
            Decimal("0")
        )

        earnings = incomes - outcomes

        payments_made = user.payments_made
        payments_received = user.payments_received

        total_payments = payments_received + payments_made
        total_payments.sort(key=lambda p: p.payment_ts, reverse=True)

        payments_json = []
        modality = "received"
        for payment in total_payments:
            if payment.user_id == user_id:
                modality = "made"
            else:
                modality = "received"

            payments_json.append({
                'id': payment.id,
                'amount': payment.amount,
                'method': payment.method,
                'payment_status': payment.payment_status,
                'payment_ts': payment.payment_ts.isoformat(),
                'reservation_id': payment.reservation_id,
                'modality': modality,
                'resident': payment.resident.name + " " + payment.resident.surname,
            })
    
        return jsonify({
            'desc': "Total earnigns retrieved successfully", 
            'code': "0", 
            'earnings': earnings, 
            'payments_list': payments_json
        }), 200
    
    except Exception as e:
        return jsonify({
            'desc': f"Database error: {e}", 
            'code': "99"
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
    
    valid_statuses = ['completed', 'failed']
    if new_status not in valid_statuses:
        return jsonify({
            'desc': f'Invalid payment status. Must be one of: {", ".join(valid_statuses)}',
            'code': '2'
        }), 400
    
    try:
        payment = Payment.query.filter_by(id=payment_id).first()
        
        if not payment:
            return jsonify({
                'desc': 'Payment not found',
                'code': '3'
            }), 404
        
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
# ------------ PAYMENTS ------------- #


# ------------ MAIN ------------ # 
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5003, debug=True)
# ------------ MAIN ------------ # 
