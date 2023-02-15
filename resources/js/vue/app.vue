<template>
    <div id="app">
      <todo-component />
    </div>
  </template>
  
  <script>
  import TodoComponent from './todoComponent.vue'
  export default {
    name: 'App',
    components: {
      TodoComponent
    }
  }
  </script>