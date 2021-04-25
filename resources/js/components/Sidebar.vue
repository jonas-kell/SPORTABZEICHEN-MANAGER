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

        <div class="card-body">
            I'm an example component.
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    mounted() {
        this.$store.dispatch("fetchFavourites");
        this.$store.dispatch("getYear");
    },
    data() {
        return {
            searchbar: ""
        };
    },
    watch: {
        "yearsArray.current": function(newValue, oldValue) {
            this.$store.dispatch("setYear", newValue); //TODO also calls on initial laod. sets unnecessary. maybe resolve.
        }
    },
    computed: {
        ...mapGetters(["favourites", "yearsArray"])
    }
};
</script>
