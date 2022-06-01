<template>
    <div class="container mx-auto">
        <div class="min-w-full border rounded grid grid-cols-3">
            <div class="border-r border-gray-300 lg:col-span-1">
                <div class="mx-3 my-3">
                </div>
                <div class="w-full">
                    <div class="relative flex items-center p-3 border-b border-gray-300">
                        <img class="object-cover w-10 h-10 rounded-full"
                            src="https://cdn.pixabay.com/photo/2018/01/15/07/51/woman-3083383__340.jpg"
                            alt="username" />
                        <span class="block ml-2 font-bold text-gray-600">{{chat_user.name}}</span>
                        <span v-if="this.active == 1" class="absolute w-3 h-3 bg-green-600 rounded-full left-10 top-3">
                        </span>
                    </div>
                    <div class="relative w-full p-6 overflow-y-auto h-[30rem]">
                        <ul class="space-y-2" v-for="message1 in messages1" :key="message1.id">
                            <!-- from user -->
                            <li class="flex justify-end" v-if="(message1.from.id == user.id && message1.to.id == chat_user.id)
                             || (message1.from == user.id)">
                                <div class="relative max-w-xl px-4 py-2 text-gray-700 bg-gray-100 rounded shadow">
                                    <span class="block" v-if="message1.file">
                                        <a class="no-underline hover:underline text-blue-600"
                                            :href="`/storage/${message1.file}`" target="_blank">Click to view</a>
                                    </span>
                                    <span class="block" v-else>{{message1.message}}</span>
                                </div>
                            </li>
                            <!-- to user -->
                            <li class="flex justify-start" v-else-if="(message1.from.id == chat_user.id && message1.to.id == user.id)
                                || (message1.from == chat_user.id && message1.to == user.id)">
                                <div class="relative max-w-xl px-4 py-2 text-gray-700 rounded shadow">
                                    <span class="block" v-if="message1.file">
                                        <a class="no-underline hover:underline text-blue-600"
                                            :href="`/storage/${message1.file}`" target="_blank">
                                            Click to view
                                        </a>
                                    </span>
                                    <span class="block" v-else>{{message1.message}}</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-between w-full p-3 border-t border-gray-300">
                        <form @submit="formSubmit" enctype="multipart/form-data">
                            <input type="file" ref="file" id="upload" hidden v-on:change="onImageChange">
                            <button @click="$refs.file.click()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </button>
                        </form>


                        <input type="text" placeholder="Message"
                            class="block w-full py-2 pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700"
                            name="message" v-model="newMessage" @keyup.enter="sendMessage" />
                        <button type="submit" id="btn-chat" @click="sendMessage">
                            <svg class="w-5 h-5 text-gray-500 origin-center transform rotate-90"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
            <div class=" lg:col-span-2 lg:block ">
                <!-- video Call Panel -->
                <VideoCall :auth_user="user" :chat_user="chat_user" :allusers="allusers" :turn_url="turn_url"
                :turn_username="turn_username" :turn_credential="turn_credential"/>
            </div>
        </div>
    </div>
</template>
<script>
    import VideoCall from './VideoCall.vue'
    export default {
        //Takes the "user" props from <chat-form> … :user="{{ Auth::user() }}"></chat-form> in the parent chat.blade.php.
        // props: ["user","chat_user"],
        props: ["messages1", "user", "chat_user","allusers", "turn_url",
            "turn_username",
            "turn_credential",],
        components: {
            VideoCall
        },
        data() {
            return {
                newMessage: "",
                image: "",
                messages: [],
                fileData: "",
                from: "",
                to: "",
                active:'',
            };
        },
        created(){
            setInterval(() => {
                this.checkActive(this.chat_user);
            }, 3000)
        },
        methods: {
            sendMessage() {
                //Emit a "messagesent" event including the user who sent the message along with the message content
                // console.log("Chat User",this.chat_user)

                console.log(this.user.id)
                this.$emit("messagesent", {
                    from: this.user,
                    to: this.chat_user,
                    //newMessage is bound to the earlier "btn-input" input field
                    message: this.newMessage,
                });
                //Clear the input
                this.newMessage = "";
            },

            onImageChange(e) {
                this.image = e.target.files[0];
                console.log("image", this.image);
                this.formSubmit(e);
            },

            formSubmit(e) {
                e.preventDefault();
                // let currentObj = this;

                const config = {
                    headers: {
                        'content-type': 'application/json',
                        'Access-Control-Allow-Origin': '*',
                        'Access-Control-Allow-Headers': 'Content-Type, X-Auth-Token, Authorization, Origin',
                        'Access-Control-Allow-Methods': 'POST, PUT, GET'
                    }

                }
                let v = this;
                let formData = new FormData();
                // image.value="test";
                formData.append('image', this.image);
                formData.append('to', this.chat_user.id);
                console.log("formdata", formData)
                axios.post('formSubmit', formData, config)
                    .then(function (response) {
                        console.log(response)

                        // let user_message = {
                        //     user: response.data,
                        //     file: response.data.message.file
                        // };
                        // messages.value.push(user_message);

                        // v.fileData = response.data.message.file

                        v.$emit("messagesent", {
                            from: v.user,
                            to: v.chat_user,
                            file: response.data.message.file
                        });

                    })
                    .catch(function (error) {
                        console.log(error)
                        // currentObj.output = error;
                    });


            },

            checkActive(chat_user)
            {
                // console.log('chat user',chat_user);
                axios.get('/active/'+chat_user.id).then(response => {
                    console.log(response.data);
                    this.active = response.data
                });
            }
        },
    };

</script>

<style>

</style>
