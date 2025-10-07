from flask import Flask, jsonify
import mysql.connector
import os

app = Flask(__name__)

#DB_CONFIG = {
#    "host": os.environ.get("DB_HOST", "mariadb"),
#    "user": os.environ.get("DB_USER", "user1"),
#    "password": os.environ.get("DB_PASSWORD", "password1"),
#    "database": os.environ.get("DB_NAME", "microservizio1"),
#    "port": 3306
#}

DB_CONFIG = {
    "host": "dbms",
    "user": "user1",
    "password": "password1",
    "database": "microservizio1",
    "port": 3306
}

@app.route("/")
def index():
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor()
        cursor.execute("SELECT NOW(), campo FROM A;")
        result = cursor.fetchone()
        conn.close()
        return jsonify({
            "message": "Connessione a MariaDB riuscita!",
            "timestamp": str(result[1]) + "--modifica--" + str(result[0])
        })
    except mysql.connector.Error as err:
        return jsonify({"error": str(err)}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)