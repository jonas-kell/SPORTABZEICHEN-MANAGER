import requests
from bs4 import BeautifulSoup
import pprint
import json
import os

url = "https://sportabzeichen.dosb.de/requirements?gender=female&year_of_birth=1900"

page = requests.get(url)
soup = BeautifulSoup(page.content, "html.parser")

# parse a discipline tables
table_selectors = {
    "endurance": "as-endurance",
    "strength": "as-strength",
    "speed": "as-agility",
    "coordination": "as-coordination",
}

page_information = {}

for major_category, selector in table_selectors.items():

    # major category selectors
    table = soup.find("table", class_=selector)
    table_body = table.find("tbody")
    rows = table_body.select("tr:not(.as-mobile-only):not(.b-discipline-row--union)")

    # create major category
    page_information[major_category] = {}

    for row in rows:
        row_store = {}

        discipline_name = row.select_one(".m-table-cell--requirement-label > a").string

        description_element = row.select_one(
            ".m-table-cell--requirement-label > div.m-help"
        )
        if description_element:
            row_store["description"] = description_element.get("title")
        else:
            row_store["description"] = ""

        bronze_div = row.select_one(".as-level-1 > .requirement-with-unit")
        silver_div = row.select_one(".as-level-2 > .requirement-with-unit")
        gold_div = row.select_one(".as-level-3 > .requirement-with-unit")

        # discipline requirements
        row_store["requirements"] = {}
        for category, requirement in {
            "bronze": bronze_div,
            "silver": silver_div,
            "gold": gold_div,
        }.items():
            row_store["requirements"][category] = {}
            row_store["requirements"][category][
                "requirement_with_unit"
            ] = requirement.text.replace("\n", "")
            requirement_description = requirement.select_one(".m-help")
            if requirement_description:
                row_store["requirements"][category][
                    "description"
                ] = requirement_description.get("title")

        # store row info
        page_information[major_category][discipline_name] = row_store

with open(
    os.path.join(os.path.dirname(__file__), "data.json"), "w", encoding="utf8"
) as outfile:
    json.dump(page_information, outfile, ensure_ascii=False)
