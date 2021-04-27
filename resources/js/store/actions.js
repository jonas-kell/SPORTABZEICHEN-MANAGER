let actions = {
    fetchFavourites({ commit }) {
        axios
            .get("api/favourites")
            .then(res => {
                commit("FETCH_FAVOURITES", res.data.athletes);
            })
            .catch(err => {
                console.log(err);
            });
    },
    addFavourite({ commit }, athlete) {
        axios
            .put(`api/favourites/add/${athlete.id}`)
            .then(res => {
                commit("FETCH_FAVOURITES", res.data.athletes);
            })
            .catch(err => {
                console.log(err);
            });
    },
    dropFavourite({ commit }, athlete) {
        axios
            .put(`api/favourites/drop/${athlete.id}`)
            .then(res => {
                commit("FETCH_FAVOURITES", res.data.athletes);
            })
            .catch(err => {
                console.log(err);
            });
    },
    fetchAthlete({ commit }, athlete_id) {
        axios
            .get(`/api/athlete/${athlete_id}`)
            .then(res => {
                commit("FETCH_ATHLETE", res.data.data);
            })
            .catch(err => {
                console.log(err);
            });
    },
    setupCreateAthlete({ commit }, newAthlete) {
        commit("SETUP_CREATE_ATHLETE", newAthlete);
    },
    createAthlete({ commit }, athlete) {
        axios
            .post("/api/athlete/create", athlete)
            .then(res => {
                commit("FETCH_ATHLETE", res.data.data);
            })
            .catch(err => {
                console.log(err);
            });
    },
    updateAthlete({ commit }, athlete) {
        axios
            .put(`/api/athlete/update/${athlete.id}`, athlete)
            .then(res => {
                commit("FETCH_ATHLETE", res.data.data);
            })
            .catch(err => {
                console.log(err);
            });
    },
    deleteAthlete({ commit }, athlete) {
        axios
            .delete(`/api/athlete/delete/${athlete.id}`)
            .then(res => {
                commit("DELETE_ATHLETE", post);
            })
            .catch(err => {
                console.log(err);
            });
    },
    getYear({ commit }) {
        axios
            .get("/api/year/get")
            .then(res => {
                commit("UPDATE_YEARS_ARRAY", res.data);
            })
            .catch(err => {
                console.log(err);
            });
    },
    setYear({ commit }, year) {
        axios
            .put("/api/year/set", { year: year })
            .then(res => {
                commit("UPDATE_YEARS_ARRAY", res.data);
            })
            .catch(err => {
                console.log(err);
            });
    },
    fetchSearch({ commit, state }, searchString) {
        if (searchString === undefined) {
            //call has no searchString specified, use previouse one
            console.log("reuse value");
        } else {
            //call has no searchString specified, use previouse one
            state.searchString = searchString;
        }

        if (state.searchString == "") {
            //search string is empty. an empty string produces no results
            commit("FETCH_ATHLETE_SEARCH", []);
        } else {
            //a string is set, therefore make the call
            axios
                .get(`api/search/athletes/${searchString}`)
                .then(res => {
                    commit("FETCH_ATHLETE_SEARCH", res.data.athletes);
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
};

export default actions;
