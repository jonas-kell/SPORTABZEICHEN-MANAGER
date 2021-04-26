<template>
    <ol v-if="list.length > 0" class="list-group">
        <li
            v-for="athlete in list"
            v-bind:key="athlete.id"
            class="list-group-item"
        >
            <span
                >{{ athlete.name }}
                <span
                    v-bind:style="{
                        color: athlete.year !== currentYear ? 'red' : 'green'
                    }"
                    >({{ athlete.year }})</span
                ></span
            >
            <span
                class="float-right rightarrow"
                @click="pushToCenter(athlete)"
            />
            <span
                class="float-right favourite_star"
                v-bind:class="athlete.favourite ? 'active' : 'inactive'"
                @click="toggleFavourite(athlete)"
            />
        </li>
    </ol>
    <ol v-else class="list-group">
        <li class="list-group-item">
            {{ __("general.no_results") }}
        </li>
    </ol>
</template>

<script>
export default {
    mounted() {},
    props: { list: Array, currentYear: Number },
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

            // update the star info in the search
            this.$store.dispatch("requestSearchUpdate");

            // update graphic immediately
            athlete.favourite = !startState;
        },
        pushToCenter: function(athlete) {
            this.$store.dispatch("fetchAthlete", athlete.id);
        }
    }
};
</script>
