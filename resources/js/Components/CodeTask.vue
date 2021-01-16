<template>
    <div>
        <div v-if="textContent.content" class="p-4 text-justify">
            <VueShowdown :markdown="textContent.content"/>
        </div>
        <div class="p-4" id="my-element-1"
             data-katacoda-env="ubuntu"
             data-katacoda-layout="editor-terminal"
             data-katacoda-port="3000"
             data-katacoda-filename="code.c" style="height: 600px; padding-top: 20px;" >
        </div>
    </div>
</template>

<script>


    export default {
        components: {
        },
        props: ['textContent'],
        data() {
            return {
            }
        },
        methods: {
        },
    }
</script>
