<template>
    <div>
        <div>
            <a href="" @click.prevent="newStatusIsOpen = true">New Status</a>
            <new-status
                :is-open="newStatusIsOpen"
                @closeModal="newStatusIsOpen = false"
                @addCustomStatus="addCustomStatusHandler"
            >
            </new-status>
        </div>
        <div class="card px-2 py-3">
            <div class="text-info" v-if="submitting">Submitting ..</div>
            <div class="text-success" v-if="submitted">Task Added</div>
            <h2 class="text-xl font-bold mb-3">Add New Task</h2>
            <new-task @addTask="addTaskHandler" :custom_status="custom_statuses"></new-task>
        </div>
        <div class="card px-2 py-3 mt-3">
            <task-item v-for="task in tasks"
                       :key="task.id"
                       :custom_statuses="custom_statuses"
                       :task="task"
                       @afterEdit="replaceTaskAndSort"
                       @afterDelete="removeTask"
            >
            </task-item>
        </div>
    </div>
</template>

<script>
    import NewTask from './tasks/NewTask.vue';
    import TaskItem from './tasks/TaskItem.vue';
    import NewStatus from './tasks/NewStatus.vue';

    export default {
        components: {
            NewTask,
            TaskItem,
            NewStatus
        },
        data() {
          return {
              submitting: false,
              submitted: false,
              tasks: [],
              custom_statuses: [],
              newStatusIsOpen: false
          }
        },
        methods: {
            async addTaskHandler(form) {
                this.submitting = true;
                this.submitted = false;
                await axios.post('/tasks', form).then((response) => {
                    this.submitting = false;
                    this.submitted = true;
                    this.tasks.push(response.data.data);
                    this.sortTasks();
                });
            },
            async addCustomStatusHandler(form) {
                console.log(form);
                await axios.post('/custom-statuses', form).then((response) => {
                    this.custom_statuses.push(response.data.data);
                    this.newStatusIsOpen = false;
                });
            },
            sortTasks() {
                this.tasks.sort((a, b) => {
                    if (a.order === b.order) {
                        return new Date(b.created_at) - new Date(a.created_at);
                    }
                    return a.order - b.order;
                });
            },
            removeTask(task) {
                const resIndex = this.tasks.findIndex(res => res.id === task.id);
                this.tasks.splice(resIndex, 1);
            },
            replaceTaskAndSort(task) {
               this.removeTask(task);
               this.tasks.push(task);
               this.sortTasks();
            }
        },
        async mounted() {
            await axios.get('/tasks').then(response => this.tasks = response.data.data);
            await axios.get('/custom-statuses').then(response => this.custom_statuses = response.data.data);
        }
    }
</script>
