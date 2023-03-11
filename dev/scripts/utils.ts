import { DB_USER, DB_PASSWORD } from "./config.json";
import { createConnection } from "mysql";
/**
 * Returns a random number between min and max, inclusive.
 * @param { number } min A smaller number
 * @param { number } max A bigger number
 */
export function randNFrom(min: number, max: number) {
    if (min > max) {
        [ min, max ] = [ max, min ];
    }
    return Math.floor(Math.random() * (max - min + 1) + min);
}
/**
 * Returns a random number between 0 and n, exclusive.
 * @param { number } n A number
 */
export const randNTo = (n: number) => Math.floor(Math.random() * n);

//https://stackoverflow.com/a/45736131
export function genRandFloat(min: number, max: number, decimalPlaces: number) {  
    let rand = Math.random() < 0.5 ? ((1 - Math.random()) * (max - min) + min) : (Math.random() * (max - min) + min); //could be min or max or anything in between
    let power = Math.pow(10, decimalPlaces);
    return Math.floor(rand * power) / power;
}

export const establishDBCon = (host: string, p: number) => createConnection({
    host: host,
    port: p,
    user: DB_USER,
    password: DB_PASSWORD,
    database: DB_USER,
});
