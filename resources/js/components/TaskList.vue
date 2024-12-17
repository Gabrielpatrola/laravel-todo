<template>
    <div class="row g-4">
        <!-- Pending Tasks Column -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between bg-white">
                    <h1 class="h5 mb-0">Pending Tasks</h1>
                    <router-link to="/tasks/create" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Add New Task
                    </router-link>
                </div>
                <div class="card-body">
                    <draggable
                        v-model="pendingTasksArr"
                        item-key="id"
                        class="list-group pending-list-group"
                        :group="{ name: 'tasks', pull: true, put: true }"
                        @end="onTaskListEnd"
                    >
                        <template #item="{ element }">
                            <div
                                class="list-group-item d-flex align-items-center justify-content-between py-2"
                                :data-id="element.id"
                            >
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical me-2 text-muted" style="cursor: grab;"></i>
                                    <span>{{ element.title }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <router-link :to="`/tasks/${element.id}/edit`" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                    <button @click="confirmDelete(element)" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </draggable>
                    <div v-if="pendingTasksArr.length === 0" class="text-center text-secondary p-4">
                        <i class="fas fa-info-circle me-2"></i>No pending tasks found.
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Tasks Column -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="h5 mb-0">Completed Tasks</h1>
                </div>
                <div class="card-body">
                    <draggable
                        v-model="completedTasksArr"
                        item-key="id"
                        class="list-group completed-list-group"
                        :group="{ name: 'tasks', pull: true, put: true }"
                        @end="onTaskListEnd"
                    >
                        <template #item="{ element }">
                            <div
                                class="list-group-item d-flex align-items-center justify-content-between py-2"
                                :data-id="element.id"
                            >
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical me-2 text-muted" style="cursor: grab;"></i>
                                    <span class="text-decoration-line-through">{{ element.title }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <router-link :to="`/tasks/${element.id}/edit`" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                    <button @click="confirmDelete(element)" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </draggable>
                    <div v-if="completedTasksArr.length === 0" class="text-center text-secondary p-4">
                        <i class="fas fa-info-circle me-2"></i>No completed tasks found.
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" ref="deleteModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the task "<strong>{{ taskToDelete?.title }}</strong>"?
                        This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                        <button type="button" class="btn btn-danger" @click="deleteTaskConfirmed">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
            <div class="toast shadow-sm border-0" ref="toastElement" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Success</strong>
                    <small>Just now</small>
                    <button type="button" class="btn-close btn-close-white" @click="hideToast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-light text-dark rounded-bottom">
                    {{ toastMessage }}
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios'
import draggable from 'vuedraggable'
import { ref, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default {
    components: { draggable },
    setup() {
        const tasks = ref([])
        const pendingTasksArr = ref([])
        const completedTasksArr = ref([])
        const taskToDelete = ref(null)

        const deleteModal = ref(null)
        const toastElement = ref(null)
        const toastMessage = ref('')

        const route = useRoute()
        const router = useRouter()

        const fetchTasks = () => {
            return axios.get('/api/tasks')
                .then(response => {
                    tasks.value = response.data
                    syncLocalArrays()
                })
                .catch(() => showToast('Failed to fetch tasks.', false))
        }

        const syncLocalArrays = () => {
            pendingTasksArr.value = tasks.value.filter(t => t.status === 'pending')
            completedTasksArr.value = tasks.value.filter(t => t.status === 'completed')
        }

        const confirmDelete = (task) => {
            taskToDelete.value = task
            showModal()
        }

        const showModal = async () => {
            await nextTick()
            const modal = new bootstrap.Modal(deleteModal.value)
            modal.show()
        }

        const closeModal = () => {
            const modalInstance = bootstrap.Modal.getInstance(deleteModal.value)
            modalInstance.hide()
            taskToDelete.value = null
        }

        const deleteTaskConfirmed = () => {
            if (!taskToDelete.value) return
            axios.delete(`/api/tasks/${taskToDelete.value.id}`)
                .then(() => {
                    closeModal()
                    fetchTasks()
                    showToast('Task deleted successfully.')
                })
                .catch(() => showToast('Failed to delete task.', false))
        }

        const showToast = async (message) => {
            toastMessage.value = message
            await nextTick()

            const toast = new bootstrap.Toast(toastElement.value)
            toast.show()
        }

        const hideToast = () => {
            const toastInstance = bootstrap.Toast.getInstance(toastElement.value)
            if (toastInstance) {
                toastInstance.hide()
            }
        }

        const dismissAlert = () => {
            router.replace({ query: {} })
        }

        const createTask = (taskData) => {
            axios.post('/api/tasks', taskData)
                .then(() => {
                    fetchTasks()
                    showToast('Task created successfully.')
                    router.push({ name: 'Tasks', query: { success: 'Task created successfully.' } })
                })
                .catch(() => showToast('Failed to create task.', false))
        }

        const updateTask = (taskId, taskData) => {
            axios.put(`/api/tasks/${taskId}`, taskData)
                .then(() => {
                    fetchTasks()
                    showToast('Task updated successfully.')
                    router.push({ name: 'Tasks', query: { success: 'Task updated successfully.' } })
                })
                .catch(() => showToast('Failed to update task.', false))
        }

        const onTaskListEnd = (event) => {
            const isInCompletedList = event.to.classList.contains('completed-list-group')
            const movedTaskId = parseInt(event.item.dataset.id, 10)
            const newStatus = isInCompletedList ? 'completed' : 'pending'

            axios.patch(`/api/tasks/${movedTaskId}/toggle-status`)
                .then(() => {
                    fetchTasks()
                    showToast('Task status updated successfully.')
                })
                .catch(() => showToast('Failed to update task status.', false))
        }

        onMounted(() => {
            fetchTasks()

            if (route.query.success) {
                showToast(route.query.success)
                dismissAlert()
            }
        })

        return {
            tasks,
            pendingTasksArr,
            completedTasksArr,
            taskToDelete,
            deleteModal,
            toastElement,
            toastMessage,
            confirmDelete,
            closeModal,
            deleteTaskConfirmed,
            showToast,
            hideToast,
            onTaskListEnd,
            createTask,
            updateTask,
            dismissAlert
        }
    }
}
</script>

<style scoped>
.list-group {
    padding: 0;
    margin: 0;
}
.list-group-item {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
}
.toast {
    max-width: 350px;
    font-family: 'Arial', sans-serif;
}

.toast-header {
    font-weight: 600;
    border-bottom: none;
}

.toast-body {
    padding: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
}
</style>
