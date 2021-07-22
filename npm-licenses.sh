npm install
npm install -g license-checker
npm install -g yui-lint

license-checker --summary --out "license_summary.txt"
license-checker --json --out "license_detailed.json"

# Generate Output.xml

echo '<?xml version="1.0" encoding="UTF-8"?><testsuites><testsuite id="NPM-CUSTOM-LICENSE-CHECKER">' > licenses.xml

for VARIABLE in "$@"
do
    echo '<testcase name="NPM check license ' $VARIABLE '">' >> licenses.xml	

    # execute test and capture error stream in variable
    ERROR=$(license-checker --failOn "$VARIABLE" 2>&1 >/dev/null)

    if [ $? != 0 ];
    then
        echo '<failure type="FAILURE">' >> licenses.xml
        echo $ERROR >> licenses.xml
        echo '</failure>' >> licenses.xml
    else
        echo '<passed type="PASSED"></passed>' >> licenses.xml
    fi
    echo '</testcase>' >> licenses.xml
done

echo '</testsuite></testsuites>' >> licenses.xml