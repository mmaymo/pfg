<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="mt-8 text-2xl">
                {{this.chapter}}
            </div>
            <!--container-->
            <section class="container" >


                <VueShowdown markdown="# Hello, world!"/>
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
                textContent:
                    ["<p>lorem ipsum dolor sit amet</p>" +
                    "<cite>esto es una cita</cite>" +
                    "<img src='https://www.google.com/url?sa=i&url=https%3A%2F%2Fslate.com%2Fculture%2F2019%2F05%2Fgifs-on-tv-shows-the-simpsons-homer-backing-into-bushes.html&psig=AOvVaw0D9jkXNBQfutQHF8pMTS0U&ust=1603653543343000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCPimmJD5zewCFQAAAAAdAAAAABAJ' alt='imagen'>"]
                ,
            }
        },
        methods: {
            computeAnswer(){

            },

        },
    }
</script>
