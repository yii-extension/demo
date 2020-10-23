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
    },
    methods: {
        blockUser(id) {
            this.$buefy.dialog.confirm({
                title: 'Block user',
                message: 'Are you sure you want to <b>block</b> this user ?',
                confirmText: 'Block user',
                iconPack: 'fas',
                icon: 'fas fa-user-lock',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => { location.href='/admin/block/' + id; }
            })
        },
        unblockUser(id) {
            this.$buefy.dialog.confirm({
                title: 'Unblock user',
                message: 'Are you sure you want to <b>unblock</b> this user ?',
                confirmText: 'Unblock user',
                iconPack: 'fas',
                icon: 'fas fa-user-alt',
                type: 'is-success',
                hasIcon: true,
                onConfirm: () => { location.href='/admin/block/' + id; }
            })
        },
        confirmDelete(id) {
            this.$buefy.dialog.confirm({
                title: 'Deleting user',
                message: 'Are you sure you want to <b>delete</b> this user ?<br>This action cannot be undone.',
                confirmText: 'Delete user',
                iconPack: 'fas',
                icon: 'fas fa-trash',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => { location.href='/admin/delete/' + id; }
            })
        },
        confirmUser(id) {
            this.$buefy.dialog.confirm({
                title: 'Confirm user',
                message: 'Are you sure you want to <b>confirm</b> this user ?<br>This action cannot be undone.',
                confirmText: 'Confirm user',
                iconPack: 'fas',
                icon: 'fas fa-user-times',
                type: 'is-info',
                hasIcon: true,
                onConfirm: () => { location.href='/admin/confirm/' + id; }
            })
        },
        resendPassword(id) {
            this.$buefy.dialog.confirm({
                title: 'Resend password',
                message: 'Are you sure you want to <b>resend password</b> ?<br>This action cannot be undone.',
                confirmText: 'Resend password',
                iconPack: 'fas',
                icon: 'fas fa-envelope',
                type: 'is-info',
                hasIcon: true,
                onConfirm: () => { location.href='/admin/reset/' + id; }
            })
        },
    }
})
