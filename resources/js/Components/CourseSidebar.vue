<template>
    <div id="sidebar"
         class="flex flex-col w-full md:w-64 text-gray-700 bg-white dark-mode:text-gray-200 dark-mode:bg-gray-800 flex-shrink-0">
        <div class="flex-shrink-0 px-8 py-4 flex flex-row items-center justify-between">
            <a href="#" class="w-full text-lg text-right font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline">{{ this.courseName }}</a>

            <button class="rounded-lg md:hidden rounded-lg focus:outline-none focus:shadow-outline" @click="open = !open">
                <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                    <path v-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    <path v-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <nav :class="{'block': open, 'hidden': !open}" class="flex-grow md:block px-4 pb-4 md:pb-0 md:overflow-y-auto">
            <div class="w-full text-sm text-right font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline">{{ this.teacher }}</div>
            <div class="w-full text-sm text-right font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline">{{ this.coursePoints }}</div>
            <div class="w-full text-sm text-right font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline">{{ this.courseProgress }}</div>
            <ul v-for="task in this.itinerary" class="" >
                <li class="">
                    <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.id})"
                       class="block px-4 py-2 mt-2 text-sm text-right text-gray-900 bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">

                        <span class="font-bold inline">{{ task.name }}</span><base-svg class="inline" :icon-name=task.type :width=20 :height=20
                                                                                       :d=iconType(task.type)></base-svg></a>
                    <ul>
                        <li  v-for="subtask in task.tasks" class="text-right" >
                            <a :href="route('courses.tasks.show', {'course':courseId, 'task':subtask.id})"   class="items-center block px-4 py-2 mt-2 text-sm text-right text-green-900 bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-green-300 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-green-300 focus:bg-green-300 focus:outline-none focus:shadow-outline">
                               <span class="inline">{{ subtask.name }}</span>
                                <base-svg class="inline" :icon-name=subtask.type :width=20 :height=20
                                          :d=iconType(subtask.type)></base-svg>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
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
                return true;
                //return this.allowedIds.includes(taskId);
            },

            iconType(type) {
                switch (type) {
                    case "document":
                    case "chapter":
                        return "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z";
                    case "code":
                        return "M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z";
                    case "quiz":
                        return "M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z";
                    case "card":
                        return "M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z";
                    default: return;
                }

            },
            toggleMenu: function(id) {
                this.menuOpen = id;
            }

        },
        data() {
            return {
                menuOpen: false,
                open: false

            }
        },
    }
</script>
