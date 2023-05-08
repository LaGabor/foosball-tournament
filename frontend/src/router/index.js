import { createRouter, createWebHistory } from 'vue-router'
import HomeView from "@/views/HomeView.vue";
import PlayersView from "@/views/PlayersView.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView
    },
    {
      path: '/players',
      name:'players',
      component: PlayersView
    },
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router
