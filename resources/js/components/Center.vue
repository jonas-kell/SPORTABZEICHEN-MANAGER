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
                <h3>{{ __("general.requirements") }}</h3>
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
                        <td style="width: 16%">
                            <span
                                class="medal bronze float-left mr-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                v-bind:title="
                                    discipline_array.requirements.bronze
                                        .description
                                "
                            ></span>
                            {{
                                discipline_array.requirements.bronze
                                    .requirement_with_unit
                            }}
                        </td>
                        <td style="width: 16%">
                            <span
                                class="medal silver float-left mr-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                v-bind:title="
                                    discipline_array.requirements.silver
                                        .description
                                "
                            ></span>
                            {{
                                discipline_array.requirements.silver
                                    .requirement_with_unit
                            }}
                        </td>
                        <td style="width: 16%">
                            <span
                                class="medal gold float-left mr-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                v-bind:title="
                                    discipline_array.requirements.gold
                                        .description
                                "
                            ></span>
                            {{
                                discipline_array.requirements.gold
                                    .requirement_with_unit
                            }}
                        </td>
                        <td style="width: 18%"></td>
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

export default {
    mounted() {
        this.typingTimer;
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
        }
    },
    watch: {
        athlete: function() {
            //let the dom modify itself before toggeling //TODO: maybe make smart and not delay dependent
            setTimeout(function() {
                $('[data-toggle="tooltip"]').tooltip();
            }, 300);
        },
        "athlete.notes": function() {
            let instance = this;
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(function() {
                instance.$store.dispatch(
                    "updateAthleteNotes",
                    instance.athlete
                );
            }, 300);
        }
    },
    computed: {
        ...mapGetters(["athlete", "newAthlete"])
    }
};
</script>
