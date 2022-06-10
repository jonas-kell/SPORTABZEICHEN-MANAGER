<style>
td.highlighted {
  background-color: #33af229a;
}
</style>

<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card class="q-dialog-plugin">
      <h5>
        <p class="text-center">
          {{ $t('general.' + category) }}: <br />
          {{ discipline }}
          <span>
            <span
              class="help_symbol"
              style="position: absolute"
              v-if="
                athlete.needed_requirements[category][discipline].description
              "
            >
              <q-tooltip
                anchor="top middle"
                self="bottom middle"
                max-width="40rem"
                :offset="[10, 10]"
              >
                {{
                  athlete.needed_requirements[category][discipline].description
                }}
              </q-tooltip>
            </span>
          </span>
        </p>
      </h5>
      <div style="width: 100%">
        <div class="q-mr-md q-ml-md">
          <input type="text" style="width: 100%" v-model="performance" />
          <table class="q-mt-sm">
            <tr>
              <td
                class="hide-overflow unselectable cursor-pointer"
                v-bind:class="{
                  highlighted: bronze_highlighted,
                }"
                @click="toggleBronze"
              >
                <span class="medal bronze float-left mr-1" @click.stop>
                  <q-tooltip
                    anchor="top middle"
                    self="bottom middle"
                    max-width="40rem"
                    :offset="[10, 10]"
                  >
                    {{
                      build_tooltip(
                        athlete.needed_requirements[category][discipline]
                          .requirements.bronze.requirement_with_unit,
                        athlete.needed_requirements[category][discipline]
                          .requirements.bronze.description
                      )
                    }}
                  </q-tooltip>
                </span>
                <span class="no-break">
                  {{
                    athlete.needed_requirements[category][discipline]
                      .requirements.bronze.requirement_with_unit
                  }}
                </span>
              </td>
              <td
                class="hide-overflow unselectable cursor-pointer"
                v-bind:class="{
                  highlighted: silver_highlighted,
                }"
                @click="toggleSilver"
              >
                <span class="medal silver float-left mr-1" @click.stop>
                  <q-tooltip
                    anchor="top middle"
                    self="bottom middle"
                    max-width="40rem"
                    :offset="[10, 10]"
                  >
                    {{
                      build_tooltip(
                        athlete.needed_requirements[category][discipline]
                          .requirements.silver.requirement_with_unit,
                        athlete.needed_requirements[category][discipline]
                          .requirements.silver.description
                      )
                    }}
                  </q-tooltip>
                </span>
                <span class="no-break">
                  {{
                    athlete.needed_requirements[category][discipline]
                      .requirements.silver.requirement_with_unit
                  }}
                </span>
              </td>
              <td
                class="hide-overflow unselectable cursor-pointer"
                v-bind:class="{
                  highlighted: gold_highlighted,
                }"
                @click="toggleGold"
              >
                <span class="medal gold float-left mr-1" @click.stop>
                  <q-tooltip
                    anchor="top middle"
                    self="bottom middle"
                    max-width="40rem"
                    :offset="[10, 10]"
                  >
                    {{
                      build_tooltip(
                        athlete.needed_requirements[category][discipline]
                          .requirements.gold.requirement_with_unit,
                        athlete.needed_requirements[category][discipline]
                          .requirements.gold.description
                      )
                    }}
                  </q-tooltip>
                </span>
                <span class="no-break">
                  {{
                    athlete.needed_requirements[category][discipline]
                      .requirements.gold.requirement_with_unit
                  }}
                </span>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <!-- buttons example -->
      <q-card-actions align="right">
        <q-btn
          class="bg-grey text-white"
          color="secondary"
          label="Cancel"
          @click="onCancelClick"
        />
        <q-btn color="primary" label="OK" @click="onOKClick" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script lang="ts">
import {
  Athlete,
  Category,
  Discipline,
  PerformanceArray,
} from '../../store/athletes/state';
import { defineComponent, PropType } from 'vue';
import { build_tooltip } from '../../string_helpers';

export default defineComponent({
  props: {
    athlete: {
      type: Object as PropType<Athlete>,
      required: true,
    },
    category: {
      type: String as PropType<
        'coordination' | 'endurance' | 'speed' | 'strength'
      >,
      required: true,
    },
    discipline: {
      type: String,
      required: true,
    },
  },

  created: function () {
    this.performance =
      this.athlete.performances[this.category][this.discipline].performance;

    this.gold_highlighted =
      this.athlete.performances[this.category][this.discipline]
        .gold_highlighted == true;

    this.silver_highlighted =
      this.athlete.performances[this.category][this.discipline]
        .silver_highlighted == true;

    this.bronze_highlighted =
      this.athlete.performances[this.category][this.discipline]
        .bronze_highlighted == true;
  },

  emits: [
    // REQUIRED
    'ok',
    'hide',
  ],

  data() {
    return {
      gold_highlighted: false,
      silver_highlighted: false,
      bronze_highlighted: false,
      performance: '',
    };
  },

  methods: {
    build_tooltip: build_tooltip,
    toggleGold() {
      this.gold_highlighted = !this.gold_highlighted;
    },
    toggleSilver() {
      this.silver_highlighted = !this.silver_highlighted;
    },
    toggleBronze() {
      this.bronze_highlighted = !this.bronze_highlighted;
    },
    updateAthletePerformance: function () {
      // create mockup athlete structure
      let mockup_athlete = { id: this.athlete.id } as Athlete;
      let performances = {} as {
        [key in Category]: { [key: Discipline]: PerformanceArray };
      };
      performances[this.category] = {};
      performances[this.category][this.discipline] = {} as PerformanceArray;

      // set the changed value
      performances[this.category][this.discipline].performance =
        this.performance;
      performances[this.category][this.discipline].bronze_highlighted =
        this.bronze_highlighted;
      performances[this.category][this.discipline].silver_highlighted =
        this.silver_highlighted;
      performances[this.category][this.discipline].gold_highlighted =
        this.gold_highlighted;

      mockup_athlete.performances = performances;

      // dispatch the performance-update
      void this.$store.dispatch('athletesModule/updateAthletePerformances', {
        athlete: mockup_athlete,
        updateTable: true,
      });
    },

    // following method is REQUIRED
    // (don't change its name --> "show")
    show() {
      // @ts-expect-error refs not typed
      this.$refs.dialog.show();
    },

    // following method is REQUIRED
    // (don't change its name --> "hide")
    hide() {
      // @ts-expect-error refs not typed
      this.$refs.dialog.hide();
    },

    onDialogHide() {
      // required to be emitted
      // when QDialog emits "hide" event
      this.$emit('hide');
    },

    onOKClick() {
      this.updateAthletePerformance();

      // on OK, it is REQUIRED to
      // emit "ok" event (with optional payload)
      // before hiding the QDialog
      this.$emit('ok');
      // or with payload: this.$emit('ok', { ... })

      // then hiding dialog
      this.hide();
    },

    onCancelClick() {
      // we just need to hide the dialog
      this.hide();
    },
  },
});
</script>
