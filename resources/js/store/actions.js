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
    addFavourite({ dispatch, commit, state }, athlete) {
        axios
            .put(`api/favourites/add/${athlete.id}`)
            .then(res => {
                commit("FETCH_FAVOURITES", res.data.athletes);

                //update search and center athlete asynchronously
                dispatch("fetchSearch");
                if (state.athlete !== null) {
                    dispatch("fetchAthlete", state.athlete.id);
                }
            })
            .catch(err => {
                console.log(err);
            });
    },
    dropFavourite({ dispatch, commit, state }, athlete) {
        axios
            .put(`api/favourites/drop/${athlete.id}`)
            .then(res => {
                commit("FETCH_FAVOURITES", res.data.athletes);

                //update search and center athlete asynchronously
                dispatch("fetchSearch");
                if (state.athlete !== null) {
                    dispatch("fetchAthlete", state.athlete.id);
                }
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
    createAthlete({ dispatch, commit }, athlete) {
        axios
            .post("/api/athlete/create", athlete)
            .then(res => {
                commit("FETCH_ATHLETE", res.data.data);

                //update search asynchronously
                dispatch("fetchSearch");
            })
            .catch(err => {
                console.log(err);
            });
    },
    updateAthlete({ dispatch, commit }, athlete) {
        axios
            .put(`/api/athlete/update/${athlete.id}`, athlete)
            .then(res => {
                commit("FETCH_ATHLETE", res.data.data);

                //update search and favourites asynchronously
                dispatch("fetchSearch");
                dispatch("fetchFavourites");
            })
            .catch(err => {
                console.log(err);
            });
    },
    updateAthleteNotes({}, athlete) {
        axios
            .put(`/api/athlete/update_notes/${athlete.id}`, {
                notes: athlete.notes
            })
            .then(res => {
                // notes are changed locally, not update event needed
                // an update on typing events risks inconsistency
            })
            .catch(err => {
                console.log(err);
            });
    },
    updateAthletePerformances({}, athlete) {
        axios
            .put(`/api/athlete/update_performances/${athlete.id}`, {
                performances: JSON.stringify(athlete.performances)
            })
            .then(res => {
                // performances are changed locally, not update event needed
                // an update on typing events risks inconsistency
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
    getYear({ dispatch, commit }) {
        axios
            .get("/api/year/get")
            .then(res => {
                commit("UPDATE_YEARS_ARRAY", res.data);

                //update search asynchronously
                dispatch("fetchSearch");
            })
            .catch(err => {
                console.log(err);
            });
    },
    setYear({ dispatch, commit }, year) {
        axios
            .put("/api/year/set", { year: year })
            .then(res => {
                commit("UPDATE_YEARS_ARRAY", res.data);

                //update search asynchronously
                dispatch("fetchSearch");
                dispatch("fetchFavourites");
            })
            .catch(err => {
                console.log(err);
            });
    },
    fetchSearch({ commit, state }, searchString) {
        if (searchString === undefined) {
            //call has no searchString specified, use previous one
        } else {
            //call has searchString specified, set the stored value new
            state.searchString = searchString;
        }

        if (state.searchString == "") {
            //search string is empty. an empty string produces no results
            commit("FETCH_ATHLETE_SEARCH", []);
        } else {
            //a string is set, therefore make the call
            axios
                .get(`api/search/athletes/${state.searchString}`)
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
