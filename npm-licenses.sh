npm install
npm install -g license-checker
npm install -g yui-lint

license-checker --summary --out "license_summary.txt"
license-checker --json --out "license_detailed.json"
license-checker --failOn "(MIT OR GPL-3.0)"