import { b_key } from "./config.json";
import { establishDBCon } from "./utils";
import axios from "axios";
import { queryCallback } from "mysql";

const outputCars = (results) => results.forEach(result => console.log(result["year"], result["make"], result["model"]));

const fetchFirstImageURL = (response: JSON) => response["value"][0]["thumbnailUrl"];

async function scrape(q: string) {
    let baseURL = "https://api.bing.microsoft.com/v7.0/images/search?q=";

    let { data } = await axios.get<JSON>(baseURL + q, { 
        method: "GET",
        headers: { 'Ocp-Apim-Subscription-Key': b_key }
    });

    return data;
}

//not type safe
async function executeScrapes(results, scrapeTerms: string[]) {
    let db = establishDBCon("localhost", 9906), delay = 500;

    for (let i = 0; i < scrapeTerms.length; ++i) {

        //Bing's free tier API allows for 3 requests a second so this should be doing 2
        setTimeout(async () => {

            let result = results[i], scrapeTerm = scrapeTerms[i];
            let image = fetchFirstImageURL(await scrape(scrapeTerm));

            db.query(
                "UPDATE Car " +
                `SET \`image_url\` = '${image}' ` +
                `WHERE \`year\`='${result["year"]}' AND \`make\`='${result["make"]}' AND \`model\`='${result["model"]}';`
            );

        }, delay * i);

    }

    setTimeout(() => {

        // disconnect from this contexts connection too
        db.end();
        console.log("Done!");

    }, delay * scrapeTerms.length + 2000);
}

const buildSearches = (results): string[] => results.map(result => encodeURIComponent(`${result["year"]} ${result["make"]} ${result["model"]}`))

//not type safe
const queryCars: queryCallback = (err, results) => executeScrapes(results, buildSearches(results));
//const queryCars: queryCallback = (err, results) => outputCars(results);

let db = establishDBCon("localhost", 9906);
db.query("SELECT \`year\`, \`make\`, \`model\` FROM \`Car\`;", queryCars);
db.end();
