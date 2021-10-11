<template>
    <div>
        <div class="h-24">
            <pie-chart ref="chart" :chart-data="chartData" :options="chartOptions" :width="100" :height="100"></pie-chart>
        </div>

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
            {{ complete }} {{ incomplete }}
            <task-item v-for="task in tasks"
                       :key="task.id"
                       :custom_statuses="custom_statuses"
                       :task="task"
                       @afterEdit="replaceTaskAndSort"
                       @afterDelete="removeTask"
                       @updateCount="updateCountHandler"
            >
            </task-item>
        </div>
    </div>
</template>

<script>
    import NewTask from './tasks/NewTask.vue';
    import TaskItem from './tasks/TaskItem.vue';
    import NewStatus from './tasks/NewStatus.vue';
    import PieChart from "./charts/PieChart.js";

    export default {
        components: {
            NewTask,
            TaskItem,
            NewStatus,
            PieChart
        },
        data() {
          return {
              submitting: false,
              submitted: false,
              tasks: [],
              complete: 0,
              incomplete: 0,
              custom_statuses: [],
              newStatusIsOpen: false,
              chartOptions: {
                  hoverBorderWidth: 20,
                  maintainAspectRatio:false,
                  responsive: true
              },
              chartData: {
                  hoverBackgroundColor: "red",
                  hoverBorderWidth: 10,
                  labels: ["Complete", "Incomplete"],
                  datasets: [
                      {
                          label: "Data One",
                          backgroundColor: ["#41B883", "#E46651"],
                          data: []
                      }
                  ]
              }
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
                    this.complete = response.data.complete;
                    this.incomplete = response.data.incomplete;
                    this.chartData.datasets[0].data = [response.data.complete, response.data.incomplete];
                    this.$refs.chart.render()
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
            replaceTaskAndSort(task, complete, incomplete) {
               this.removeTask(task);
               this.tasks.push(task);
               this.complete = complete;
               this.incomplete = incomplete;
                this.chartData.datasets[0].data = [complete, incomplete];
                this.$refs.chart.render()
               this.sortTasks();
            },
            updateCountHandler(complete, incomplete) {
                this.complete = complete;
                this.incomplete = incomplete;
                this.chartData.datasets[0].data = [complete, incomplete];
                this.$refs.chart.render()
            }
        },

        async mounted() {
            await axios.get('/tasks').then(response => {
                this.tasks = response.data.data;
                this.complete = response.data.complete;
                this.incomplete = response.data.incomplete;
                this.chartData.datasets[0].data = [response.data.complete, response.data.incomplete];
                this.$refs.chart.render()
            });
            await axios.get('/custom-statuses').then(response => this.custom_statuses = response.data.data);
        }
    }
</script>
