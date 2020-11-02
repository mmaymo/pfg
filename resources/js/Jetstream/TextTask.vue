<template>
    <div id="task" class="flex">
        <div class="pb-4 w-full pt-8 lg:pt-8">
            <div class="max-w-3xl mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:px-12 xl:w-3/4 ">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold capitalize">{{this.chapter}}</h1>
                </div>
                <h2 class="group flex whitespace-pre-wrap relative capitalize">{{this.taskName}}</h2>
            </div>
            <div class="flex">
                <div class="px-6 xl:px-12 w-full max-w-3xl mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:w-3/4">
                    <div class="mt-0  overflow-hidden relative">

                        <VueShowdown :markdown="this.textContent"/>
                    </div>

                </div>
            </div>
            <div id="bottomTaskButtons" class="flex border-b border-gray-300 p-8 pt-16">
                <div class="w-2/4">
                    <button class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">Atrás</button>
                    <p>Task name anterior</p>
                </div>
                <div class="w-2/4 text-right">
                    <button class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">Siguiente</button>
                    <p>Task name siguiente</p>
                </div>

            </div>
            <div id="footer" class="pt-8">
                <div class="text-right w-full pr-8"><p>Issue</p></div>
                <div class="text-right w-full pr-8"><p>Dudas</p></div>
            </div>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from './../Jetstream/ApplicationLogo'

    export default {
        components: {
            JetApplicationLogo,
        },
        data() {
            return {
                chapter: "Nombre del Capítulo",
                taskName: "Nombre tarea",
                textContent:"Dillinger is a cloud-enabled, mobile-ready, offline-storage, AngularJS powered HTML5 Markdown editor\n" +
                    "  - Type some Markdown on the left\n" +
                    "  - See HTML in the right\n" +
                    "  - Magic\n" +
                    "\n" +
                    "# New Features!\n" +
                    "\n" +
                    "  - Import a HTML file and watch it magically convert to Markdown\n" +
                    "  - Drag and drop images (requires your Dropbox account be linked)\n" +
                    "> The overriding design goal for Markdown's\n" +
                    "> formatting syntax is to make it as readable\n" +
                    "\n![esto es un gif](https://media.giphy.com/media/xT5LMzIK1AdZJ4cYW4/giphy.gif)"
            }
        },
        methods: {
            computeAnswer(){

            },

        },
    }
</script>
