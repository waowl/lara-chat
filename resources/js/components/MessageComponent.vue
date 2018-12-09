<template>
    <div class="card card-default chat-box">
        <div class="card-header d-flex flex-row justify-content-between">
            <p :class="{'text-danger': session_block}">
                <span>{{user.name}}</span>
            <b v-if="session_block">(Blocked)</b>
            </p>
            <div class="actions">
                <a href=""  role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" a>
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
            <p  v-for="message in messages" :key="message.body">{{message.body}}</p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input type="text" v-model="message" class="form-control"  placeholder="Enter message" :disabled="session_block">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: [
            'user'
        ],
        data(){
            return {
                messages: [],
                message : '',
                session_block: false
            }
        },
        methods: {
            send() {
                let message = {'body': this.message}
                this.messages.push(message)
                this.message = ''
            },
            close() {
                this.$emit('closed')
            },
            clear() {
                this.messages = []
            },
            toggleBlock() {
                this.session_block ? this.session_block = false : this.session_block = true
            }
        }
    }
</script>

<style scoped>
    .chat-box{
        height: 400px;
    }
    .card-body{
        overflow-y: scroll;
    }
</style>