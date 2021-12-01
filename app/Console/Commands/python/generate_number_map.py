import json
import os

with open(
    os.path.join(os.path.dirname(__file__), "data.json"),
    "r",
    encoding="utf8",
) as infile:
    data = json.load(infile)

compiledInformation = {}

categories = ["coordination", "endurance", "speed", "strength"]
for category in categories:
    compiledInformation[category] = {}

for gender in data:
    for age in data[gender]:
        for category in categories:
            for discipline in data[gender][age][category]:
                compiledInformation[category][discipline] = 0

with open(
    os.path.join(os.path.dirname(__file__), "number_map_template.json"),
    "w",
    encoding="utf8",
) as outfile:
    json.dump(compiledInformation, outfile, ensure_ascii=False, sort_keys=True)
