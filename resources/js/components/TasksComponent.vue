<template>
    <div>
        <div class="card px-2 py-3">
            <div class="text-info" v-if="submitting">Submitting ..</div>
            <div class="text-success" v-if="submitted">Task Added</div>
            <h2 class="text-xl font-bold mb-3">Add New Task</h2>
            <new-task @addTask="addTaskHandler"></new-task>
        </div>
        <div class="card px-2 py-3 mt-3">
            <task-item v-for="task in tasks"
                       :key="task.id"
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

    export default {
        components: {
            NewTask,
            TaskItem
        },
        data() {
          return {
              submitting: false,
              submitted: false,
              tasks: []
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
        }
    }
</script>
