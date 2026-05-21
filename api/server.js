console.log("SERVER STARTED");

const express = require("express");
const db = require("./config/db");

const app = express();

app.use(express.json());

const PORT = 3001;

// =======================
// ROOT
// =======================

app.get("/", (req, res) => {
  res.send("API Working");
});

// =======================
// GET ALL PRODUCTS
// AgriMart products
// =======================

app.get("/api/products", (req, res) => {
  db.query(
    `SELECT *
        FROM products`,

    (err, result) => {
      if (err) {
        console.log(err);

        return res.status(500).json(err);
      }

      res.json(result);
    },
  );
});

// =======================
// GET SINGLE PRODUCT
// =======================

app.get("/api/product/:uuid", (req, res) => {
  const uuid = req.params.uuid;

  db.query(
    `SELECT *
        FROM products
        WHERE product_uuid=?`,

    [uuid],

    (err, result) => {
      if (err) {
        console.log(err);

        return res.status(500).json(err);
      }

      if (result.length === 0) {
        return res.json({
          success: false,
        });
      }

      res.json(result[0]);
    },
  );
});

// =======================
// UPDATE STOCK
// called by FarmInven
// =======================

app.post("/api/update-stock",(req,res)=>{

const {

product_uuid,
stock_level

}=req.body;


db.query(

`UPDATE products
SET stock_level=?,
last_synced=NOW()

WHERE product_uuid=?`,

[
stock_level,
product_uuid
],

(err)=>{

if(err){

console.log(err);

return res.json(err);

}

res.send("Stock Updated");

}

);

});

// =======================
// CREATE ORDER
// =======================

app.post("/api/orders", (req, res) => {
  const { customer_id, product_uuid, quantity } = req.body;

  db.query(
    "SELECT * FROM products WHERE product_uuid=?",

    [product_uuid],

    (err, result) => {
      if (err) {
        return res.status(500).json(err);
      }

      if (result.length === 0) {
        return res.json({
          success: false,
          message: "Product not found",
        });
      }

      const product = result[0];

      if (quantity > product.stock_level) {
        return res.json({
          success: false,
          message: "Insufficient stock",
        });
      }

      const subtotal = quantity * product.price;

      db.query(
        `INSERT INTO orders(

customer_id,
total_amount,
status

)

VALUES(

?,
?,
'Pending'

)`,

        [customer_id, subtotal],

        (err, orderResult) => {
          if (err) {
            return res.status(500).json(err);
          }

          const orderId = orderResult.insertId;

          db.query(
            `INSERT INTO order_items(

order_id,
product_uuid,
quantity,
price,
subtotal

)

VALUES(

?,?,?,?,?

)`,

            [orderId, product_uuid, quantity, product.price, subtotal],

            (err) => {
              if (err) {
                return res.status(500).json(err);
              }

              res.json({
                success: true,
                message: "Order created",
                order_id: orderId,
                status: "Pending",
              });
            },
          );
        },
      );
    },
  );
});

// =======================
// COMPLETE ORDER
// reduce stock
// =======================

app.post("/api/products/sync", (req, res) => {
  console.log("Incoming sync:");

  console.log(req.body);

  const { product_uuid, product_name, stock_level, product_image } = req.body;

  db.query(
    "SELECT * FROM products WHERE product_uuid=?",

    [product_uuid],

    (err, result) => {
      if (err) {
        console.log("SELECT ERROR:");

        console.log(err);

        return res.status(500).json(err);
      }

      console.log("FOUND:");

      console.log(result.length);

      if (result.length > 0) {
        db.query(
          `UPDATE products

SET
product_name=?,
stock_level=?,
image=?,
last_synced=NOW()

WHERE product_uuid=?`,

          [product_name, stock_level, product_image, product_uuid],

          (err) => {
            if (err) {
              console.log("UPDATE ERROR:");

              console.log(err);

              return res.status(500).json(err);
            }

            console.log("UPDATED");

            res.json({
              success: true,
            });
          },
        );
      } else {
        const sku = "SKU" + Math.floor(1000 + Math.random() * 9000);

        db.query(
          `INSERT INTO products(

product_uuid,
sku_code,
product_name,
stock_level,
price,
seller_id,
image,
last_synced

)

VALUES(

?,
?,
?,
?,
50,
1,
?,
NOW()

)`,

          [product_uuid, sku, product_name, stock_level, product_image],

          (err) => {
            if (err) {
              console.log("INSERT ERROR:");

              console.log(err);

              return res.status(500).json(err);
            }

            console.log("CREATED");

            res.json({
              success: true,
            });
          },
        );
      }
    },
  );
});

// =======================
// CREATE OR UPDATE PRODUCT
// FROM FARMINVEN
// =======================

// =======================
// AUTO CREATE OR UPDATE
// FROM FARMINVEN
// =======================

app.post("/api/products/sync", (req, res) => {
  console.log(req.body);

  const { product_uuid, product_name, stock_level, product_image } = req.body;

  db.query(
    "SELECT * FROM products WHERE product_uuid=?",

    [product_uuid],

    (err, result) => {
      if (err) {
        console.log(err);

        return res.status(500).json(err);
      }

      if (result.length > 0) {
        db.query(
          `UPDATE products

SET

product_name=?,
stock_level=?,
image=?,
last_synced=NOW()

WHERE product_uuid=?`,

          [product_name, stock_level, product_image, product_uuid],

          (err) => {
            if (err) {
              console.log(err);

              return res.status(500).json(err);
            }

            console.log("UPDATED");

            res.json({
              success: true,
              message: "updated",
            });
          },
        );
      } else {
        const sku = "SKU" + Math.floor(1000 + Math.random() * 9000);

        db.query(
          `INSERT INTO products(

product_uuid,
sku_code,
product_name,
stock_level,
price,
seller_id,
image,
last_synced

)

VALUES(

?,
?,
?,
?,
?,
?,
?,
NOW()

)`,

          [product_uuid, sku, product_name, stock_level, 50, 1, product_image],

          (err) => {
            if (err) {
              console.log("INSERT FAILED");

              console.log(err);

              return res.status(500).json(err);
            }

            console.log("CREATED");

            res.json({
              success: true,
              message: "created",
            });
          },
        );
      }
    },
  );
});


app.get("/api/testinsert",(req,res)=>{

db.query(

`INSERT INTO products(

product_uuid,
sku_code,
product_name,
stock_level,
price,
seller_id,
image,
last_synced

)

VALUES(

'TEST-UUID',
'SKU1111',
'TEST PRODUCT',
10,
50,
1,
'test.png',
NOW()

)`,

(err)=>{

if(err){

console.log(err);

return res.send(err);

}

res.send("INSERT SUCCESS");

}

);

});

app.listen(PORT, () => {
  console.log("Server running on port 3001");
});
