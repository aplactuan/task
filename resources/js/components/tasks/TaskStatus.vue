<template>
    <div class="flex flex-wrap item-center bg-white rounded-md mt-3">
        <div
            @click="activate('pending')"
            class="rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status === 'pending'}"
        >
            <div class="flex items-center text-sm">
                <span class="ml-3 font-medium">Pending</span>
            </div>
        </div>

        <div
            @click="activate('complete')"
            class="rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status === 'complete'}"
        >
            <div class="flex items-center text-sm">
                <span class="ml-3 font-medium">Complete</span>
            </div>
        </div>

        <div
            @click="activate('cancelled')"
            class="rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status === 'cancelled'}"
        >
            <div class="flex items-center text-sm">
                <span class="ml-3 font-medium">Cancelled</span>
            </div>
        </div>

        <div
            @click="activate(customStatus)"
            class="rounded-md relative border p-2 flex-1 cursor-pointer focus:outline-none ml-2"
            :class="{active: status !== 'cancelled' && status !== 'pending' && status !== 'complete'}"
        >
            <div class="flex items-center text-sm">
                <div class="w-full sm:max-w-xs">
                    <input type="text"
                           name="status"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="Custom Status"
                           v-model="customStatus"
                           @change="activateCustom"
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['status'],
        emits: ['update:status'],
        data() {
          return {
              customStatus: ''
          }
        },
        methods: {
            activate(option) {
                this.$emit('update:status', option);
            },
            activateCustom() {
                this.$emit('update:status', this.customStatus);
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

