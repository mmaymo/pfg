<template>
    <div class="col-span-1 sm:col-span-6">
        <div class="col-span-3 sm:col-span-2">
            <h2>Sección</h2>
            <jet-label for="chapter" value="Sección en la que añadir esta tarea"/>
            <select v-if="chapters.length > 0" id="chapter" v-model="form.chapter_id">
                <option v-for="chapter in chapters" :value="chapter.id">{{chapter.name}}</option>
            </select>
            <jet-button >
                Editar sección
            </jet-button>
        </div>

        <jet-section-border/>
        <jet-form-section @submitted="addChapter">
            <template #title>
                Crear nueva sección
            </template>

            <template #description>

            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="chapterName" value="Nombre de la sección"/>
                    <jet-input id="chapterName" type="text" class="mt-1 block w-full" v-model="formChapter.name"/>
                    <jet-input-error :message="formChapter.error('name')" class="mt-2"/>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="formChapter.recentlySuccessful" class="mr-3">
                    Guardado.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': formChapter.processing }" :disabled="formChapter.processing">
                    Guardar
                </jet-button>
            </template>
        </jet-form-section>
        <jet-section-border/>

        <div class="col-span-6 sm:col-span-4">
            <jet-label for="name" value="Titulo de la tarea"/>
            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
            <jet-input-error :message="form.error('name')" class="mt-2"/>
        </div>
        <div class="col-span-3 sm:col-span-2">
            <jet-label for="points" value="Puntos"/>
            <jet-input id="points" type="number" class="mt-1 block w-full" v-model="form.points"/>
            <jet-input-error :message="form.error('points')" class="mt-2"/>
        </div>

    </div>
</template>

<script>
    import JetInput from '../Jetstream/Input'
    import JetInputError from '../Jetstream/InputError'
    import JetLabel from '../Jetstream/Label'
    import JetFormSection from '../Jetstream/FormSection'
    import JetActionMessage from "../Jetstream/ActionMessage";
    import JetButton from "../Jetstream/Button";
    import JetSectionBorder from "../Jetstream/SectionBorder";


    export default {
        props: [
            'form', 'chapters', 'availableTypes', 'courseId'
        ],
        data() {
            return {
                formChapter: this.$inertia.form({
                    name: '',
                    type: 'chapter',

                }, {
                    bag: 'chapterForm',
                    resetOnSuccess: true,
                }),
            }
        },
        components: {
            JetInput,
            JetInputError,
            JetLabel,
            JetFormSection,
            JetActionMessage,
            JetButton,
            JetSectionBorder
        },
        methods: {
            addChapter() {
                this.formChapter.post(route('courses.tasks.store', {'course':this.courseId}), {
                    preserveScroll: true
                });
            },
        }
    }
</script>
