from flask import *
import pymysql
import pymysql
from flask import render_template,redirect

pymysql.install_as_MySQLdb()

from flask_mysqldb import MySQL
from flask import Flask, render_template, request, redirect, session
from flask_mysqldb import MySQL
from werkzeug.utils import secure_filename
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.lib import colors
from flask import *

import os
import uuid
import requests

app = Flask(__name__)

app.secret_key = "farminven_secret_key"


# ==========================
# MYSQL CONFIG
# MAC + XAMPP FIX
# ==========================

app.config["MYSQL_HOST"] = "127.0.0.1"
app.config["MYSQL_PORT"] = 3306
app.config["MYSQL_USER"] = "root"
app.config["MYSQL_PASSWORD"] = ""
app.config["MYSQL_DB"] = "farminven_db"

mysql = MySQL(app)

UPLOAD_FOLDER="static/uploads"

app.config["UPLOAD_FOLDER"]=UPLOAD_FOLDER
app.config["TEMPLATES_AUTO_RELOAD"] = True
app.config["DEBUG"] = True

# ==========================
# HOME
# ==========================

@app.route("/")
def home():

    return redirect("/login")


# ==========================
# DASHBOARD
# ==========================

@app.route("/dashboard")
def dashboard():

    cur=mysql.connection.cursor()

    # TOTAL PRODUCTS
    cur.execute(
        "SELECT COUNT(*) FROM farm_products"
    )

    totalProducts= cur.fetchone()[0]

    # INVENTORY LOGS
    cur.execute(
        "SELECT COUNT(*) FROM inventory_logs"
    )

    totalLogs= cur.fetchone()[0]

    # LOW STOCK ALERTS
    cur.execute(
        """
        SELECT COUNT(*)
        FROM farm_products
        WHERE stock_level <= 5
        """
    )

    lowStock= cur.fetchone()[0]

    cur.close()

    return render_template(

        "dashboard.html",

        totalProducts=totalProducts,

        totalLogs=totalLogs,

        lowStock=lowStock

    )
    
# ==========================
# REPORTS DASHBOARD + CHARTS
# ==========================

@app.route("/reports")
def reports():

    if "user_id" not in session:
        return redirect("/login")

    cur=mysql.connection.cursor()

    # total products
    cur.execute(
    "SELECT COUNT(*) FROM farm_products"
    )

    totalProducts=cur.fetchone()[0]


    # total stock
    cur.execute(
    "SELECT SUM(stock_level) FROM farm_products"
    )

    totalStock=cur.fetchone()[0]

    if totalStock is None:
        totalStock=0


    # low stock alerts
    cur.execute(
    """
    SELECT *
    FROM farm_products
    WHERE stock_level<=10
    """
    )

    lowStock=cur.fetchall()


    # chart data
    cur.execute(
    """
    SELECT
    product_name,
    stock_level

    FROM farm_products
    """
    )

    chartData=cur.fetchall()

    names=[]
    stocks=[]

    for item in chartData:

        names.append(item[0])
        stocks.append(item[1])

    cur.close()

    return render_template(

        "reports.html",

        totalProducts=totalProducts,
        totalStock=totalStock,
        lowStock=lowStock,
        names=names,
        stocks=stocks

    )

# ==========================
# PRODUCTS PAGE
# ==========================

@app.route("/products")
def products():

    try:

        conn = pymysql.connect(
            host="localhost",
            user="root",
            password="",
            database="agrimart_db"
        )

        cur = conn.cursor()

        cur.execute("""
            SELECT
            id,
            product_name,
            product_uuid,
            stock_level,
            image
            FROM products
        """)

        products = cur.fetchall()

        cur.close()
        conn.close()

        return render_template(
            "products.html",
            products=products
        )

    except Exception as e:

        return f"ERROR: {str(e)}"
        
# ==========================
# ADD PRODUCT
# ==========================

@app.route("/addProduct")
def addProduct():

    return render_template(
    "addProduct.html"
    )
    
# ==========================
# EDIT PRODUCT
# ==========================

@app.route("/editProduct/<id>")
def editProduct(id):

    cur=mysql.connection.cursor()

    cur.execute(

    """

    SELECT *

    FROM farm_products

    WHERE id=%s

    """,

    [id]

    )

    product=cur.fetchone()

    cur.close()

    return render_template(

    "editProduct.html",

    product=product

    )

# ==========================
# DELETE PRODUCT
# ==========================

@app.route("/deleteProduct/<int:id>")
def deleteProduct(id):

    cur=mysql.connection.cursor()

    cur.execute(
    """
    DELETE
    FROM farm_products
    WHERE id=%s
    """,
    (id,)
    )

    mysql.connection.commit()

    cur.close()

    return redirect(
    "/products"
    )

# ==========================
# INVENTORY LOGS
# ==========================

@app.route("/logs")
def logs():

    cur=mysql.connection.cursor()

    cur.execute(
    """
    SELECT *
    FROM inventory_logs

    ORDER BY id DESC
    """
    )

    logs=cur.fetchall()

    return render_template(
    "logs.html",
    logs=logs
    )


# ==========================
# EXPORT PDF REPORT
# ==========================

@app.route("/exportReport")
def exportReport():

    if "user_id" not in session:
        return redirect("/login")

    cur=mysql.connection.cursor()

    cur.execute(
    """
    SELECT
    product_name,
    stock_level
    FROM farm_products
    """
    )

    products=cur.fetchall()

    cur.close()


    filename="inventory_report.pdf"

    pdf=SimpleDocTemplate(filename)

    data=[

        ["Product","Stock"]

    ]


    for p in products:

        data.append(

            [

            p[0],
            p[1]

            ]

        )


    table=Table(data)

    table.setStyle(

    TableStyle([

    ('BACKGROUND',(0,0),(-1,0),colors.green),

    ('TEXTCOLOR',(0,0),(-1,0),colors.white),

    ('GRID',(0,0),(-1,-1),1,colors.black)

    ])

    )


    elements=[]

    elements.append(table)

    pdf.build(elements)


    return redirect("/reports")


def logout():

    session.clear()

    return redirect(
    "/login"
    )


# ==========================
# SAVE PRODUCT
# ==========================

@app.route("/save-product", methods=["POST"])
def saveProduct():

    import uuid
    import requests
    import os

    product_name = request.form["product_name"]

    stock_level = request.form["stock_level"]

    image = request.files["product_image"]

    filename = secure_filename(image.filename)

    upload_path = os.path.join(
        "static",
        "uploads",
        filename
    )

    image.save(upload_path)


    product_uuid = str(
        uuid.uuid4()
    )


    cur = mysql.connection.cursor()

    cur.execute(

    """

    INSERT INTO farm_products(

    product_name,
    product_uuid,
    stock_level,
    product_image

    )

    VALUES(%s,%s,%s,%s)

    """,

    (

    product_name,
    product_uuid,
    stock_level,
    filename

    )

    )

    mysql.connection.commit()


    # AUTO SYNC TO AGRIMART

    data={

    "product_uuid":product_uuid,
    "product_name":product_name,
    "stock_level":stock_level,
    "product_image":filename

    }

    try:

        response=requests.post(

        "http://localhost:3001/api/products/sync",

        json=data

        )

        print(response.text)

    except Exception as e:

        print(e)


    cur.close()


    return redirect("/products")

@app.route("/updateProduct/<int:id>",methods=["GET","POST"])
def updateProduct(id):

    cur=mysql.connection.cursor()

    if request.method=="POST":

        product_name=request.form["product_name"]
        stock_level=request.form["stock_level"]

        cur.execute(

        """
        UPDATE farm_products
        SET product_name=%s,
        stock_level=%s
        WHERE id=%s
        """,

        (
        product_name,
        stock_level,
        id
        )

        )

        mysql.connection.commit()

        # AUTO UPDATE AGRIMART
        cur.execute(
        "SELECT product_uuid,stock_level FROM farm_products WHERE id=%s",
        (id,)
        )

        product=cur.fetchone()

        import requests

        try:

            requests.post(

            "http://localhost:3001/api/update-stock",

            json={

            "product_uuid":product[0],
            "stock_level":product[1]

            }

            )

        except:
            print("API Sync Failed")


        return redirect("/products")


    cur.execute(
    "SELECT * FROM farm_products WHERE id=%s",
    (id,)
    )

    product=cur.fetchone()

    return render_template(
    "editProduct.html",
    product=product
    )

            
@app.route("/orders")
def adminOrders():

    conn=pymysql.connect(
        host="localhost",
        user="root",
        password="",
        database="agrimart_db"
    )

    cur=conn.cursor()

    cur.execute("""
        SELECT
        order_id,
        customer_id,
        total_amount,
        status
        FROM orders
        ORDER BY order_id DESC
    """)

    orders=cur.fetchall()

    cur.close()
    conn.close()

    return render_template(
        "orders.html",
        orders=orders
    )

@app.route("/approveOrder/<int:id>")
def approveOrder(id):

    conn=pymysql.connect(
        host="localhost",
        user="root",
        password="",
        database="agrimart_db"
    )

    cur=conn.cursor()

    cur.execute(
        "UPDATE orders SET status='Completed' WHERE order_id=%s",
        (id,)
    )

    conn.commit()

    cur.close()
    conn.close()

    return redirect("/orders")


@app.route("/declineOrder/<int:id>")
def declineOrder(id):

    conn=pymysql.connect(
        host="localhost",
        user="root",
        password="",
        database="agrimart_db"
    )

    cur=conn.cursor()

    cur.execute(
        "UPDATE orders SET status='Cancelled' WHERE order_id=%s",
        (id,)
    )

    conn.commit()

    cur.close()
    conn.close()

    return redirect("/orders")



@app.route("/sync")
def sync():

    try:

        farm = pymysql.connect(
            host="localhost",
            user="root",
            password="",
            database="farminven_db"
        )

        agri = pymysql.connect(
            host="localhost",
            user="root",
            password="",
            database="agrimart_db"
        )

        farmCur=farm.cursor()
        agriCur=agri.cursor()

        agriCur.execute("""

        SELECT
        product_uuid,
        sku_code,
        product_name,
        stock_level,
        image

        FROM products

        """)

        products=agriCur.fetchall()


        for p in products:

            farmCur.execute("""

            SELECT *
            FROM products
            WHERE product_uuid=%s

            """,(p[0],))

            exists=farmCur.fetchone()


            if not exists:

                farmCur.execute("""

                INSERT INTO products
                (
                product_uuid,
                sku_code,
                product_name,
                stock_level,
                image
                )

                VALUES
                (%s,%s,%s,%s,%s)

                """,p)


        farm.commit()

        farmCur.close()
        agriCur.close()

        farm.close()
        agri.close()

        return redirect("/products")


    except Exception as e:

        return f"SYNC ERROR: {str(e)}"
        
# ==========================
# START APP
# ==========================

if __name__=="__main__":

    app.run(
        debug=True
    )