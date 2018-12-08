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
                                    <li class="list-group-item">Cras justo odio</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <message-component v-if="open" @closed="closeChat"></message-component>
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
                open: true
            }
        },
        methods: {
          closeChat() {
              this.open = !this.open
          }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
