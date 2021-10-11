<template>
    <div class="block lg:flex flex-wrap item-center bg-white rounded-md mt-3">
        <div
            @click="activate('pending')"
            class="lg:w-1/4 rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status === 'pending'}"
        >
            <div class="flex items-center text-sm">
                <span class="ml-3 font-medium">Pending</span>
            </div>
        </div>

        <div
            @click="activate('cancelled')"
            class="lg:w-1/4 rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status === 'cancelled'}"
        >
            <div class="flex items-center text-sm">
                <span class="ml-3 font-medium">Cancelled</span>
            </div>
        </div>
        <div
            v-for="custom_status in custom_statuses"
            @click="activate(custom_status.value)"
            class="lg:w-1/4 rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status === custom_status.value}"
        >
            <div class="flex items-center text-sm">
                <span class="ml-3 font-medium">{{ custom_status.name }}</span>
            </div>
        </div>
        <div
            @click="activate('complete')"
            class="lg:w-1/4 rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status === 'complete'}"
        >
            <div class="flex items-center text-sm">
                <span class="ml-3 font-medium">Complete</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['status', 'custom_statuses'],
        emits: ['update:status'],
        data() {
          return {
              customStatus: ''
          }
        },
        mounted() {
          this.customStatus = this.status;
        },
        methods: {
            activate(option) {
                this.$emit('update:status', option);
            },
            activateCustom() {
                this.$emit('update:status', this.$refs.customStatus.value);
            }
        }
    }
</script>

<style scoped>
    .active {
       background: #3490dc;
    }
    .inactive {
       border: solid gray;
    }
</style>

