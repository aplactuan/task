<template>
    <div class="bg-white overflow-hidden shadow rounded-lg mb-3">
        <div class="p-2 flex justify-between">
            <h2 class="font-bold text-m text-blue-500">{{ task.name }}</h2>
            <div class="flex justify-right -mr-2">
                <a href="" @click.prevent="restoreTask" class="mr-2">
                    Restore
                </a>
                <a href="" @click.prevent="deleteTask" class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['task'],
        emits: ['deleteTask'],
        methods: {
            async restoreTask() {
                await axios.put(`/deleted-tasks/restore/${this.task.id}`).then((response) => {
                    this.$emit('deleteTask', this.task)
                })
            },
            async deleteTask() {
                await axios.put(`/deleted-tasks/delete/${this.task.id}`).then((response) => {
                    this.$emit('deleteTask', this.task)
                })
            }
        }
    }
</script>
