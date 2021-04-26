<template>
    <div class="card card-fill">
        <div class="card-header">
            <div class="input-group">
                <input
                    v-model="searchbar"
                    class="form-control"
                    :placeholder="__('general.search')"
                />
                <div class="input-group-append">
                    <span
                        class="input-group-text search-icon unselectable"
                    ></span>
                    <select v-model="yearsArray.current">
                        <option
                            v-for="year in yearsArray.allYears"
                            v-bind:key="year"
                            v-bind:value="year"
                        >
                            {{ year }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body row no-gutters justify-content-center">
            <span
                class="btn btn-edit btn-sm mb-2 col-lg-8 col-md-12 col-8"
                @click="createNewAthlete"
            >
                ++ {{ __("general.create_new") }} ++
            </span>
            <sidebar-list
                class="col-12"
                v-bind="{
                    currentYear: yearsArray.current,
                    list: searchedAthletes
                }"
            />
        </div>
        <hr class="m-0" />
        <div class="card-body row no-gutters">
            <h5>{{ __("general.favourites") }}</h5>

            <sidebar-list
                class="col-12"
                v-bind="{
                    currentYear: yearsArray.current,
                    list: favourites
                }"
            />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import SidebarList from "./SidebarList.vue";

export default {
    components: { SidebarList },
    mounted() {
        this.$store.dispatch("fetchFavourites");
        this.$store.dispatch("getYear");
        this.typingTimer;
    },
    data() {
        return {
            searchbar: ""
        };
    },
    methods: {
        createNewAthlete: function() {
            this.$store.dispatch("setupCreateAthlete", {
                name: this.searchbar,
                year: this.yearsArray.current,
                birthday: null,
                gender: "male"
            });
        }
    },
    watch: {
        "yearsArray.current": function(newValue, oldValue) {
            this.$store.dispatch("setYear", newValue); //TODO also calls on initial laod. sets unnecessary. maybe resolve.
            let instance = this;
            setTimeout(function() {
                instance.$store.dispatch("fetchSearch", instance.searchbar);
            }, 1000); //TODO lazy, doesn't check for finish of call, just waits a set amount of time. may need to be modified
        },
        searchbar: function(newValue, oldValue) {
            let store = this.$store;
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(function() {
                store.dispatch("fetchSearch", newValue);
            }, 250);
        },
        searchNeedsUpdate: function(newValue, oldValue) {
            if (newValue) {
                console.log("done");
                this.$store.dispatch("fetchSearch", this.searchbar);
                this.$store.dispatch("fulfillSearchUpdate");
            }
        }
    },
    computed: {
        ...mapGetters([
            "favourites",
            "yearsArray",
            "searchedAthletes",
            "searchNeedsUpdate"
        ])
    }
};
</script>
