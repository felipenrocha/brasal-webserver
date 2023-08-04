import { createRouter, createWebHistory } from 'vue-router'
import Login from './components/auth/Login.vue'
import Register from './components/auth/Register.vue'
import Home from './components/tarefas/Home.vue'
import Create from './components/tarefas/Create.vue'
import Edit from './components/tarefas/Edit.vue'



const routes = [
  {
    path: '/',
    component: Home
  }, {
    path: '/home',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    component: Login,
  },
  {
    path: '/register',
    component: Register,
  }, {
    path: '/create',
    component: Create,
  },
  // obs.: id representa o id no banco de dados da tarefa.
  { 
    path: '/edit/:id', component: Edit
 },


]
const router = createRouter({
  history: createWebHistory(),
  routes
})
export default router;
