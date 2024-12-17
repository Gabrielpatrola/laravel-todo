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

    <!-- Optional Toast or Alert for success message -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
      <div class="toast" ref="deleteToast">
        <div class="toast-header">
          <strong class="me-auto">Success</strong>
          <button type="button" class="btn-close" @click="hideToast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          Task deleted successfully.
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios'
import draggable from 'vuedraggable'
import { ref, onMounted, nextTick } from 'vue'

export default {
  components: { draggable },
  setup() {
    const tasks = ref([])
    const pendingTasksArr = ref([])
    const completedTasksArr = ref([])
    const taskToDelete = ref(null)

    // Refs to modal and toast elements
    const deleteModal = ref(null)
    const deleteToast = ref(null)

    const fetchTasks = () => {
      return axios.get('/api/tasks')
        .then(response => {
          tasks.value = response.data
          syncLocalArrays()
        })
        .catch(error => {
          console.error(error)
          alert('Failed to fetch tasks.')
        })
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
      const modalElement = deleteModal.value
      const modal = new bootstrap.Modal(modalElement)
      modal.show()
    }

    const closeModal = async () => {
      const modalElement = deleteModal.value
      const modalInstance = bootstrap.Modal.getInstance(modalElement)
      modalInstance.hide()
      taskToDelete.value = null
    }

    const deleteTaskConfirmed = () => {
      if (!taskToDelete.value) return
      axios.delete(`/api/tasks/${taskToDelete.value.id}`)
        .then(() => {
          closeModal()
          fetchTasks()
          showToast()
        })
        .catch(error => {
          console.error(error)
          alert('Failed to delete task.')
        })
    }

    const showToast = async () => {
      await nextTick()
      const toastElement = deleteToast.value
      const toast = new bootstrap.Toast(toastElement)
      toast.show()
    }

    const hideToast = async () => {
      const toastElement = deleteToast.value
      const toastInstance = bootstrap.Toast.getInstance(toastElement)
      toastInstance.hide()
    }

    const onTaskListEnd = (event) => {
      console.log("Drag end event:", event)

      if (!event.to || !event.to.classList) {
        console.warn('event.to or event.to.classList is undefined, cannot determine target list.')
        return
      }

      const isInCompletedList = event.to.classList.contains('completed-list-group')
      const movedTaskId = event.item && event.item.dataset && event.item.dataset.id
        ? parseInt(event.item.dataset.id, 10)
        : null

      if (!movedTaskId) {
        console.warn('Could not determine moved task ID.')
        return
      }

      const newStatus = isInCompletedList ? 'completed' : 'pending'
      updateTaskStatus(movedTaskId, newStatus)
    }

    const updateTaskStatus = (taskId, newStatus) => {
      const idx = tasks.value.findIndex(t => t.id === taskId)
      if (idx === -1) {
        console.warn('Moved task not found in main tasks array.')
        return
      }

      tasks.value[idx].status = newStatus

      axios.patch(`/api/tasks/${taskId}/toggle-status`)
        .then(() => {
          console.log('Task status toggled successfully on drag.')
          fetchTasks()
        })
        .catch(error => {
          console.error('Failed to toggle task status after dragging.', error)
          alert('Failed to toggle task status after dragging.')
        })
    }

    onMounted(() => {
      fetchTasks()
    })

    return {
      tasks,
      pendingTasksArr,
      completedTasksArr,
      taskToDelete,
      deleteModal,
      deleteToast,
      confirmDelete,
      closeModal,
      deleteTaskConfirmed,
      onTaskListEnd,
      hideToast
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
</style>
