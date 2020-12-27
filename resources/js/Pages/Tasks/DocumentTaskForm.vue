<template>
    <jet-form-section @submitted="createTask">
        <template #title>
            Tarea tipo Documento
        </template>

        <template #description>

        </template>

        <template #form>
           <basic-details-task-form :form.sync="form" :chapters="chapters" :availableTypes="availableTypes" :courseId="courseId"></basic-details-task-form>
            <div class="col-span-3 sm:col-span-2">
                <jet-label for="availableTypes" value="Tipo de tarea"/>
                <select name="type" id="type" v-model="form.type">
                    <option v-for="type in availableTypes" :value="type">{{type}}</option>
                </select>
            </div>
            <div  v-if="form.type == 'code'"  class="col-span-6 sm:col-span-4">
                <jet-label for="code_url" value="Nombre del archivo"/>
                <jet-input id="code_url" type="text" class="mt-1 block w-full" v-model="form.properties.code_url"/>
                <jet-input-error :message="form.error('code_url')" class="mt-2"/>
            </div>
            <div v-if="form.type == 'quiz'">
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="question" value="Texto de la pregunta"/>
                    <jet-input id="question" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.question"/>
                    <jet-input-error :message="form.error('question')" class="mt-2"/>
                </div>
                <div  class="col-span-6 sm:col-span-4">
                    <jet-label for="answer1" value="Respuesta 1"/>
                    <jet-input id="answer1" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t1"/>
                    <jet-input-error :message="form.error('answer1')" class="mt-2"/>
                </div>
                <div  class="col-span-6 sm:col-span-4">
                    <jet-label for="answer2" value="Respuesta 2"/>
                    <jet-input id="answer2" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t2"/>
                    <jet-input-error :message="form.error('answer2')" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="answer3" value="Respuesta 3"/>
                    <jet-input id="answer3" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t3"/>
                    <jet-input-error :message="form.error('answer3')" class="mt-2"/>
                </div>
                <div  class="col-span-6 sm:col-span-4">
                    <jet-label for="answer4" value="Respuesta 4"/>
                    <jet-input id="answer4" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t4"/>
                    <jet-input-error :message="form.error('answer4')" class="mt-2"/>
                </div>
                <div  class="col-span-6 sm:col-span-4">
                    <jet-label for="correctAnswer" value="Respuesta Correcta"/>
                    <select name="type" id="correctAnswer" v-model="form.properties.quiz.correctAnswer">
                        <option value="1">Respuesta 1</option>
                        <option value="2">Respuesta 2</option>
                        <option value="3">Respuesta 3</option>
                        <option value="4">Respuesta 4</option>
                    </select>
                    <jet-input-error :message="form.error('correctAnswer')" class="mt-2"/>
                </div>
            </div>
            <div v-if="form.type == 'card'">
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="cardQuestion" value="Texto de la pregunta"/>
                    <jet-input id="cardQuestion" type="text" class="mt-1 block w-full" v-model="form.properties.card.front"/>
                    <jet-input-error :message="form.error('cardQuestion')" class="mt-2"/>
                </div>
                <div  class="col-span-6 sm:col-span-4">
                    <jet-label for="answer" value="Respuesta"/>
                    <jet-input id="answer" type="text" class="mt-1 block w-full" v-model="form.properties.card.back"/>
                    <jet-input-error :message="form.error('answer')" class="mt-2"/>
                </div>
            </div>

            <markdown-editor-section v-if="form.type == 'document' || form.type == 'code'" :properties.sync="form.properties.content"></markdown-editor-section>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Guardado.
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

    import MarkdownEditorSection from "../../Components/MarkdownEditorSection";
    import BasicDetailsTaskForm from "../../Components/BasicDetailsTaskForm";
    import JetInput from "../../Jetstream/Input";
    import JetInputError from "../../Jetstream/InputError";
    import JetLabel from "../../Jetstream/Label";

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            MarkdownEditorSection,
            BasicDetailsTaskForm,
            JetInput,
            JetInputError,
            JetLabel,
        },
        props:[
            'courseId','chapters', 'availableTypes'
        ],
        data() {
            return {
                form: this.$inertia.form({
                    name:'',
                    type:'',
                    chapter_id:'',
                    points:'',
                    properties:
                        {
                            content: '',
                            code_url: '',
                            quiz: {
                                question: '',
                                responses: {
                                    t1: '',
                                    t2: '',
                                    t3: '',
                                    t4: ''
                                },
                                correctAnswer: 0
                            },
                            card: {
                                front: '',
                                back: ''
                            }
                        }
                }, {
                    bag: 'createTask',
                    resetOnSuccess: false,
                })
            }
        },

        methods: {
            createTask() {
                this.form.post(route('courses.tasks.store', {'course':this.courseId}), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
