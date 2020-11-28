<template>
    <jet-form-section @submitted="updateTask">
        <template #title>
            Editar tarea
        </template>

        <template #description>
            Nombre del curso y su responsable.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Nombre" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="points" value="Puntos" />
                <jet-input id="points" type="number" class="mt-1 block w-full" v-model="form.points" />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="type" value="Tipo" />
                <select id="type"  class="mt-1 block w-full rounded-md shadow-sm" v-model="form.type">
                    <option value="document">Texto</option>
                    <option value="card">Tarjeta</option>
                    <option value="quiz">Test</option>
                    <option value="code">Codigo</option>
                </select>
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="properties" value="Contenido" />
                <jet-input id="properties" type="text" class="mt-1 block w-full" v-model="form.properties" />
                <jet-input-error :message="form.error('properties')" class="mt-2" />
            </div>
        </template>

        <template #actions v-if="permissions.canUpdateTeam">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Guardar
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetButton from './../../Jetstream/Button'
    import JetFormSection from './../../Jetstream/FormSection'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        props: ['task', 'permissions', 'course'],

        data() {
            return {
                form: this.$inertia.form({
                    name:this.task.name,
                    type:this.task.type,
                    position:this.task.position,
                    points:this.task.points,
                    properties: this.task.properties
                }, {
                    bag: 'updateTask',
                    resetOnSuccess: false,
                })
            }
        },

        methods: {
            updateTask() {
                this.form.put(route('courses.tasks.update', {'course': this.course, 'task': this.task.id}), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
