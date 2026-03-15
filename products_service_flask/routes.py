from flask import jsonify, request, current_app
from firebase import db
from dotenv import load_dotenv
from functools import wraps
import os

load_dotenv()

def require_token(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        token = request.headers.get("Authorization")
        if token!= os.getenv("TOKEN_APIS"):
            return jsonify({'error' : "no aturizado"})
        return f(*args, **kwargs)
    return decorated

def register_routes(app):
    @app.route("/api/products/validar_stock/<id>", methods = ["GET"])
    @require_token
    def validar_stock(id):
        product = db.collection("products").document(id).get()
        if(not product.exists):
           return jsonify({"mensaje": "Producto no encontrado"}), 404
        
        stock = product.get("stock")
        return {"id": id, "stock" : stock}
    
    @app.route("/api/products/descontar_stock/<id>", methods = ["POST"])
    @require_token
    def descontar_stock(id):
        data = request.get_json();

        product_ref = db.collection("products").document(id)
        product = product_ref.get()

        quantity = data["quantity"]

        product_ref.update({
            "stock" : product.get("stock") - quantity
        })

        return jsonify({"mensaje" : "stock actualizado"})




    @app.route("/api/products", methods = ['POST'])
    @require_token
    def post_products():
        data = request.get_json()
        product = {
            "name" : data["name"],
            "price" : data["price"],
            "stock" : data["stock"],
            "category" : data["category"]
        }

        db.collection("products").add(product)

        return jsonify({"mensaje" : "producto creado con exito"})

    @app.route("/api/products", methods = ['GET'])
    @require_token
    def get_products():
        products = db.collection("products").stream()

        resultado = []

        for p in products:
            resultado.append({
                "id": p.id,
                "name": p.get("name"),
                "price": p.get("price"),
                "stock": p.get("stock"),
                "category": p.get("category")
        })

        return jsonify(resultado)

    
    @app.route("/api/products/<id>", methods = ["PUT"])
    @require_token
    def edit_product(id):
        data = request.get_json()

        db.collection("products").document(id).update({
            "name" : data["name"],
            "price" : data["price"],
            "stock" : data["stock"],
            "category" : data["category"],
        })

        return jsonify({"mensaje" : "Producto actualizado"})
    

    @app.route("/api/products/<id>", methods = ["DELETE"])
    @require_token
    def delete_products(id):
        product = db.collection("products").document(id).delete()
        


        
    