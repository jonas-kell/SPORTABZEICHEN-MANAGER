let mutations = {
    FETCH_FAVOURITES(state, favourites) {
        return (state.favourites = favourites);
    },
    FETCH_ATHLETE(state, athlete) {
        return (state.athlete = athlete);
    },
    SETUP_CREATE_ATHLETE(state, newAthlete) {
        state.athlete = null;
        state.newAthlete = newAthlete;
    },
    DELETE_ATHLETE(state) {
        state.athlete = null;
    },
    UPDATE_YEARS_ARRAY(state, yearsArray) {
        state.yearsArray = yearsArray;
    },
    FETCH_ATHLETE_SEARCH(state, athletes) {
        return (state.searchedAthletes = athletes);
    }
};
export default mutations;
