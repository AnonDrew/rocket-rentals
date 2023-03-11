import { api_key } from "./config.json";
import { establishDBCon, randNFrom, randNTo } from "./utils";
import axios from "axios";
import { Connection } from "mysql";

type Drive = "fwd" | "rwd" | "awd" | "4wd";
type Fuel = "gas" | "diesel" | "electricity";
type Transmission = "a" | "m";
type Car = {
    city_mpg: number,
    class: string,
    combination_mpg: number,
    cylinders: number,
    displacement: number,
    drive: Drive,
    fuel_type: Fuel,
    highway_mpg: number,
    make: string,
    model: string,
    transmission: Transmission,
    year: number,
};
type CarQueryParameters = {
    drive: Drive,
    fuel_type: Fuel,
    transmission: Transmission,
    year: number,
    limit: number,
};

function setupRandQueryParams() {
    let drives: Drive[] = [ "fwd", "rwd", "awd", "4wd" ];
    let fuels: Fuel[] = [ "gas", "diesel", "electricity" ];
    let transmissions: Transmission[] = [ "a", "m" ];
    let [ year, limit ] = [ randNFrom(1993, 2022), randNFrom(1, 12) ];

    let queryParams: CarQueryParameters = {
        "drive": drives[randNTo(drives.length)],
        "fuel_type": fuels[randNTo(fuels.length)],
        "transmission": transmissions[randNTo(transmissions.length)],
        "year": year,
        "limit": limit,
    };

    return queryParams;
}

function setupQueryString(queryParams: CarQueryParameters) {
    let queryString = "?";

    for (let key in queryParams) {
        queryString += key + "=" + queryParams[key] + "&";
    }

    return queryString.slice(0, -1);
}

const getAllMakesAndModels = (cars: Car[]) => cars.map(car => `${car["make"]} ${car["model"]}`);

async function fetchCars(n: number) {
    let cars: Car[] = [], baseURL = "https://api.api-ninjas.com/v1/cars";

    while (cars.length <= n) {
        let { data, status } = await axios.get<Car[]>(baseURL + setupQueryString(setupRandQueryParams()), {
            method: "GET",
            headers: { 
                "X-Api-Key": api_key,
            },
        });

        if (status == 200) {
            data.forEach(car => {
                if (!getAllMakesAndModels(cars).includes(car["make"] + " " + car["model"])) {
                    cars.push(car);
                }
            });
        }
    }

    return cars;
}

function insertCars(con: Connection, cars: Car[]) {
    cars.forEach(car => {
        con.query(
            "INSERT IGNORE INTO Car " +
            "(" + Object.keys(car).join(", ") + ") " +
            "VALUES " +
            "(\"" + Object.values(car).join("\", \"") + "\");"
        );
    });
}

async function main() {
    const connection: Connection = establishDBCon("localhost", 9906);
    insertCars(connection, await fetchCars(125));
    connection.end();
}

main();
