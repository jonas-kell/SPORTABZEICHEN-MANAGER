<template>
    <ol v-if="list.length > 0" class="list-group">
        <li
            v-for="athlete in list"
            v-bind:key="athlete.id"
            class="list-group-item"
            @click="pushToCenter(athlete)"
            v-bind:style="{
                background:
                    centerAthlete != null && centerAthlete.id == athlete.id
                        ? '#969696'
                        : ''
            }"
        >
            <span>
                {{ athlete.name }}
                <span
                    v-if="currentYear == -1"
                    v-bind:style="{
                        color: 'grey'
                    }"
                >
                    ({{ athlete.year }})
                </span>
                <span
                    v-else
                    v-bind:style="{
                        color: athlete.gender == 'male' ? 'blue' : 'red'
                    }"
                >
                    {{ __("general.gender_short_" + athlete.gender) }} |
                    {{ athlete.requirements_tag }}
                </span>
            </span>
            <medal-display :medalScores="athlete.medal_scores"></medal-display>
            <span
                class="float-right rightarrow"
                @click.stop="pushToCenter(athlete)"
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
import { mapGetters } from "vuex";
import FavouriteStarButton from "./FavouriteStarButton.vue";
import MedalDisplay from "./MedalDisplay.vue";

export default {
    components: { FavouriteStarButton, MedalDisplay },
    props: { list: Array, currentYear: Number },
    methods: {
        pushToCenter: function(athlete) {
            this.$store.dispatch("fetchAthlete", athlete.id);
        }
    },
    computed: {
        ...mapGetters({ centerAthlete: "athlete" })
    }
};
</script>
