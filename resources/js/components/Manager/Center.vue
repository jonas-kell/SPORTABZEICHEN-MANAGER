<template>
    <div class="card card-fill">
        <div v-if="newAthlete == null && athlete == null">
            <div class="card-header">{{ __("general.welcome") }}</div>

            <div class="card-body">
                <p>
                    {{ __("general.action") }}
                </p>
            </div>
        </div>
        <div v-else-if="athlete != null">
            <div class="card-header">
                {{ athlete.name }}
                ({{ __("general." + athlete.gender) }} |
                {{ athlete.requirements_tag }})
                <div class="float-right">
                    <span
                        v-if="canEdit"
                        class="btn btn-sm btn-save"
                        @click="toggleEdit"
                        >{{ __("general.save") }}</span
                    >
                    <span
                        v-else
                        class="btn btn-sm btn-edit"
                        @click="toggleEdit"
                        >{{ __("general.edit") }}</span
                    >
                </div>
                <favourite-star-button
                    v-bind="{ athlete: athlete }"
                    class="mr-3"
                />
            </div>

            <div class="card-body row">
                <div class="from-group col-lg-6 col-12">
                    <label for="center_name_field">
                        {{ __("general.name") }}
                    </label>
                    <input
                        id="center_name_field"
                        v-model="athlete.name"
                        class="form-control"
                        v-bind:disabled="!canEdit"
                        :placeholder="__('general.name')"
                    />
                </div>
                <div class="from-group col-lg-6 col-12">
                    <label for="center_year_field">
                        {{ __("general.year") }}
                    </label>
                    <input
                        id="center_year_field"
                        v-model="athlete.year"
                        class="form-control"
                        v-bind:disabled="!canEdit"
                        :placeholder="__('general.year')"
                    />
                </div>
                <div class="from-group col-lg-6 col-12">
                    <label for="center_birthday_field">
                        {{ __("general.birthday") }}
                    </label>
                    <input
                        id="center_birthday_field"
                        type="date"
                        v-model="athlete.birthday"
                        class="form-control"
                        v-bind:disabled="!canEdit"
                    />
                </div>
                <div class="from-group col-lg-6 col-12">
                    <label for="center_gender_field">
                        {{ __("general.gender") }}
                    </label>
                    <select
                        id="center_gender_field"
                        v-model="athlete.gender"
                        class="form-control"
                        v-bind:disabled="!canEdit"
                    >
                        <option value="male">
                            {{ __("general.male") }}
                        </option>
                        <option value="female">
                            {{ __("general.female") }}
                        </option>
                    </select>
                </div>
            </div>
            <hr class="m-0" />
            <div class="card-body row"></div>
            <!-- Athlete body section -->
            <div class="from-group col-12">
                <label for="center_notes_field">
                    {{ __("general.notes") }}
                </label>
                <textarea
                    id="center_notes_field"
                    v-model="athlete.notes"
                    class="form-control"
                >
                </textarea>
            </div>
            <div class="from-group col-12 mt-2">
                <h3>
                    {{ __("general.requirements") }}:
                    {{ __("general." + athlete.gender) }} |
                    {{ athlete.requirements_tag }}
                </h3>
            </div>
            <div
                class="from-group col-12"
                v-for="category in [
                    'coordination',
                    'endurance',
                    'speed',
                    'strength'
                ]"
                v-bind:key="category"
            >
                <h4 class="mt-2">{{ __("general." + category) }}</h4>
                <table class="requirements_table">
                    <tr
                        v-for="(discipline_array, discipline) in athlete
                            .needed_requirements[category]"
                        v-bind:key="discipline"
                    >
                        <td style="width: 30%">
                            {{ discipline }}
                            <span
                                class="help_symbol float-right"
                                data-toggle="tooltip"
                                data-placement="top"
                                v-bind:title="discipline_array.description"
                                v-if="discipline_array.description"
                            ></span>
                        </td>
                        <td
                            style="width: 16%"
                            class="hide-overflow unselectable pointer"
                            v-bind:class="{
                                highlighted:
                                    athlete.performances[category][discipline]
                                        .bronze_highlighted
                            }"
                            @click="
                                toggleHighlight(
                                    category,
                                    discipline,
                                    'bronze_highlighted'
                                )
                            "
                        >
                            <span
                                class="medal bronze float-left mr-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                v-bind:title="
                                    build_tooltip(
                                        discipline_array.requirements.bronze
                                            .requirement_with_unit,
                                        discipline_array.requirements.bronze
                                            .description
                                    )
                                "
                                @click.stop
                            ></span>
                            <span class="requirement_with_unit">
                                {{
                                    discipline_array.requirements.bronze
                                        .requirement_with_unit
                                }}
                            </span>
                        </td>
                        <td
                            style="width: 16%"
                            class="hide-overflow unselectable pointer"
                            v-bind:class="{
                                highlighted:
                                    athlete.performances[category][discipline]
                                        .silver_highlighted
                            }"
                            @click="
                                toggleHighlight(
                                    category,
                                    discipline,
                                    'silver_highlighted'
                                )
                            "
                        >
                            <span
                                class="medal silver float-left mr-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                v-bind:title="
                                    build_tooltip(
                                        discipline_array.requirements.silver
                                            .requirement_with_unit,
                                        discipline_array.requirements.silver
                                            .description
                                    )
                                "
                                @click.stop
                            ></span>
                            <span class="requirement_with_unit">
                                {{
                                    discipline_array.requirements.silver
                                        .requirement_with_unit
                                }}
                            </span>
                        </td>
                        <td
                            style="width: 16%"
                            class="hide-overflow unselectable pointer"
                            v-bind:class="{
                                highlighted:
                                    athlete.performances[category][discipline]
                                        .gold_highlighted
                            }"
                            @click="
                                toggleHighlight(
                                    category,
                                    discipline,
                                    'gold_highlighted'
                                )
                            "
                        >
                            <span
                                class="medal gold float-left mr-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                v-bind:title="
                                    build_tooltip(
                                        discipline_array.requirements.gold
                                            .requirement_with_unit,
                                        discipline_array.requirements.gold
                                            .description
                                    )
                                "
                                @click.stop
                            ></span>
                            <span class="requirement_with_unit">
                                {{
                                    discipline_array.requirements.gold
                                        .requirement_with_unit
                                }}
                            </span>
                        </td>
                        <td style="width: 18%">
                            <input
                                type="text"
                                class="mr-1 ml-1"
                                style="width: 90%;"
                                v-model="
                                    athlete.performances[category][discipline]
                                        .performance
                                "
                                @change="
                                    updateAthletePerformance(
                                        athlete.id, // I hand this to the function to cache it, because I fear, we have race conditions, if the athlete changes before this gets executed, if a change athlete click triggers the change event.
                                        category,
                                        discipline
                                    )
                                "
                            />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div v-else-if="newAthlete != null">
            <div class="card-header">{{ __("general.create_new") }}</div>

            <div class="card-body row">
                <div class="from-group col-lg-6 col-12">
                    <label for="center_name_field">
                        {{ __("general.name") }}
                    </label>
                    <input
                        id="center_name_field"
                        v-model="newAthlete.name"
                        class="form-control"
                        :placeholder="__('general.name')"
                    />
                </div>
                <div class="from-group col-lg-6 col-12">
                    <label for="center_year_field">
                        {{ __("general.year") }}
                    </label>
                    <input
                        id="center_year_field"
                        v-model="newAthlete.year"
                        class="form-control"
                        :placeholder="__('general.year')"
                    />
                </div>
                <div class="from-group col-lg-6 col-12">
                    <label for="center_birthday_field">
                        {{ __("general.birthday") }}
                    </label>
                    <input
                        id="center_birthday_field"
                        type="date"
                        v-model="newAthlete.birthday"
                        class="form-control"
                    />
                </div>
                <div class="from-group col-lg-6 col-12">
                    <label for="center_gender_field">
                        {{ __("general.gender") }}
                    </label>
                    <select
                        id="center_gender_field"
                        v-model="newAthlete.gender"
                        class="form-control"
                    >
                        <option value="male">
                            {{ __("general.male") }}
                        </option>
                        <option value="female">
                            {{ __("general.female") }}
                        </option>
                    </select>
                </div>
                <div
                    class="from-group col-12 mt-3 justify-content-center row no-gutters"
                >
                    <button
                        class="btn btn-save btn-sm"
                        v-on:click="createAthlete"
                    >
                        {{ __("general.save") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import FavouriteStarButton from "../Includes/FavouriteStarButton.vue";

export default {
    components: {
        FavouriteStarButton
    },
    mounted() {
        this.typingTimer = {}; //use sub-elements to time different things, to prevent loss of data
    },
    data() {
        return {
            canEdit: false
        };
    },
    methods: {
        createAthlete: function() {
            this.$store.dispatch("createAthlete", this.newAthlete);
        },
        toggleEdit() {
            if (this.canEdit) {
                // edit was avaliable until now, that means we need to save now
                this.$store.dispatch("updateAthlete", this.athlete);
            }

            this.canEdit = !this.canEdit;
        },
        updateAthletePerformance(athlete_id, category, discipline) {
            // I hand the athlete id to the function to cache it, because I fear, we have race conditions,
            // if the athlete changes before this gets executed, if a change athlete click triggers the change event.

            // create mockup athlete structure
            let mockup_athlete = { id: athlete_id };
            mockup_athlete.performances = {};
            mockup_athlete.performances[category] = {};
            mockup_athlete.performances[category][discipline] = {};
            // set the changed value
            mockup_athlete.performances[category][
                discipline
            ].performance = this.athlete.performances[category][
                discipline
            ].performance;

            mockup_athlete.performances[category][
                discipline
            ].bronze_highlighted = this.athlete.performances[category][
                discipline
            ].bronze_highlighted;

            mockup_athlete.performances[category][
                discipline
            ].silver_highlighted = this.athlete.performances[category][
                discipline
            ].silver_highlighted;

            mockup_athlete.performances[category][
                discipline
            ].gold_highlighted = this.athlete.performances[category][
                discipline
            ].gold_highlighted;

            // dispatch the performance-update
            this.$store.dispatch("updateAthletePerformances", mockup_athlete);
        },
        toggleHighlight(category, discipline, type) {
            if (this.athlete.performances[category][discipline][type]) {
                this.athlete.performances[category][discipline][type] = false;
            } else {
                this.athlete.performances[category][discipline][type] = true;
            }
            this.$forceUpdate(); // don't know, why I need this, but hey it doesn't rerender otherwise.

            let instance = this;
            clearTimeout(this.typingTimer[discipline]);
            this.typingTimer[discipline] = setTimeout(function() {
                instance.updateAthletePerformance(
                    instance.athlete.id,
                    category,
                    discipline
                ); //store the update in the database
            }, 400);
        }
    },
    watch: {
        athlete: function() {
            //let the dom modify itself before toggeling //TODO: maybe make smart and not delay dependent
            setTimeout(function() {
                //in order to catch the changed values for title, dispose of all tooltips and reenable them fresh.
                $('[data-toggle="tooltip"]').tooltip("dispose");
                // the api says this function is called "destroy".
                // You have no idea, how much time this misinformation just cost me
                $('[data-toggle="tooltip"]').tooltip("enable");
            }, 300);
        },
        "athlete.notes": function() {
            let instance = this;
            if (instance.athlete != null) {
                clearTimeout(this.typingTimer["typing"]);
                this.typingTimer["typing"] = setTimeout(function() {
                    instance.$store.dispatch(
                        "updateAthleteNotes",
                        instance.athlete
                    );
                }, 300);
            }
        }
    },
    computed: {
        ...mapGetters(["athlete", "newAthlete"])
    }
};
</script>
