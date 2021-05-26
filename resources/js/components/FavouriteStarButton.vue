<template>
    <span
        class="float-right favourite_star"
        v-bind:class="athlete.favourite ? 'active' : 'inactive'"
        @click.stop="toggleFavourite(athlete)"
    />
</template>

<script>
export default {
    props: { athlete: Object },
    methods: {
        toggleFavourite: function(athlete) {
            let startState = athlete.favourite;

            if (startState) {
                // drop favourite
                this.$store.dispatch("dropFavourite", athlete);
            } else {
                // associate favourite
                this.$store.dispatch("addFavourite", athlete);
            }

            // update own state immediately
            athlete.favourite = !startState;
        }
    }
};
</script>
