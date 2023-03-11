import { establishDBCon, genRandFloat } from "./utils";
import { queryCallback } from "mysql";

//const typeMyArrayForMe: queryCallback = (err, results) => console.log("\"" + results.map(row => row["make"]).join("\" , \"") + "\"");

const updatePrices: queryCallback = (err, results: any[]) => {
    let db = establishDBCon("localhost", 9906);

    //UPDATE Car SET price=123.23 WHERE cid=1;
    results.forEach(row => db.query(`UPDATE Car SET price=${genRandFloat(200, 650, 2)} WHERE cid=${row['cid']};`));

    db.end();
}

let db = establishDBCon("localhost", 9906);

//db.query("SELECT DISTINCT make FROM `Car`;", typeMyArrayForMe);
db.query("SELECT cid FROM Car", updatePrices);

db.end();
