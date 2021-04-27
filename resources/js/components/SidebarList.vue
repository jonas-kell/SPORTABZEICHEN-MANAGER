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
            <favourite-star-button v-bind="{ athlete: athlete }" />
        </li>
    </ol>
    <ol v-else class="list-group">
        <li class="list-group-item">
            {{ __("general.no_results") }}
        </li>
    </ol>
</template>

<script>
import FavouriteStarButton from "./FavouriteStarButton.vue";
export default {
    components: { FavouriteStarButton },
    props: { list: Array, currentYear: Number },
    methods: {
        pushToCenter: function(athlete) {
            this.$store.dispatch("fetchAthlete", athlete.id);
        }
    }
};
</script>
