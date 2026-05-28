# Discipline export

There is a way to export a disciplines data (currenlty hardcoded for Standweitsprung) in teh meta table view.

The output can be converted to csv for further processing like this:

```cmd
jq -r '
  (.[0] | keys_unsorted) as $keys
  | $keys,
    (.[] | [.[ $keys[] ]])
  | @csv
' input.json > out.csv
```
