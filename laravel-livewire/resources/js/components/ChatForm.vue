<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm"
            placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage" />
        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
            <!-- <button type="button" class="btn btn-primary btn-sm">
                <i class="fa fa-upload" aria-hidden="true"></i>
            </button> -->
            <form @submit="formSubmit" enctype="multipart/form-data">
                <input type="file" id="upload" hidden v-on:change="onImageChange">
                <label for="upload">Choose file</label>
                <!-- <button for="upload" type="submit" class="btn btn-success">Submit</button> -->
            </form>
        </span>
    </div>
</template>
<script>
    export default {
        //Takes the "user" props from <chat-form> … :user="{{ Auth::user() }}"></chat-form> in the parent chat.blade.php.
        props: ["user"],
        data() {
            return {
                newMessage: "",
                image: "",
                messages: [],
                fileData: "",
            };
        },
        methods: {
            sendMessage() {
                //Emit a "messagesent" event including the user who sent the message along with the message content
                this.$emit("messagesent", {
                    user: this.user,
                    //newMessage is bound to the earlier "btn-input" input field
                    message: this.newMessage,
                });
                //Clear the input
                this.newMessage = "";
            },

            onImageChange (e) {
                this.image = e.target.files[0];
                console.log(this.image);

                this.formSubmit(e);
            },

            formSubmit (e) {
                e.preventDefault();
                // let currentObj = this;
 
                const config = {
                    headers: { 
                        'content-type': 'application/json',
                        'Access-Control-Allow-Origin':  'https://alegralabs.com/abhijit/laravel-chat/public',
                        'Access-Control-Allow-Headers':  'Content-Type, X-Auth-Token, Authorization, Origin',
                        'Access-Control-Allow-Methods':  'POST, PUT, GET'
                    }

                }
                let v = this;
                let formData = new FormData();
                // image.value="test";
                formData.append('image', this.image);
                console.log("formdata",formData)
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
                        user: v.user,
                        file: response.data.message.file
                    });
                    
                })
                .catch(function (error) {
					console.log(error)
                    // currentObj.output = error;
                });

               
            },
        },
    };

</script>

<style scoped>
label{
  display: inline-block;
  background-color: indigo;
  color: white;
  padding: 0.5rem;
  font-family: sans-serif;
  border-radius: 0.3rem;
  cursor: pointer;
  /* margin-top: 1rem; */
}
</style>
