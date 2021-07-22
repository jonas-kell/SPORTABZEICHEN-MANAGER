npm install
npm install -g license-checker
npm install -g yui-lint

license-checker --summary --out "license_summary.txt"
license-checker --json --out "license_detailed.json"

# generate Output.xml

echo '<?xml version="1.0" encoding="UTF-8"?><testsuites><testsuite id="NPM-CUSTOM-LICENSE-CHECKER">' > licenses.xml

for VARIABLE in "$@"
do
    echo '<testcase name="NPM check license ${VARIABLE}">' >> licenses.xml	
    license-checker --failOn "$VARIABLE";
    if [ $? != 0 ];
    then
        echo '<failure type="FAILURE">' >> licenses.xml
        license-checker --failOn "$VARIABLE" &>> licenses.xml
        echo '</failure>' >> licenses.xml
    else
        echo '<passed type="PASSED"></passed>' >> licenses.xml
    fi
    echo '</testcase>' >> licenses.xml
done

echo '</testsuite></testsuites>' >> licenses.xml