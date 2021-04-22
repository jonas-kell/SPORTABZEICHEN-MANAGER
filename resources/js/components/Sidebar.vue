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
    },
    data() {
        return {
            searchbar: ""
        };
    },
    computed: {
        ...mapGetters(["favourites"])
    }
};
</script>
