<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <div id="mainContentWrap"
                     class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
                    <div id="mainContent" class="grid grid-cols-3 gap-4">
                        <div v-for="team in $page.user.all_teams" class="pt-4">
                            <div class="border-4 m-4 p-4">
                                <nav-link :href="route('courses.index')" :active="$page.currentRouteName == 'courses'">
                                    {{ team.name }}
                                </nav-link>
                                <p></p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'
import NavLink from "../Jetstream/NavLink";

export default {
    components: {
        AppLayout,
        NavLink,
    },

    data() {

        return {}
    },

}
</script>
