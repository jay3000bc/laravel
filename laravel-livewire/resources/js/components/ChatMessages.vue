<template>
  <ul class="chat">
    <li class="left clearfix" v-for="message in messages" :key="message.id">
      <div class="clearfix">
        <div class="header">
          <strong>
            {{ message.user.name }}
          </strong>
        </div>
        <p>
          <span v-if="message.file">
             <a :href="message.file" target="_blank">Click to view</a>
            </span>
          <span v-else>{{ message.message }}</span>
        </p>
      </div>
    </li>
  </ul>
</template>
<script>
export default {
  props: ["messages"],
};
</script>