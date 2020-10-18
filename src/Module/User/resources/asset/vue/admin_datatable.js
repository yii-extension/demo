new Vue({
    el: "#users",
    data() {
        return {
            users: [],
            isPaginated: true,
            paginationPosition: 'bottom',
            defaultSortDirection: 'asc',
            sortIcon: 'arrow-up',
            sortIconSize: 'is-small',
            currentPage: 1,
            perPage: 5,
            bordered: true
      }
    },
    mounted: function() {
        axios
            .get('/users')
            .then(response => {
            this.users = response.data
        })
            .catch(function (error) {
            console.log(error);
        })
    }
})
