<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="mt-8 text-2xl">
                {{this.chapter}}
            </div>
            <!--container-->
            <section class="container" >


                <VueShowdown :markdown="this.textContent"/>
            </section>
            <!--/container-->
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
                chapter: "nombre del capítulo",
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
