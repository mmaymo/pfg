<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <div id="mainContentWrap"
                     class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
                    <div id="mainContent">
                        <div v-for="team in $page.user.all_teams">
                            <jet-responsive-nav-link :href="route('courses.show', team.id)" :active="$page.currentRouteName == 'courses.show'">
                                {{ team.name }}
                            </jet-responsive-nav-link>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'
import JetResponsiveNavLink from "../Jetstream/ResponsiveNavLink";

export default {
    components: {
        AppLayout,
        JetResponsiveNavLink,
    },
    data() {
        return {}
    },

}
</script>
