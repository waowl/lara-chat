<template>
    <div class="card card-default chat-box">
        <div class="card-header d-flex flex-row justify-content-between">
            <p :class="{'text-danger': session_block}">
                <span>{{user.name}}</span>
                <b v-if="session_block">(Blocked)</b>
            </p>
            <div class="actions">
                <a href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" a>
                    <i class="fas fa-ellipsis-h mr-4"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" v-if="!session_block" href="#" @click.prevent="toggleBlock">Block</a>
                    <a v-else class="dropdown-item" href="#" @click.prevent="toggleBlock">Unblock</a>
                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear Chat</a>
                </div>


                <a href="" @click.prevent="close">
                    <i class="fas fa-times"></i>
                </a>

            </div>
        </div>

        <div class="card-body  " v-chat-scroll>
            <p :class="{'text-right': message.type == 0,  'text-success': (message.read_at != null && message.type == 0)}"
               v-for="message in messages" :key="message.id">
                {{message.message}}
                <br>
                <span class="text-danger" v-if="message.read_at == null && message.type == 0">
                <i class="far fa-clock"></i>
            </span>
                <span v-else>
                <small>{{message.read_at}}</small>
            </span>
            </p>

        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input type="text" v-model="message" class="form-control" placeholder="Enter message"
                       :disabled="session_block">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: [
            'user'
        ],
        data() {
            return {
                messages: [],
                message: '',
                session_block: false
            }
        },
        methods: {
            send() {
                if (this.message) {
                    axios.post(`/session/${this.user.session.id}/send`, {content: this.message, to_user: this.user.id})
                        .then(({data}) => {
                            console.log(data);
                            this.messages.push({
                                id: data,
                                message: this.message,
                                type: 0,
                                send_at: 'Just Now',
                                read_at: null
                            })
                            this.message = ''
                        })
                }

            },
            close() {
                this.$emit('closed')
            },
            clear() {
                axios.get(`/session/${this.user.session.id}/clear`)
                    .then((res) => {
                        this.messages = []
                    })
            },
            toggleBlock() {
                this.session_block ? this.session_block = false : this.session_block = true
            },
            getMessages: function () {
                axios.get(`/session/${this.user.session.id}/chats`)
                    .then(({data}) => {
                        this.messages = data.data
                    })
            },
            read() {
                axios.get(`/session/${this.user.session.id}/read`)
                    .then(({data}) => {
                        console.log(data);
                    })
            }
        },
        created() {
            this.read()
            this.getMessages()
            Echo.private(`Chat.${this.user.session.id}`)
                .listen('PrivateChannelEvent', ev => {
                    this.user.session.open ? this.read() : ''
                    this.messages.push({message: ev.content, type: 1})
                })
                .listen('MessageReadEvent',
                    ev => {
                        this.messages.forEach(message => {
                            message.id == ev.chat.id ? message.read_at = ev.chat.read_at : ""
                        })
                    }
                )
        }
    }
</script>

<style scoped>
    .chat-box {
        height: 400px;
    }

    .card-body {
        overflow-y: scroll;
    }

    .receive {
        float: right;
    }
</style>