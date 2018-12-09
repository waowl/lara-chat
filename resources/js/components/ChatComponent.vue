<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-default">
                            <div class="card-header">Private Chat App</div>

                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="user in users" :key="user.id" @click="openChat(user)">{{user.name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div v-for="user in users" v-if="user.session" >
                            <message-component  :key="user.id" v-if="user.session.open" @closed="closeChat(user)" :user=user></message-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MessageComponent from './MessageComponent'
    export default {
        components : {
            'message-component' : MessageComponent
        },
        data() {
            return {
                open: true,
                users: []
            }
        },
        methods: {
          closeChat(user) {
              user.session.open = false
          },
          openChat(user) {
             if (user.session) {
                 this.users.forEach((user) => {
                     user.session.open = false
                 })
                 user.session.open = true
             } else {
                 this.createSession(user)
             }
          },

            createSession(user) {
               axios.post('/session/create', {user_id: user.id} )
                   .then(({data}) => {
                       user.session = data.data
                       this.openChat(user)
                   })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            axios.post('/get-friends')
                .then(({data}) => {
                    this.users = data.data
                })
        }
    }
</script>
