<template>
    <div>
        <div>
            <div class="text-info" v-if="submitting">Submitting ..</div>
            <div class="text-success" v-if="submitted">Task Added</div>
            <new-task @addTask="addTaskHandler"></new-task>
        </div>
        <div class="mt-3">
            <task-item></task-item>
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
              submitted: false
          }
        },
        methods: {
            async addTaskHandler(form) {
                this.submitting = true;
                this.submitted = false;
                await axios.post('/tasks', form).then(() => {
                    this.submitting = false;
                    this.submitted = true;
                });
            }
        }
    }
</script>
