type MapToComputed<S> = {
  [K in keyof S]: () => S[K];
};
