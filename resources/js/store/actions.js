let actions = {
    fetchFavourites({ commit }) {
        axios
            .get("api/favourites")
            .then(res => {
                commit("FETCH_FAVOURITES", res.data);
            })
            .catch(err => {
                console.log(err);
            });
    },
    createAthlete({ commit }, athlete) {
        axios
            .post("/api/athlete/create", athlete)
            .then(res => {
                commit("CREATE_ATHLETE", res.data.data);
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
    }
};

export default actions;
