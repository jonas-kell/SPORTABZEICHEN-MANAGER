<template>
    <div class="row no-gutters">
        <table class="table table-sm table-bordered">
            <tr>
                <th>{{ __("general.name") }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr
                v-for="athlete in favourites"
                v-bind:key="athlete.id"
            >
                <td>
                    <strong>
                        {{ athlete.name }}
                    </strong>
                </td>
                <td
                    v-bind:style="{
                        color: athlete.gender == 'male' ? 'blue' : 'red'
                    }"
                >
                    {{ __("general.gender_short_" + athlete.gender) }} |
                    {{ athlete.requirements_tag }}
                </td>
                <td>
                    <medal-display-table :medalScores="athlete.medal_scores" />
                </td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
            </tr>
            <tr
                v-for="n in 10"
                v-bind:key="n"
            >
                <td></td>
                <td></td>
                <td>
                    <strong>
                        {{__("general.create_new")}}
                    </strong>
                </td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
                <td style="width:10%"></td>
            </tr>
        </table>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import MedalDisplayTable from "../Includes/MedalDisplayTable.vue";

export default {
    components: { MedalDisplayTable },
    created() {
        this.ALL_YEARS_STRING = "Alle";
    },
    mounted() {
        this.$store.dispatch("fetchFavourites");
    },
    computed: {
        ...mapGetters(["favourites"])
    }
};
</script>
