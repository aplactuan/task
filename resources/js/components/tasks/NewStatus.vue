<template>
    <div>
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true"
            v-if="isOpen"
        >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

           <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                    <form @submit.prevent="submitNewStatus">
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
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" v-if="statusNameValidity === 'invalid'">
                                <!-- Heroicon name: solid/exclamation-circle -->
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p
                                class="mt-2 text-sm text-red-600"
                                v-if="statusNameValidity === 'invalid'"
                            >
                                Status name must not be empty
                            </p>
                        </div>
                        <div class="mt-5 sm:mt-6 flex">
                            <button type="submit"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                Save
                            </button>
                            <button type="button"
                                    @click.prevent="closeStatusModal"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: '',
            },
            statusNameValidity: 'pending'
        }
    },
    props: {
        isOpen: {
            type: Boolean,
            default: false
        }
    },
    emits: ['closeModal', 'addCustomStatus'],
    methods: {
        closeStatusModal() {
            this.$emit('closeModal');
        },
        validateInput() {
            if (this.form.name === '') {
                this.statusNameValidity = 'invalid';
            } else {
                this.statusNameValidity = 'valid';
            }
        },
        submitNewStatus() {
            if (this.form.name.trim() === '') {
                this.userNameValidity = 'invalid';
                return;
            }
            this.$emit('addCustomStatus', this.form);
        }
    }
}
</script>
