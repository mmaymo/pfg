<template>
    <button :type="type" class="inline-flex items-center mx-2 px-4 py-2 bg-green-300 border border-transparent rounded-md font-semibold text-xs text-green-800 hover:text-white uppercase tracking-widest hover:bg-green-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
        }
    }
</script>
