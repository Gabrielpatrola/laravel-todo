<template>
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-white">
            <h1 class="h5 mb-0">{{ isEdit ? 'Edit Task' : 'Add New Task' }}</h1>
            <router-link to="/tasks" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Tasks
            </router-link>
        </div>
        <div class="card-body">
            <form @submit.prevent="handleSubmit" novalidate>
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title</label>
                    <input
                        type="text"
                        id="title"
                        v-model="task.title"
                        :class="['form-control', errors.title ? 'is-invalid' : '']"
                        required
                        maxlength="255"
                    >
                    <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select
                        id="status"
                        v-model="task.status"
                        :class="['form-select', errors.status ? 'is-invalid' : '']"
                        required
                    >
                        <option value="">Select Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                    <div v-if="errors.status" class="invalid-feedback">{{ errors.status }}</div>
                </div>
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-save me-1"></i>
                        {{ isEdit ? 'Update Task' : 'Add Task' }}
                    </button>
                    <router-link to="/tasks" class="btn btn-light btn-sm">
                        <i class="fas fa-times me-1"></i>Cancel
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default {
    setup() {
        const task = ref({
            title: '',
            status: '',
        })
        const errors = ref({})
        const route = useRoute()
        const router = useRouter()

        const isEdit = !!route.params.id
        const taskId = route.params.id

        const fetchTask = () => {
            axios.get(`/api/tasks/${taskId}`)
                .then(response => {
                    task.value = response.data
                })
                .catch(error => {
                    console.error(error)
                    router.push({ name: 'Tasks' })
                })
        }

        const handleSubmit = () => {
            const apiCall = isEdit
                ? axios.put(`/api/tasks/${taskId}`, task.value)
                : axios.post('/api/tasks', task.value)

            apiCall
                .then(() => {
                    const successMessage = isEdit
                        ? 'Task updated successfully.'
                        : 'Task created successfully.'
                    router.push({ name: 'Tasks', query: { success: successMessage } })
                })
                .catch(error => {
                    if (error.response && error.response.data.errors) {
                        errors.value = error.response.data.errors
                    } else {
                        console.error(error)
                    }
                })
        }

        onMounted(() => {
            if (isEdit) {
                fetchTask()
            }
        })

        return {
            task,
            errors,
            isEdit,
            handleSubmit
        }
    }
}

</script>

<style scoped>
.card {
    border-radius: 0.5rem;
    margin-bottom: 2rem;
}
.card-header {
    border-bottom: 1px solid #e9ecef;
}
.invalid-feedback {
    display: block;
}
</style>
