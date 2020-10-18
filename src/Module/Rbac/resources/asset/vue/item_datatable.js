new Vue({
    el: "#items",
    data() {
        return {
            items: [],
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
            .get('/items')
            .then(response => {
            this.items = response.data
        })
            .catch(function (error) {
            console.log(error);
        })
    }
})
