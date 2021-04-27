let mutations = {
    FETCH_FAVOURITES(state, favourites) {
        return (state.favourites = favourites);
    },
    FETCH_ATHLETE(state, athlete) {
        return (state.athlete = athlete);
    },
    SETUP_CREATE_ATHLETE(state, newAthlete) {
        state.athlete = null;
        return (state.newAthlete = newAthlete);
    },
    DELETE_ATHLETE(state) {
        state.athlete = null;
    },
    UPDATE_YEARS_ARRAY(state, yearsArray) {
        return (state.yearsArray = yearsArray);
    },
    FETCH_ATHLETE_SEARCH(state, athletes) {
        return (state.searchedAthletes = athletes);
    },
    REQUEST_SEARCH_UPDATE(state) {
        state.searchNeedsUpdate = true;
    },
    FULFILL_SEARCH_UPDATE(state) {
        state.searchNeedsUpdate = false;
    }
};
export default mutations;
