<template>
    <div>
        <div class="bg-white overflow-hidden shadow rounded-lg mb-3">
            <div class="p-2 flex justify-between">
                <h2 class="font-bold text-m text-blue-500">{{ task.name }}</h2>
                <div class="flex justify-right -mr-2">
                    <a href="" @click.prevent="displayEditTask" class="mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </a>
                    <a href="" @click.prevent="displayAddSubTask" class="mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="" @click.prevent="deleteTask" class="mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="px-2 py-1" v-if="showAddSubTaskForm">
                <new-task @addTask="addSubTask" :custom_status="custom_statuses"></new-task>
            </div>
            <div class="px-2 py-1" v-if="showEditForm">
                <edit-task :task="task" @editTask="editTaskHandler" :custom_statuses="custom_statuses"></edit-task>
            </div>
        </div>
        <div class="ml-3" v-if="subTasks.length > 0">
            <task-item v-for="subtask in subTasks"
                       :key="subtask.id"
                       :task="subtask"
                       :custom_statuses="custom_statuses"
                       @afterEdit="updateTasks"
                       @afterDelete="removeTask"
                       @updateCount="updateCountRecursive"
            >
            </task-item>
        </div>
    </div>
</template>

<script>
    import NewTask from './NewTask.vue';
    import EditTask from './EditTask.vue';
    export default {
        data() {
          return {
              showAddSubTaskForm: false,
              showEditForm: false,
              subTasks: []
          }
        },
        components: {
          NewTask,
          EditTask
        },
        props: ['task', 'custom_statuses'],
        emits: ['afterEdit', 'afterDelete', 'updateCount'],
        name: 'task-item',
        mounted() {
          this.subTasks = this.task.sub_tasks;
        },
        methods: {
            displayAddSubTask() {
                this.showEditForm = false;
                this.showAddSubTaskForm = !this.showAddSubTaskForm;
            },
            displayEditTask() {
                this.showEditForm = !this.showEditForm;
                this.showAddSubTaskForm = false;
            },
            updateCountRecursive(complete, incomplete) {
                this.$emit('updateCount', complete, incomplete);
            },
            async addSubTask(form) {
                const formData = {...form, parent_id: this.task.id}
                await axios.post('/tasks', formData).then((response) => {
                    this.addSubTaskThenSort(response.data.data)
                    console.log('updatedHere')
                    this.$emit('updateCount', response.data.complete, response.data.incomplete);
                });
                this.showAddSubTaskForm = false;
            },
            async editTaskHandler(form) {
                const formData = {...form, parent_id: this.task.id}
                await axios.put(`/tasks/${this.task.id}`, formData).then((response) => {
                    this.$emit('afterEdit', response.data.data, response.data.complete, response.data.incomplete);
                    this.showEditForm = false;
                });
            },
            async deleteTask() {
                await axios.delete(`/tasks/${this.task.id}`).then((response) => {
                    this.$emit('afterDelete', this.task);
                    this.$emit('updateCount', response.data.complete, response.data.incomplete);
                })
            },
            updateTasks(task, complete, incomplete) {
                this.removeTask(task);
                this.subTasks.push(task);
                this.sortTasks();
                this.$emit('updateCount', complete, incomplete);
            },
            removeTask(task) {
                const resIndex = this.subTasks.findIndex(res => res.id === task.id);
                this.subTasks.splice(resIndex, 1);
            },
            sortTasks() {
                this.subTasks.sort((a, b) => {
                    if (a.order === b.order) {
                        return new Date(b.created_at) - new Date(a.created_at);
                    }
                    return a.order - b.order;
                });
            },
            addSubTaskThenSort(subTask) {
                this.subTasks.push(subTask);
            }
        }
    }
</script>
