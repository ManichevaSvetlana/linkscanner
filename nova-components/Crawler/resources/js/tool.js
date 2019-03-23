
import Notifications from 'vue-notification'

Nova.booting((Vue, router, store) => {
    Vue.use(Notifications);
    router.addRoutes([
        {
            name: 'crawler',
            path: '/crawler',
            component: require('./components/Tool'),
        },
    ])
})
