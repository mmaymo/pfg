<template>
    <div class="col-span-1 sm:col-span-6">
        <div class="col-span-6 sm:col-span-4">
            <jet-label for="name" value="Titulo de la tarea" />
            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
            <jet-input-error :message="form.error('name')" class="mt-2" />
        </div>
        <div class="col-span-3 sm:col-span-2">
            <jet-label for="points" value="Puntos" />
            <jet-input id="points" type="number" class="mt-1 block w-full" v-model="form.points" />
            <jet-input-error :message="form.error('name')" class="mt-2" />
        </div>
    </div>
</template>

<script>
import JetInput from '../Jetstream/Input'
import JetInputError from '../Jetstream/InputError'
import JetLabel from '../Jetstream/Label'

export default {
        props:{
            form: {
                name:'',
                points: ''
            }
        },
        data(){
            return {

            }
        },
        components: {
            JetInput,
            JetInputError,
            JetLabel,
        },
        methods: {

        }
    }
</script>
