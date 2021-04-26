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
    mounted() {},
    data() {
        return {
            canEdit: false
        };
    },
    methods: {
        createAthlete: function() {
            this.$store.dispatch("createAthlete", this.newAthlete);
            this.$store.dispatch("requestSearchUpdate");
        },
        toggleEdit() {
            if (this.canEdit) {
                // edit was avaliable until now, that means we need to save now
                this.$store.dispatch("updateAthlete", this.athlete);

                //make sure to update sidebar
                this.$store.dispatch("fetchFavourites");
                this.$store.dispatch("requestSearchUpdate");
            }

            this.canEdit = !this.canEdit;
        }
    },
    computed: {
        ...mapGetters(["athlete", "newAthlete"])
    }
};
</script>
