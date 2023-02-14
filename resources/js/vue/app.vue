<template>
    <div class="todoListContainer">
        <div class="heading">
            <h2 id="title">Todo List</h2>
            <add-item-form/>
        </div> 
        <list-view />
    </div>
</template>

<script>
import addItemForm from './addItemForm.vue';
import listView from './listView.vue';

export default {
    setup: () => ({
        title: 'How To Install Vue 3 in Laravel 9 From Scratch'
    }),
    components: {
        addItemForm,
        listView,
    }
}
</script>

<style>
.todoListContainer {
    width: 350px;
    margin: auto;
}

.heading {
    background: #e6e6e6;
    padding: 10px;
}

#title {
    text-align: center;
}

</style>