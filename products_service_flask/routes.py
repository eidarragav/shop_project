from flask import jsonify, request, current_app
from firebase import db

def register_routes(app):
    @app.route("/api/products", methods = ['POST'])
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
    def delete_products(id):
        product = db.collection("products").document(id).delete()
        


        
    