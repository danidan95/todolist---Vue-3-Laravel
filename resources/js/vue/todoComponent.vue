<template>
    <div class="container" style="max-width: 600px">
      <div>
        <label for="frontendOnly">Frontend only</label>
        <input id="frontendOnly" type="checkbox" v-model="frontendOnly">
      </div>
      <!-- Heading -->
      <h2 class="text-center mt-5">My Vue 3 - Laravel Todo App</h2>
  
      <!-- Input -->
      <div class="d-flex mt-5">
        <input
          type="text"
          v-model="task"
          placeholder="Enter task"
          class="w-100 form-control"
          @keypress.enter="submitTask"
        />
        <button class="btn btn-warning rounded" @click="submitTask">
          SUBMIT
        </button>
      </div>
  
      <!-- Task table -->
      <table class="table table-bordered mt-5">
        <thead>
          <tr>
            <th scope="col">Task</th>
            <th scope="col" style="width: 120px">Status</th>
            <th scope="col" style="width: 150px" class="text-center">Created at</th>
            <th scope="col" class="text-center">Delete</th>
            <th scope="col" class="text-center">Edit</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(task, index) in tasks" :key="index">
            <td>
              <span :class="{ 'line-through': task.status === 'finished' }">
                {{ task.name }}
              </span>
            </td>
            <td>
              <span
                class="pointer noselect"
                @click="changeStatus(index)"
                :class="{
                  'text-danger': task.status === 'to-do',
                  'text-success': task.status === 'finished',
                  'text-warning': task.status === 'in-progress',
                }"
              >
                {{ capitalizeFirstChar(task.status) }}
              </span>
            </td>
            <td class="text-center">
                {{ task.createdAt }}
            </td>
            <td class="text-center">
              <div @click="deleteTask(index)">
                <span class="fa fa-trash pointer"></span>
              </div>
            </td>
            <td class="text-center">
              <div @click="editTask(index)">
                <p class="fa fa-pen pointer"></p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios'
  export default {
    name: "Todo App",
    props: {
      msg: String,
    },
  
    data() {
      return {
        frontendOnly: true,
        task: "",
        editedTask: null,
        statuses: ["to-do", "in-progress", "finished"],
  
        /* Status could be: 'to-do' / 'in-progress' / 'finished' */
        tasks: [ ],
      };
    },

    created() {
      if (this.frontendOnly) {
        this.tasks.push({
            name: "Demo Task 1",
            status: "to-do",
            createdAt: "10-2-2023 12:23"
          },
          {
            name: "Demo Task 2",
            status: "in-progress",
            createdAt: "11-1-2023 11:45"
          },
          {
            name: "Demo Task 3",
            status: "finished",
            createdAt: "10-12-2021 10:32"
          },)
      } else {
        /**
         * GET data from db with API
         */
         this.getTaskListFromDB;
      }
    },

    watch: {
      frontendOnly: function (val) {
        if (val !== this.frontendOnly) {
          this.frontendOnly = val;
        }
        console.log("frontendOnly ", this.frontendOnly)
      }

    },
    
    methods: {
      /**
      * GET data from db with API
      */
      getTaskListFromDB() {
                axios.get("/api/items")
                .then(res => {
                    this.tasks = res.data
                })
                .catch(err => {
                    console.log(err)
                })
      },

      /**
       * Capitalize first character
       */
      capitalizeFirstChar(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
      },
  
      /**
       * Change status of task by index
       */
      changeStatus(index) {
        if (this.frontendOnly) {
          let newIndex = this.statuses.indexOf(this.tasks[index].status);
          if (++newIndex > 2) newIndex = 0;
          this.tasks[index].status = this.statuses[newIndex];
        } else {
          /**
         * Update task from db with API call
         */
         axios.put("/api/item/"+ index, {
                item: this.task
            })
            .then(res => {
                if(res.status == 200){
                    this.$emit('taskChanged')
                }
            })
            .catch(err=>{
                console.log(err)
            })
        
        }
        
      },
  
      /**
       * Deletes task by index
       */
      deleteTask(index) {
        if (this.frontendOnly) {
          this.tasks.splice(index, 1);
        } else {
          /**
         * Delete task from db with API
         */
         axios.delete("/api/item/"+index)
            .then(res => {
                if(res.status == 200){
                    this.$emit('Task deleted')
                }
            })
            .catch(err=>{
                console.log(err)
            })
        }
        
      },
  
      /**
       * Edit task
       */
      editTask(index) {
        if (this.frontendOnly) {
          this.task = this.tasks[index].name;
          this.editedTask = index;
        } else {
          /**
         * Update task from db with API
         */
         axios.put("/api/item/"+ index, {
                item: this.task
            })
            .then(res => {
                if(res.status == 200){
                    this.$emit('Task changed')
                }
            })
            .catch(err=>{
                console.log(err)
            })
        }
        
      },
  
      /**
       * Add / Update task
       */
      submitTask() {
        console.log("SUBMIT requested");
        if (this.task.length === 0) return;
  
        /* We need to update the task */
        if (this.editedTask != null) {
          this.tasks[this.editedTask].name = this.task;
          this.editedTask = null;
        } else {
          /* We need to add new task */
          const date = new Date();

          let day = date.getDate();
          let month = date.getMonth() + 1;
          let year = date.getFullYear();
          let hour = date.getHours();
          let min = date.getMinutes();

          let currentDate = `${day}-${month}-${year} ${hour}:${min}`;

          if (this.frontendOnly) {
            this.tasks.push({
              name: this.task,
              status: "todo",
              createdAt: currentDate,
            });

          } else {
            /**
             * ADD new task in db via API
             */
            if(this.task === ""){
                return;
            } 

            let newTask = {
              name: this.task,
              status: "todo",
              createdAt: currentDate,
            }

            axios.post('/api/item/store',{item: newTask})
            .then(response => {
                if(response.status == 200){
                    this.task = ''
                    this.getTaskListFromDB();
                }
                console.log(response);
            })
            .catch(error => {
                console.log(error)
            })
          }
          
        }
  
        this.task = "";
      },
    },
  };
  </script>
  
  <style scoped>
  .pointer {
    cursor: pointer;
  }
  .noselect {
    -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
    -khtml-user-select: none; /* Konqueror HTML */
    -moz-user-select: none; /* Old versions of Firefox */
    -ms-user-select: none; /* Internet Explorer/Edge */
    user-select: none; /* Non-prefixed version, currently
                                    supported by Chrome, Edge, Opera and Firefox */
  }
  .line-through {
    text-decoration: line-through;
  }

  .btn {
    margin: 0 10px;
  }
  </style>