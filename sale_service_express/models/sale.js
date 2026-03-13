const mongoose = require("mongoose");

const saleSchema = new mongoose.Schema({
    quantity: {
        type: Number,
        required: true
    },

    total: {
        type: Number,
        required: true
    },

    product_id: {
        type: Number,
        required: true
    }
});

module.exports = mongoose.model("Sale", saleSchema);