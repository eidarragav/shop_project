require("dotenv").config();

const express = require("express");
const connectDB = require("./db"); 

const app = express();

app.use(express.json());

connectDB();



const Sale = require("./models/sale.js");

app.get("/sales", async (req, res) => {
    const sales = await Sale.find();
    res.json(sales);
});

app.post("/sales", async (req, res) => {
    const sale = new Sale(req.body);
    const savedSale = await sale.save();
    res.json(savedSale);
});

app.get("/sales/:id", async (req, res) => {
    const sale = await Sale.findById(req.params.id);
    res.json(sale);
});

app.put("/sales/:id", async (req, res) => {
    const updated = await Sale.findByIdAndUpdate(
        req.params.id,
        req.body,
        { new: true }
    );

    res.json(updated);
});

app.delete("/sales/:id", async (req, res) => {
    await Sale.findByIdAndDelete(req.params.id);
    res.json({ message: "Venta eliminada" });
});



const PORT = process.env.PORT;

app.listen(PORT, () => {
    console.log(`Servidor corriendo en puerto ${PORT}`);
});