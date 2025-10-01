import os
from flask import Flask, request, render_template, jsonify

app = Flask(__name__)

# Directory to save uploaded files
UPLOAD_FOLDER = 'PENDING_VERIFICATION'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif'}

def allowed_file(filename):
    """Check if a file is allowed based on its extension."""
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/submit_order', methods=['POST'])
def submit_order():
    order_number = request.form.get('orderNumber')
    item = request.form.get('item')
    quantity = request.form.get('quantity')
    id_file = request.files.get('idUpload')

    if not order_number or not item or not quantity or not id_file:
        return jsonify({'error': 'All fields are required.'}), 400

    if not allowed_file(id_file.filename):
        return jsonify({'error': 'Invalid file type. Only image files are allowed.'}), 400

    # Save file with order number as the name
    filename = f"{order_number}_{id_file.filename}"
    save_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
    id_file.save(save_path)

    return jsonify({
        'message': 'Order submitted successfully!',
        'orderNumber': order_number,
        'item': item,
        'quantity': quantity,
        'fileSavedAs': filename
    })

@app.route('/')
def index():
    """Serve the HTML form."""
    return render_template('index.html')

if __name__ == '__main__':
    app.run(debug=True)
