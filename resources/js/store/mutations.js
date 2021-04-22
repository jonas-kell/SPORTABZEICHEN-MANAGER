let mutations = {
    FETCH_FAVOURITES(state, favourites) {
        return (state.favourites = favourites);
    },
    CREATE_ATHLETE(state, athlete) {
        return (state.athlete = athlete);
    },
    DELETE_ATHLETE(state) {
        state.athlete = null;
    }
};
export default mutations;
