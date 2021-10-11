<template>
    <div>
        <form @submit.prevent="submitTask">
            <div class="flex flex-wrap -mr-4">
                <div class="mr-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text"
                               name="name"
                               id="name"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Task Name"
                               v-model.trim="form.name"
                               @blur="validateInput"
                        />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" v-if="taskNameValidity === 'invalid'">
                            <!-- Heroicon name: solid/exclamation-circle -->
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <p
                        class="mt-2 text-sm text-red-600"
                        v-if="taskNameValidity === 'invalid'"
                    >
                        Task name must not be empty
                    </p>
                </div>
                <div class="mr-4">
                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input
                            type="number"
                            min="0"
                            class="block w-full border border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md"
                            v-model="form.order"
                        />
                    </div>
                </div>
            </div>
            <task-status :status="form.status" @update:status="form.status = $event" :custom_statuses="custom_statuses"></task-status>
            <p class="mt-2 text-sm text-red-600" v-if="form.status.trim() === ''">
                Task status must not be empty
            </p>
            <div class="mt-2">
                <button type="submit"
                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit Task
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import TaskStatus from './TaskStatus.vue';

export default {
    components: {
        TaskStatus
    },
    data() {
        return {
            form: {
                name: '',
                order: 0,
                status: ''
            },
            taskNameValidity: 'pending'
        }
    },
    props: ['task', 'custom_statuses'],
    mounted() {
      this.form.name = this.task.name;
      this.form.order = this.task.order;
      this.form.status = this.task.status;
    },
    emits: ['editTask'],
    methods: {
        submitTask() {
            if (this.form.name.trim() === '') {
                this.taNameValidity = 'invalid';
                return;
            }
            if (this.form.status === '') {
                return;
            }
            this.$emit('editTask', this.form);
        },
        validateInput() {
            if (this.form.name === '') {
                this.taskNameValidity= 'invalid';
            } else {
                this.taskNameValidity = 'valid';
            }
        }
    }
}
</script>
