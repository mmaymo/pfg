<template>
    <VueShowdown class="p-4 font-justify" :markdown="textContent.content"/>
</template>

<script>

export default {
    props: {
        textContent: {
            type: Object,
            default: {},
        },
    }
}
</script>
