<style scoped>
.gold {
    background: #ffc107;
}

.silver {
    background: #adadad;
}

.bronze {
    background: #7e6963;
}

.edges {
    border-radius: 0.7em;
    padding-left: 0.3em;
    padding-right: 0.3em;
}
</style>

<template>
    <span class="no-break unselectable">
        <span
            v-bind:class="{
                bronze: medalScores.coordination == 'bronze',
                silver: medalScores.coordination == 'silver',
                gold: medalScores.coordination == 'gold',
                edges: true
            }"
            >Koo</span
        >
        <span
            v-bind:class="{
                bronze: medalScores.endurance == 'bronze',
                silver: medalScores.endurance == 'silver',
                gold: medalScores.endurance == 'gold',
                edges: true
            }"
            >Aus</span
        >
        <span
            v-bind:class="{
                bronze: medalScores.speed == 'bronze',
                silver: medalScores.speed == 'silver',
                gold: medalScores.speed == 'gold',
                edges: true
            }"
            >Sch</span
        >
        <span
            v-bind:class="{
                bronze: medalScores.strength == 'bronze',
                silver: medalScores.strength == 'silver',
                gold: medalScores.strength == 'gold',
                edges: true
            }"
            >Stä</span
        >
    </span>
</template>

<script>
export default {
    props: { medalScores: Object },
    methods: {}
};
</script>
