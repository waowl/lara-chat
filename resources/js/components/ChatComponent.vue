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
                                    <li class="list-group-item d-flex justify-content-between" v-for="user in users" :key="user.id" >
                                        <a href="" @click.prevent="openChat(user)">{{user.name}}</a>
                                        <div class="info">
                                            <span v-if="hasUnread(user)" class="badge badge-danger mr-2">{{user.session.unread_count}}</span>
                                            <span class="online" v-if="user.online"></span>
                                        </div>
                                    </li>
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
            hasUnread(user) {
                return    (user.session && user.session.unread_count != 0 )
            },
          closeChat(user) {
              user.session.open = false
          },
          openChat(user) {
             if (user.session) {
                 this.users.forEach((u) => {
                     if (u.session) {
                        u.session.open = false
                     }
                 })
                 user.session.unread_count = 0
                 user.session.open = true
             } else {
                 this.createSession(user)
             }
          },

            createSession(user) {
               axios.post('/session/create', {user_id: user.id} )
                   .then(({data}) => {
                       user.session = data.data
                       user.session.open = true
                   })
            },
            listenForEverySession(user) {
                Echo.private(`Chat.${user.session.id}`)
                    .listen('PrivateChannelEvent', ev => {
                        console.log('got ' + ev +" count :" + user.session.unread_count);
                        user.session.open ? "" : user.session.unread_count++
                        console.log('new count ' + user.session.unread_count);

                    })
            }

        },
        created() {
            axios.post('/get-friends')
                .then(({data}) => {
                    this.users = data.data
                    this.users.forEach(user => {
                        if (user.session) {
                            this.listenForEverySession(user)
                        }
                    })
                })

            Echo.join('Chat')
                .here(users => {
                    users.forEach(user => {
                        this.users.forEach(u => {
                             u.id == user.id ? u.online = true : ""
                        })
                    } )

                })
                .joining(user => {
                    this.users.forEach(u => {
                        u.id === user.id ? u.online = true : u.online = false
                    })
                })
                .leaving(user => {
                    this.users.forEach(u => {
                        u.id === user.id ? u.online = false : ''
                    })
                })
            Echo.channel('Chat')
                .listen('SessionEvent', ev => {
                    let user =  this.users.find(user => user.id == ev.session_by.id)
                    user.session = ev.session
                    this.listenForEverySession(user)
                })
        }
    }
</script>
<style>
    .online{
        margin-top: 5px;
        height: 10px;
        width: 10px;
        margin-top: 5px;
        display: block;
        border-radius: 50%;
        background-color: green;
    }
    .info{
        display: flex;
        align-items: flex-start;

    }
</style>