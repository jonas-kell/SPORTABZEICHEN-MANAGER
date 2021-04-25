let mutations = {
    FETCH_FAVOURITES(state, favourites) {
        return (state.favourites = favourites);
    },
    CREATE_ATHLETE(state, athlete) {
        return (state.athlete = athlete);
    },
    DELETE_ATHLETE(state) {
        state.athlete = null;
    },
    UPDATE_YEARS_ARRAY(state, yearsArray) {
        state.yearsArray = yearsArray;
    }
};
export default mutations;
