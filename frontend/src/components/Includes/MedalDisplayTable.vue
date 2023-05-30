<style scoped>
.gold {
  color: green;
  font-weight: bold;
}

.silver {
  color: orange;
  font-weight: bold;
}

.bronze {
  color: red;
  font-weight: bold;
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
        bronze: medalScores.coordination.value == 'bronze',
        silver: medalScores.coordination.value == 'silver',
        gold: medalScores.coordination.value == 'gold',
        edges: true,
      }"
      >Koo{{ calculatePointsNumber(medalScores.coordination.value) }}</span
    >
    <span
      v-bind:class="{
        bronze: medalScores.endurance.value == 'bronze',
        silver: medalScores.endurance.value == 'silver',
        gold: medalScores.endurance.value == 'gold',
        edges: true,
      }"
      >Aus{{ calculatePointsNumber(medalScores.endurance.value) }}</span
    >
    <span
      v-bind:class="{
        bronze: medalScores.speed.value == 'bronze',
        silver: medalScores.speed.value == 'silver',
        gold: medalScores.speed.value == 'gold',
        edges: true,
      }"
      >Sch{{ calculatePointsNumber(medalScores.speed.value) }}</span
    >
    <span
      v-bind:class="{
        bronze: medalScores.strength.value == 'bronze',
        silver: medalScores.strength.value == 'silver',
        gold: medalScores.strength.value == 'gold',
        edges: true,
      }"
      >Stä{{ calculatePointsNumber(medalScores.strength.value) }}</span
    >
  </span>
</template>

<script lang="ts">
import { Category } from 'src/store/athletes/state';
import { PropType, defineComponent } from 'vue';

export default defineComponent({
  props: {
    medalScores: {
      type: Object as PropType<{
        [key in Category]: {
          performance: string;
          points: number;
          discipline_name: string;
          value: string;
        };
      }>,
      default: {} as {
        [key in Category]: {
          performance: string;
          points: number;
          discipline_name: string;
          value: string;
        };
      },
    },
  },
  methods: {
    calculatePointsNumber: function (valueString: string) {
      if (valueString == 'gold') {
        return '(3)';
      } else if (valueString == 'silver') {
        return '(2)';
      } else if (valueString == 'bronze') {
        return '(1)';
      } else {
        return '(0)';
      }
    },
  },
});
</script>
