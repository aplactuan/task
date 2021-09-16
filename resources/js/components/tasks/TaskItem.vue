<template>
    <div>
        {{ task.name }}
    </div>
</template>

<script>
    export default {
        props: ['task'],
        computed: {
            taskDate() {
                return new Date(this.task.created_at)
            }
        }
    }
</script>
