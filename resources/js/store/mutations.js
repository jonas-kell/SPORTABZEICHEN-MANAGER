let mutations = {
    FETCH_FAVOURITES(state, favourites) {
        return (state.favourites = favourites);
    },
    CREATE_POST(state, post) {
        state.posts.unshift(post);
    },
    DELETE_POST(state, post) {
        let index = state.posts.findIndex(item => item.id === post.id);
        state.posts.splice(index, 1);
    }
};
export default mutations;
