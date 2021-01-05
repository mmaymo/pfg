<template>
    <div class="flex">
        <div class="px-6 xl:px-12 w-full max-w-3xl mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:w-3/4">
            <div class="mt-0  overflow-hidden relative">
                <VueShowdown :markdown="textContent.content"/>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    components: {
    },
    props: {
        textContent: {
            type: Object,
            default: {},
        },
    },
    data() {
        return {}
    },
    methods: {
        computeAnswer() {

        },

    },
}
</script>
