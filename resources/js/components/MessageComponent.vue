<template>
    <div class="card card-default chat-box">
        <div class="card-header d-flex flex-row justify-content-between">
            <p :class="{'text-danger': this.user.session.blocked}">
                <span>{{user.name}}</span>
                <span class="text-success" v-show="isTyping"">
                    <i class="fas fa-pencil-alt"></i>
                    <i >
                        is typing ...
                    </i>
                </span>
                <b v-if="this.user.session.blocked">(Blocked)</b>
            </p>
            <div class="actions">
                <a href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" a>
                    <i class="fas fa-ellipsis-h mr-4"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a v-if="this.user.session.blocked && can"  class="dropdown-item" href="#" @click.prevent="unblock">Unblock</a>
                    <a class="dropdown-item" v-else href="#" @click.prevent="block">Block</a>
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
                       :disabled="this.user.session.blocked == true">
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
                isTyping: false
            }
        },
        computed: {
            can () {
                return this.user.session.blocked_id === auth.id
            },
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
            block() {
              axios.post(`/session/${this.user.session.id}/block`)
                  .then(res=> {
                    this.user.session.blocked = true
                    this.user.session.blocked_id = auth.id
                  })
            },
            unblock() {
                axios.post(`/session/${this.user.session.id}/unblock`)
                    .then(res=> {
                        this.user.session.blocked = false
                      this.user.session.blocked_id = null
                    })
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
        watch:{
         message (value){
           if(value) {
             console.log(value);
             Echo.private(`Chat.${this.user.session.id}`)
               .whisper('typing', {
                    name: auth.name
               })
           }
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
              .listen('BlockEvent', ev => {
                 ev.blocked ? this.user.session.blocked = true : this.user.session.blocked = false
              })
              .listenForWhisper('typing', ev => {
                this.isTyping = true
                setTimeout(()=>{
                  this.isTyping = false
                }, 2000)
              })
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