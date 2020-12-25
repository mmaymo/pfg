<template>
    <div>
        <div class="col-span-6 sm:col-span-4">
            <jet-label for="name" value="Nombre del curso *"/>
            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus/>
            <jet-input-error :message="form.error('name')" class="mt-2"/>
        </div>
    </div>

</template>
<script>
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'

    export default {
        name: 'courseNameFields',
        components: {JetInput, JetInputError, JetLabel},
        props: {
            form: {type: Object}
        }
    }
</script>
