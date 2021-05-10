module.exports = {
    methods: {
        /**
         * replace spaces with non-breaking spaces
         */
        nbsp_encode(string) {
            return string.replace(" ", "\xa0");
        },
        /**
         * replace spaces with non-breaking spaces
         */
        build_tooltip(requirement, description) {
            if (description == undefined) {
                return requirement;
            } else {
                return requirement + ": " + description;
            }
        }
    }
};
