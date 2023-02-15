<template>
    <div id="app">
      <todo-app></todo-app>
    </div>
  </template>
  
  <script>
  import TodoApp from './todoComponent.vue'
  export default {
    name: 'App',
    components: {
      TodoApp
    }
  }
  </script>