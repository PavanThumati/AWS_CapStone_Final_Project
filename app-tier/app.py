from flask import Flask, request, jsonify
import pymysql
import config

app = Flask(__name__)

def get_db_connection():
    return pymysql.connect(
        host=config.DB_HOST,
        user=config.DB_USER,
        password=config.DB_PASSWORD,
        database=config.DB_NAME,
        cursorclass=pymysql.cursors.DictCursor  # So output is JSON serializable
    )

@app.route('/', methods=['GET', 'POST'])
def index():
    conn = get_db_connection()
    cursor = conn.cursor()

    if request.method == 'POST':
        name = request.form.get('name')
        if name:
            cursor.execute("INSERT INTO users (name) VALUES (%s)", (name,))
            conn.commit()

    cursor.execute("SELECT id, name FROM users")
    users = cursor.fetchall()
    conn.close()
    return jsonify(users)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
