let getters = {
    favourites: state => {
        return state.favourites;
    },
    athlete: state => {
        return state.athlete;
    },
    newAthlete: state => {
        return state.newAthlete;
    },
    yearsArray: state => {
        return state.yearsArray;
    },
    searchedAthletes: state => {
        return state.searchedAthletes;
    },
    searchNeedsUpdate: state => {
        return state.searchNeedsUpdate;
    }
};

export default getters;
