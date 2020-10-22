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
    },
    methods: {
        confirmDelete(id) {
            this.$buefy.dialog.confirm({
                title: 'Deleting item',
                message: 'Are you sure you want to <b>delete</b> this item ?<br>This action cannot be undone.',
                confirmText: 'Delete item',
                iconPack: 'fas',
                icon: 'fas fa-trash',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => { location.href='/item/delete/' + id; }
            })
        },
    }
})
