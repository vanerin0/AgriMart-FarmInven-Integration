from flask import Flask, render_template, request, redirect, session
from flask_mysqldb import MySQL
from werkzeug.utils import secure_filename
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.lib import colors
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


# ==========================
# HOME
# ==========================

@app.route("/")
def home():

    return redirect("/login")


# ==========================
# REGISTER
# ==========================

@app.route(
"/register",
methods=["GET","POST"]
)
def register():

    if request.method=="POST":

        fullname=request.form["fullname"]

        email=request.form["email"]

        password=request.form["password"]

        role=request.form["role"]


        cur=mysql.connection.cursor()


        cur.execute(
        """
        SELECT *
        FROM users
        WHERE email=%s
        """,
        (email,)
        )


        existing=cur.fetchone()


        if existing:

            cur.close()

            return "Email already exists"


        cur.execute(
        """
        INSERT INTO users
        (
        fullname,
        email,
        password,
        role
        )

        VALUES
        (
        %s,
        %s,
        %s,
        %s
        )
        """,

        (
        fullname,
        email,
        password,
        role
        )

        )


        mysql.connection.commit()

        cur.close()

        return redirect(
        "/login"
        )


    return render_template(
    "register.html"
    )


# ==========================
# LOGIN
# ==========================

@app.route(
"/login",
methods=["GET","POST"]
)

def login():

    if request.method=="POST":

        email=request.form["email"]

        password=request.form["password"]


        cur=mysql.connection.cursor()


        cur.execute(
        """
        SELECT *
        FROM users

        WHERE email=%s
        AND password=%s
        """,

        (
        email,
        password
        )

        )


        user=cur.fetchone()


        cur.close()


        if user:

            session["user_id"]=user[0]

            session["fullname"]=user[1]

            session["role"]=user[4]

            return redirect(
            "/dashboard"
            )


        return "Invalid Login"


    return render_template(
    "login.html"
    )


# ==========================
# DASHBOARD
# ==========================

@app.route("/dashboard")
def dashboard():

    if "user_id" not in session:
        return redirect("/login")

    cur = mysql.connection.cursor()

    # total products
    cur.execute(
        "SELECT COUNT(*) FROM farm_products"
    )

    totalProducts = cur.fetchone()[0]


    # total stock
    cur.execute(
        "SELECT SUM(stock_level) FROM farm_products"
    )

    totalStock = cur.fetchone()[0]

    if totalStock is None:
        totalStock = 0


    # low stock alerts
    cur.execute(
        """
        SELECT *
        FROM farm_products
        WHERE stock_level <= 10
        """
    )

    lowStock = cur.fetchall()


    # recent logs
    cur.execute(
        """
        SELECT *
        FROM inventory_logs
        ORDER BY id DESC
        LIMIT 5
        """
    )

    recentLogs = cur.fetchall()

    cur.close()

    return render_template(

        "dashboard.html",

        totalProducts=totalProducts,
        totalStock=totalStock,
        lowStock=lowStock,
        recentLogs=recentLogs

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

    if "user_id" not in session:

        return redirect("/login")

    cur=mysql.connection.cursor()

    cur.execute(
    """
    SELECT *
    FROM farm_products
    ORDER BY id DESC
    """
    )

    products=cur.fetchall()

    cur.close()

    return render_template(
    "products.html",
    products=products
    )


# ==========================
# ADD PRODUCT
# ==========================

@app.route(
"/addProduct",
methods=["GET","POST"]
)

def addProduct():

    if "user_id" not in session:

        return redirect("/login")


    if request.method=="POST":

        product_name=request.form["product_name"]

        stock_level=request.form["stock_level"]

        image=request.files["product_image"]


        filename=""


        if image:

            filename=secure_filename(
            image.filename
            )

            image.save(

            os.path.join(
            app.config["UPLOAD_FOLDER"],
            filename
            )

            )


        product_uuid=str(
        uuid.uuid4()
        )


        cur=mysql.connection.cursor()


        cur.execute(

        """
        INSERT INTO farm_products
        (
        product_name,
        product_uuid,
        stock_level,
        product_image
        )

        VALUES
        (%s,%s,%s,%s)

        """,

        (
        product_name,
        product_uuid,
        stock_level,
        filename
        )

        )

        mysql.connection.commit()

        cur.close()

        return redirect(
        "/products"
        )


    return render_template(
    "addProduct.html"
    )

# ==========================
# EDIT PRODUCT
# ==========================

@app.route(
"/editProduct/<int:id>",
methods=["GET","POST"]
)

def editProduct(id):

    if "user_id" not in session:

        return redirect("/login")


    cur=mysql.connection.cursor()


    if request.method=="POST":

        name=request.form["product_name"]

        stock=request.form["stock_level"]


        cur.execute(
        """
        SELECT *
        FROM farm_products
        WHERE id=%s
        """,
        (id,)
        )

        old=cur.fetchone()


        oldStock=old[3]

        uuid=old[2]


        cur.execute(
        """
        UPDATE farm_products

        SET product_name=%s,
        stock_level=%s

        WHERE id=%s
        """,

        (
        name,
        stock,
        id
        )
        )


        cur.execute(
        """
        INSERT INTO inventory_logs
        (
        product_uuid,
        product_name,
        previous_stock,
        new_stock,
        action
        )

        VALUES
        (%s,%s,%s,%s,%s)
        """,

        (
        uuid,
        name,
        oldStock,
        stock,
        "Updated"
        )

        )

        mysql.connection.commit()

        cur.close()

        return redirect("/products")


    cur.execute(
    """
    SELECT *
    FROM farm_products
    WHERE id=%s
    """,
    (id,)
    )

    product=cur.fetchone()

    return render_template(
    "editProduct.html",
    p=product
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

# ==========================
# LOGOUT
# ==========================

@app.route("/logout")
def logout():

    session.clear()

    return redirect(
    "/login"
    )


# ==========================
# START APP
# ==========================

if __name__=="__main__":

    app.run(
        debug=True
    )