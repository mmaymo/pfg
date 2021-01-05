<template>
    <div class="">
        <jet-section-title>
            <template #title><slot name="title"></slot></template>
            <template #description><slot name="description"></slot></template>
        </jet-section-title>

        <div class="mt-5 md:mt-0 md:col-span-2 shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:p-6 bg-white ">
                <slot name="content"></slot>
            </div>
            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6" v-if="hasActions">
                <slot name="actions"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import JetSectionTitle from './SectionTitle'

    export default {
        components: {
            JetSectionTitle,
        },
        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
