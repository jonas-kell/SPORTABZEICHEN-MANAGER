let getters = {
    favourites: state => {
        return state.favourites;
    },
    athlete: state => {
        return state.athlete;
    },
    yearsArray: state => {
        return state.yearsArray;
    }
};

export default getters;
