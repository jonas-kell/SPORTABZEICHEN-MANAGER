<style>
td.highlighted {
  background-color: #33af229a;
}
</style>

<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card class="q-dialog-plugin">
      <h5>
        <p class="text-center">{{ $t('general.proofOfSwimming') }}:</p>
      </h5>
      <div style="width: 100%">
        <div class="q-mr-md q-ml-md">
          <input type="text" style="width: 100%" v-model="swimmingYear" />
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
import { Athlete } from '../../store/athletes/state';
import { defineComponent, PropType } from 'vue';

export default defineComponent({
  props: {
    athlete: {
      type: Object as PropType<Athlete>,
      required: true,
    },
  },

  created: function () {
    if (this.athlete.proofOfSwimming == null) {
      this.swimmingYear = '';
    } else {
      this.swimmingYear = String(this.athlete.proofOfSwimming);
    }
  },

  emits: [
    // REQUIRED
    'ok',
    'hide',
  ],

  data() {
    return {
      swimmingYear: '',
    };
  },

  methods: {
    updateAthleteSwimmingYear: function () {
      let newProofOfSwimming = null;
      const parsed = parseInt(this.swimmingYear);
      if (parsed) {
        newProofOfSwimming = parsed;
      }

      // dispatch the performance-update
      void this.$store.dispatch('athletesModule/updateAthleteSwimmingYear', {
        athleteId: this.athlete.id,
        proofOfSwimming: newProofOfSwimming,
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
      this.updateAthleteSwimmingYear();

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
