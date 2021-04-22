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
    createPost({ commit }, post) {
        axios
            .post("/api/posts", post)
            .then(res => {
                commit("CREATE_POST", res.data);
            })
            .catch(err => {
                console.log(err);
            });
    },
    deletePost({ commit }, post) {
        axios
            .delete(`/api/posts/${post.id}`)
            .then(res => {
                if (res.data === "ok") commit("DELETE_POST", post);
            })
            .catch(err => {
                console.log(err);
            });
    }
};

export default actions;
