import { createRouter, createWebHistory } from 'vue-router'
import HomeView from "@/views/HomeView.vue";
import PlayersView from "@/views/PlayersView.vue";
import TournamenstView from "@/views/TournamentsView.vue";
import TeamsView from "@/views/TeamsView.vue";
import GamesView from "@/views/GamesView.vue";

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
    {
        path: '/teams',
        name:'teams',
        component: TeamsView
    },
    {
        path: '/tournaments',
        name:'tournaments',
        component: TournamenstView
    },
    {
        path: '/games',
        name:'games',
        component: GamesView
    },
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router
