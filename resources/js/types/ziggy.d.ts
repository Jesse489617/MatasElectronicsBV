import type { RouterParams, Router} from 'ziggy-js'

declare global {
    function route(): Router
    function route(name: string, params?: RouterParams<typeof name> | undefined, absolute?: boolean) : string
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof route
    }
}
