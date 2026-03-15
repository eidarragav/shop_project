require("dotenv").config();

const express = require("express");
const connectDB = require("./db"); 

const app = express();

app.use(express.json());

connectDB();

const TOKEN_SECRETO = process.env.TOKEN_APIS

function requireToken(req, res, next){
    const token = req.headers["Authorization"];
    if(token !== `${TOKEN_SECRETO}`){
        return res.status(401).json({error : "No autorizado"})
    }
    next();
}



const Sale = require("./models/sale.js");

app.get("/api/sales", requireToken, async (req, res) => {
    const sales = await Sale.find();
    res.json(sales);
});

app.post("/api/sales", requireToken, async (req, res) => {
    const sale = new Sale(req.body);
    const savedSale = await sale.save();
    res.json(savedSale);
});

app.get("/api/sales/:id", requireToken, async (req, res) => {
    const sale = await Sale.findById(req.params.id);
    res.json(sale);
});

app.put("/api/sales/:id", requireToken, async (req, res) => {
    const updated = await Sale.findByIdAndUpdate(
        req.params.id,
        req.body,
        { new: true }
    );

    res.json(updated);
});

app.delete("/api/sales/:id", requireToken, async (req, res) => {
    await Sale.findByIdAndDelete(req.params.id);
    res.json({ message: "Venta eliminada" });
});

app.post("/api/my_sales", requireToken, async (req, res) => {

    const { user_id } = req.body;

    const sales = await Sale.find({ user_id: user_id });

    return res.json(sales);

});



const PORT = process.env.PORT;

app.listen(PORT, () => {
    console.log(`Servidor corriendo en puerto ${PORT}`);
});