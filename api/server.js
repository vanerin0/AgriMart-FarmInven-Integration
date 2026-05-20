console.log("SERVER STARTED");

const express=require("express");

const db=require("./config/db");

const app=express();

app.use(express.json());

const PORT=3001;

app.get("/",(req,res)=>{

res.send("API working");

});

app.listen(PORT,()=>{

console.log(
"Server running on port 3001"
);

});