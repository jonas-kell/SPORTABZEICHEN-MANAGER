let getters = {
    favourites: state => {
        return state.favourites;
    },
    athlete: state => {
        return state.athlete;
    },
    yearsArray: state => {
        return state.yearsArray;
    },
    searchedAthletes: state => {
        return state.searchedAthletes;
    }
};

export default getters;
