<style scoped>
.btn-save {
    color: rgb(17, 17, 17);
    background-color: #1add00;
    border-color: #016601;
}

.btn-edit {
    color: #fff;
    background-color: #4d4d4d;
    border-color: #292929;
}
</style>

<template>
    <div class="card card-fill">
        <div v-if="athlete == null">
            <div class="card-header">Center</div>

            <div class="card-body">
                <input
                    v-model="newName"
                    class="form-control"
                    :placeholder="__('general.name')"
                />
                <input
                    v-model="newYear"
                    class="form-control"
                    :placeholder="__('general.year')"
                />
                <button v-on:click="createAthlete">
                    create
                </button>
            </div>
        </div>
        <div v-else>
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
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    mounted() {},
    data() {
        return {
            newName: "",
            newYear: 0,
            canEdit: false
        };
    },
    methods: {
        createAthlete: function() {
            this.$store.dispatch("createAthlete", {
                name: this.newName,
                year: this.newYear
            });
        },
        toggleEdit() {
            if (this.canEdit) {
                // edit was avaliable until now, that means we need to save now
                this.$store.dispatch("updateAthlete", this.athlete);
                this.$store.dispatch("fetchFavourites");
            }

            this.canEdit = !this.canEdit;
        }
    },
    computed: {
        ...mapGetters(["athlete"])
    }
};
</script>
