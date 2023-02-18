<template>
    <div class="container" style="max-width: 600px">
      <div class="toggle-frontend-only">
        Frontend only
        <div v-if="frontendOnly" @click=" frontendOnly=!frontendOnly">
          <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
              x="0px" y="0px" width="30px" height="15px" viewBox="0 0 122.878 73.391" enable-background="new 0 0 122.878 73.391" xml:space="preserve">
              <g>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M35.691,0h51.495c19.631,0,35.691,16.513,35.691,36.695l0,0 c0,20.182-16.062,36.695-35.691,36.695H35.691C16.062,73.391,0,56.877,0,36.695l0,0C0,16.513,16.061,0,35.691,0L35.691,0z M86.183,7.863c16.184,0,29.309,13.125,29.309,29.309S102.366,66.48,86.183,66.48S56.874,53.355,56.874,37.172 S69.999,7.863,86.183,7.863L86.183,7.863z"/>
              </g>
            </svg>
        </div>

        <div v-else @click=" frontendOnly=!frontendOnly">
          <svg width="50px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="7" width="20" height="10" rx="5" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="7" cy="12" r="5" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

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
            <th scope="col" class="text-center">Task</th>
            <th scope="col" style="width: 120px" class="text-center">Status</th>
            <th scope="col" style="width: 150px" class="text-center">Updated at</th>
            <th scope="col" class="text-center">Delete</th>
            <th scope="col" style="width: 120px" class="text-center">Edit name</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(task, index) in tasks" :key="index">
            <td class="text-center">
              <span :class="{ 'line-through': task.status === 'finished' }">
                {{ task.name }}
              </span>
            </td>
            <td class="text-center">
              <span
                class="pointer noselect"
                @click="changeStatus(index, task)"
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
                {{ task.updated_at }}
            </td>
            <td class="text-center">
              <div @click="deleteTask(task.id)">
                <span class="fa fa-trash pointer"></span>
              </div>
            </td>
            <td class="text-center">
              <div @click="editTask(task)">
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
      let vm = this;

      if (vm.frontendOnly) {
        vm.tasks.push({
            id: "1",
            name: "Demo Task 1",
            status: "to-do",
            createdAt: "10-2-2023 12:23:34",
            updated_at: "10-2-2023 12:23:56"
          },
          {
            id: "2",
            name: "Demo Task 2",
            status: "in-progress",
            createdAt: "11-1-2023 11:45:45",
            updated_at: "11-1-2023 11:45:34"
          },
          {
            id: "3",
            name: "Demo Task 3",
            status: "finished",
            createdAt: "10-12-2021 10:32:12",
            updated_at: "10-12-2021 10:32:23"
          },)
      } else {
        /**
         * GET data from db with API
         */
         vm.getTaskListFromDB();
      }
    },

    watch: {
      frontendOnly: function (val) {
        if (val !== this.frontendOnly) {
          this.frontendOnly = val;
        }
        
        //IF we change from frontendONLY to backend also, updated the list from DB
        if (val === false) {
          this.getTaskListFromDB();
        }
      }

    },
    
    methods: {
      /**
      * GET data from db with API
      */
      getTaskListFromDB() {
        axios.get("/api/items")
        .then(res => {
            this.tasks = res.data;
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
      changeStatus(index, task) {
        let newIndex = this.statuses.indexOf(this.tasks[index].status);
        if (++newIndex > 2) newIndex = 0;
        this.tasks[index].status = this.statuses[newIndex];
        this.tasks[index].updated_at = this.getCurrentDate();

         /*Update task from db with API call */
        if (!this.frontendOnly) {
          task.status = this.statuses[newIndex];

         axios.put("/api/item/"+ task.id, {
                item: task
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
        let vm = this;

        if (vm.frontendOnly) {
          let allTasks = [...vm.tasks];

          let filtered_arr = allTasks.filter( function(val) {
              if (val.id !== index ) {
                return val;
              }
            })
          
          //Update the list in data.
          vm.tasks = filtered_arr;
        } else {
          /**
         * Delete task from db with API
         */
         axios.delete("/api/item/"+index)
            .then(res => {
                if(res.status == 200){
                  vm.$emit('Task deleted')
                }
                vm.getTaskListFromDB();
            })
            .catch(err=>{
                console.log(err)
            })
        }
        
      },
  
      /**
       * Edit task
       */
      editTask(taskToEdit) {
        this.task = taskToEdit.name;
        this.editedTask = taskToEdit;        
      },
  
      getCurrentDate: function() {
        let date = new Date();

          let day = date.getDate();
          let month = date.getMonth() + 1;
          let year = date.getFullYear();
          let hour = date.getHours();
          let min = date.getMinutes();
          let sec = date.getSeconds();

          return `${day}-${month}-${year} ${hour}:${min}:${sec}`;
      },

      /**
       * Add / Update task
       */
      submitTask() {
        let vm = this;

        if (vm.task.length === 0) return;

        /* We need to update the task */
        if (vm.editedTask != null) {
          
          vm.editedTask.name = vm.task;
          vm.editedTask.updated_at = vm.getCurrentDate();

          /** Update task from db with API */
         if (!vm.frontendOnly) { 
          axios.put("/api/item/"+ vm.editedTask.id, {
                  item: vm.editedTask
              })
              .then(res => {
                  if(res.status == 200){
                    vm.$emit('Task changed');
                    vm.getTaskListFromDB();
                  }
              })
              .catch(err=>{
                  console.log(err)
              })
          }

          vm.editedTask = null;
        } else {
          /* We need to add new task */

          if (vm.frontendOnly) {
            vm.tasks.push({
              name: vm.task,
              status: "todo",
              created_at: vm.getCurrentDate(),
              updated_at: vm.getCurrentDate(),
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
              created_at: vm.getCurrentDate(),
              updated_at: vm.getCurrentDate(),
            }

            axios.post('/api/item/store',{item: newTask})
            .then(response => {
                if(response.status == 200){
                    this.task = ''
                }
                this.getTaskListFromDB();
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

  .toggle-frontend-only {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
  }
  </style>