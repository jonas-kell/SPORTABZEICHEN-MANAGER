// How to register in a boot file:

import { Notify } from 'quasar';

Notify.registerType('success', {
  icon: 'check',
  progress: true,
  color: 'green',
  textColor: 'white',
  classes: 'glossy',
  position: 'top-right'
});

Notify.registerType('error', {
  icon: 'error',
  progress: true,
  color: 'red',
  textColor: 'white',
  classes: 'glossy',
  position: 'top-right'
});
