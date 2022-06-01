<template>
  <!-- <ul class="chat">
    <li class="left clearfix" v-for="message in messages" :key="message.id">

      <div class="clearfix" >
        <div class="header">
          <strong>
          IF:  {{ message.user.name }}
          </strong>
        </div>
        <p>
          <span v-if="message.file">
             <a :href="`/laravel-chat-main/storage/app/public/${message.file}`" target="_blank">Click to view</a>
            </span>
          <span v-else>{{ message.message }}</span>
        </p>
      </div>
     
    </li>
  </ul> -->
  <div >
 
    <div v-for="message in messages" :key="message.id" >
        {{message}}
        <p style="color:red;padding-left:100px" v-if="message.from.id == user || message.from == user">
            <span>U: {{ message.from.name }} </span>
            <span>M: {{message.message}}</span>
        </p>
        <p style="color:green" v-if="(message.from.id == chat_user.id && message.to.id == user ) || (message.from == chat_user.id && message.to == user) ">
           <span>U: {{ message.from.name }}</span>
            <span>M: {{message.message}}</span>
        </p>
      
          <!-- <p style="color:red;padding-left:100px" v-if="message.user.id == user">
            <span>U: {{message.user.name}}</span>
            <span>M: {{message.message}}</span>
          </p>
          <p style="color:green" v-if="message.user.id == chat_user" >
            <span>U: {{message.user.name}}</span>
            <span>M: {{message.message}}</span>
          </p>   -->


          <!-- <p v-if="message.user.id == user">{{message}}</p>
          <p v-if="message.user.id == chat_user && message.to == user">{{message}}</p> -->
    </div>
      <!-- <div  v-for="message in messages" :key="message.id">
        <div v-if="message.from == user">
            <p style="color:black">{{message.user.name}}</p>
            <p style="color:red">{{message.message}}</p>
        </div>
        <div v-if="message.from == chat_user && message.to == user">
            <p style="color:black">{{message.user.name}}</p>
            <p style="color:green">{{message.message}}</p>
        </div> -->
  </div>
</template>
<script>
export default {
  props: ["messages","user","chat_user"]
};
</script>

<style scoped>
  .from{
    padding-left:200px;
  }
</style>