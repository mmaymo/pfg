<template>
    <div>
        <div class="col-span-6">
            <jet-label value="Responsable del curso"/>

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" :src="$page.user.profile_photo_url" :alt="$page.user.name">

                <div class="ml-4 leading-tight">
                    <div>{{ $page.user.name }}</div>
                    <div class="text-gray-700 text-sm">{{ $page.user.email }}</div>
                </div>
            </div>
        </div>
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
