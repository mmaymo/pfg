<template>
    <div id="sidebar"
         class="flex flex-col w-full md:w-64 text-gray-700 bg-white dark-mode:text-gray-200 dark-mode:bg-gray-800 flex-shrink-0">
        <div class="flex-shrink-0 px-8 py-4 flex flex-row items-center justify-between">
            <a href="#" class="sidebarHeader text-gray-900">{{ this.courseName }}</a>
        </div>
        <div id="navWrapper"
             class="h-full overflow-y-auto scrolling-touch lg:h-auto lg:block lg:relative lg:sticky lg:bg-transparent overflow-hidden lg:top-16 bg-white">
            <div class="sidebarHeader text-green-400">{{ this.coursePoints }} puntos</div>
            <div class="sidebarHeader text-green-400">{{ this.courseProgress.toFixed(1) }}%</div>
            <div class="sidebarHeader text-green-400"><a
                :href="route('flashCardsShuffle', {'course':courseId})">Tarjetas de estudio</a>
            </div>
            <nav id="nav"
                 class="flex-grow md:block px-4 pb-4 md:pb-0 md:overflow-y-auto">
                <ul v-for="task in this.itinerary" class="">
                    <li v-if="disableLink(task.id)"
                        class="sidebarToggleItem  text-green-900 hover:text-gray-900 hover:bg-green-300">
                        <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.id})"
                           class="">
                            <base-svg :icon-name=task.type :width=20 :height=20
                                      :d=iconType(task.type)></base-svg>
                            <span class="ml-3">{{ task.name }}</span>
                        </a>
                        <p><a @click="toggleMenu(task.id)" class="">
                            <svg width=30 height=30 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M15.707 4.293a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 011.414-1.414L10 8.586l4.293-4.293a1 1 0 011.414 0zm0 6a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 111.414-1.414L10 14.586l4.293-4.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </a></p>
                    </li>
                    <li v-else
                        class="sidebarToggleItem hover:text-gray-900 hover:bg-gray-300 text-gray-900">
                        <a href="javascript:">
                            <base-svg :icon-name=task.type :width=20 :height=20
                                      :d=iconType(task.type)></base-svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-3">{{ task.name }}</span>
                        </a>
                        <p><a @click="toggleMenu(task.id)" class="">
                            <svg width=30 height=30 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M15.707 4.293a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 011.414-1.414L10 8.586l4.293-4.293a1 1 0 011.414 0zm0 6a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 111.414-1.414L10 14.586l4.293-4.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </a></p>
                    </li>
                    <li v-for="subtask in task.tasks" v-show="menuOpen === task.id"
                        class="text-right">
                        <a v-if="disableLink(subtask.id)"
                           :href="route('courses.tasks.show', {'course':courseId, 'task':subtask.id})"
                           class="sidebarToggleItem hover:text-gray-900 hover:bg-green-300 text-green-900">
                            <span class="font-bold inline">{{ subtask.name }}</span>
                            <base-svg class="inline" :icon-name=subtask.type :width=20 :height=20
                                      :d=iconType(subtask.type)></base-svg>
                        </a>
                        <a v-else href="javascript:"
                           class="sidebarToggleItem hover:text-gray-900 hover:bg-gray-300 text-gray-900">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20"
                                 height="20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-bold inline">{{ subtask.name }}</span>
                            <base-svg class="inline" :icon-name=subtask.type :width=20 :height=20
                                      :d=iconType(subtask.type)></base-svg>

                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>

import BaseSvg from "./BaseSvg";

export default {
        components: {
            BaseSvg
        },
        props: {
            courseId: {
                type: Number,
                default: '',
            },
            courseName: {
                type: String,
                default: '',
            },
            teacher: {
                type: String,
                default: '',
            },
            itinerary: {
                type: Array,
                default: [],
            },
            allowedIds: {
                type: Array,
                default: ()=>[],
            },
            coursePoints: {
                type: Number,
                default: '',
            },
            courseProgress: {
                type: Number,
                default: '',
            },
        },
        methods: {
            disableLink(taskId){
                return this.allowedIds.includes(taskId);
            },

            iconType(type) {
                switch (type) {
                    case "document":
                    case "chapter":
                        return "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z";
                    case "code":
                        return "M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z";
                    case "quiz":
                    case "multipleQuiz":
                        return "M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z";
                    case "card":
                        return "M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z";
                    default: return;
                }

            },
            toggleMenu: function(id) {
                if(id === this.menuOpen){
                    this.menuOpen = false

                }else{
                    this.menuOpen = id;
                }

            }

        },
        data() {
            return {
                menuOpen: false,
                open: true

            }
        },
    }
</script>
