import requests
from bs4 import BeautifulSoup
import pprint
import json
import os

scraped_information = {}

gender_array = ["male", "female"]
years_to_parse = [
    {"lower_year": 6, "upper_year": 7, "tag": "6-7"},
    {"lower_year": 8, "upper_year": 9, "tag": "8-9"},
    {"lower_year": 10, "upper_year": 11, "tag": "10-11"},
    {"lower_year": 12, "upper_year": 13, "tag": "12-13"},
    {"lower_year": 14, "upper_year": 15, "tag": "14-15"},
    {"lower_year": 16, "upper_year": 17, "tag": "16-17"},
    {"lower_year": 18, "upper_year": 19, "tag": "18-19"},
    {"lower_year": 20, "upper_year": 24, "tag": "20-24"},
    {"lower_year": 25, "upper_year": 29, "tag": "25-29"},
    {"lower_year": 30, "upper_year": 34, "tag": "30-34"},
    {"lower_year": 35, "upper_year": 39, "tag": "35-39"},
    {"lower_year": 40, "upper_year": 44, "tag": "40-44"},
    {"lower_year": 45, "upper_year": 49, "tag": "45-49"},
    {"lower_year": 50, "upper_year": 54, "tag": "50-54"},
    {"lower_year": 55, "upper_year": 59, "tag": "55-59"},
    {"lower_year": 60, "upper_year": 64, "tag": "60-64"},
    {"lower_year": 65, "upper_year": 69, "tag": "65-69"},
    {"lower_year": 70, "upper_year": 74, "tag": "70-74"},
    {"lower_year": 75, "upper_year": 79, "tag": "75-79"},
    {"lower_year": 80, "upper_year": 84, "tag": "80-84"},
    {"lower_year": 85, "upper_year": 89, "tag": "85-89"},
    {"lower_year": 90, "upper_year": 200, "tag": "ab 90"},
]
current_year = 2021

for gender in gender_array:
    scraped_information[gender] = {}
    for year_object in years_to_parse:
        year_to_get = current_year - year_object["lower_year"]
        url = f"https://sportabzeichen.dosb.de/requirements?gender={gender}&year_of_birth={year_to_get}"

        print(url)

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
            rows = table_body.select(
                "tr:not(.as-mobile-only):not(.b-discipline-row--union)"
            )

            # create major category
            page_information[major_category] = {}

            for row in rows:
                row_store = {}

                discipline_name = row.select_one(
                    ".m-table-cell--requirement-label > a"
                ).string

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
                    # attempt to parse requirement_with_unit
                    exploded_requirememt = row_store["requirements"][category][
                        "requirement_with_unit"
                    ].split(" ")
                    if len(exploded_requirememt) == 2:
                        row_store["requirements"][category][
                            "requirement"
                        ] = exploded_requirememt[0]
                        row_store["requirements"][category][
                            "unit"
                        ] = exploded_requirememt[1]
                    requirement_description = requirement.select_one(".m-help")
                    if requirement_description:
                        row_store["requirements"][category][
                            "description"
                        ] = requirement_description.get("title")

                # store row info
                page_information[major_category][discipline_name] = row_store

        # store the information created by the page sweep
        scraped_information[gender][year_object["lower_year"]] = page_information
        scraped_information[gender][year_object["lower_year"]][
            "lower_year"
        ] = year_object["lower_year"]
        scraped_information[gender][year_object["lower_year"]][
            "upper_year"
        ] = year_object["upper_year"]
        scraped_information[gender][year_object["lower_year"]]["tag"] = year_object[
            "tag"
        ]

with open(
    os.path.join(os.path.dirname(__file__), "data.json"), "w", encoding="utf8"
) as outfile:
    json.dump(scraped_information, outfile, ensure_ascii=False)
