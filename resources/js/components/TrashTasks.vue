<template>
    <div>
        <div class="card px-2 py-3">
            <h2 class="text-xl font-bold mb-3">Trash Task</h2>
        </div>
        <div class="card px-2 py-3 mt-3">
            <deleted-task
                v-for="deletedTask in deletedTasks"
                :key="deletedTask.id"
                :task="deletedTask"
                @deleteTask="removeTask"
            >
            </deleted-task>
        </div>
    </div>
</template>

<script>
    import DeletedTask from './tasks/DeletedTask.vue';
    export default {
        components: {
          DeletedTask
        },
        data() {
            return {
                deletedTasks: []
            }
        },
        methods: {
            removeTask(task) {
                const resIndex = this.deletedTasks.findIndex(res => res.id === task.id);
                this.deletedTasks.splice(resIndex, 1);
            },
        },
        async mounted() {
            await axios.get('/deleted-tasks').then((response) => {
                this.deletedTasks = response.data.data;
            })
        }
    }
</script>
