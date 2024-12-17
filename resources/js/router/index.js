import { createRouter, createWebHistory } from 'vue-router';
import TaskList from '../components/TaskList.vue';
import TaskForm from '../components/TaskForm.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: TaskList,
    },
    {
        path: '/tasks',
        name: 'Tasks',
        component: TaskList,
    },
    {
        path: '/tasks/create',
        name: 'CreateTask',
        component: TaskForm,
    },
    {
        path: '/tasks/:id/edit',
        name: 'EditTask',
        component: TaskForm,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
