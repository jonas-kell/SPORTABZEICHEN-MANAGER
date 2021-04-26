<template>
    <div class="card card-fill">
        <div class="card-header">Center</div>

        <div class="card-body">
            <input
                v-model="name"
                class="form-control"
                :placeholder="__('general.name')"
            />
            <input
                v-model="year"
                class="form-control"
                :placeholder="__('general.year')"
            />
            <button v-on:click="createAthlete">
                create
            </button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    mounted() {},
    data() {
        return {
            name: "",
            year: 0
        };
    },
    methods: {
        createAthlete: function() {
            this.$store.dispatch("createAthlete", {
                name: this.name,
                year: this.year
            });
        }
    },
    computed: {
        ...mapGetters(["athlete"])
    }
};
</script>
