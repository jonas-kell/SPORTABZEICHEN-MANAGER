<style scoped>
.stronger-table-border > tr > td,
.stronger-table-border > tr > th {
    border: 2px solid rgba(41, 41, 41, 0.51);
}
</style>

<template>
    <div class="row no-gutters ml-3 mr-3">
        <p class="col-12">
            <span class="btn btn-sm btn-edit float-right" @click="requestPDF">
                PDF erzeugen
            </span>
        </p>
        <table
            class="table table-sm table-bordered stronger-table-border"
            id="render-pdf"
        >
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
            <tr v-for="athlete in favourites" v-bind:key="athlete.id">
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
            <tr v-for="n in 10" v-bind:key="-n">
                <!-- binding normal numbes causes duplicate-key issues, when the ids used here are the same as real athletes ones by chance -->
                <td></td>
                <td></td>
                <td>
                    <strong>
                        {{ __("general.create_new") }}
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
        //this.ALL_YEARS_STRING = "Alle"; //TODO make selector for favorites/non-favorites, clone year selector to this page
    },
    mounted() {
        this.$store.dispatch("fetchFavourites");
    },
    computed: {
        ...mapGetters(["favourites"])
    },
    methods: {
        requestPDF() {
            this.$store.dispatch(
                "requestPDF",
                $("#render-pdf").prop("outerHTML")
            );
        }
    }
};
</script>
