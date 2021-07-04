<template>
    <jet-form-section @submitted="createTask">
        <template #title>
            Tarea tipo Documento
        </template>

        <template #description>

        </template>

        <template #form>
           <basic-details-task-form :form.sync="form" :chapters="chapters" :availableTypes="availableTypes" :courseId="courseId"></basic-details-task-form>
            <div class=" sm:col-span-4">
                <jet-label for="availableTypes" value="Tipo de tarea"/>
                <select name="type" id="type" v-model="form.type">
                    <option v-for="type in availableTypes" :value="type">{{type}}</option>
                </select>
            </div>
            <div v-if="form.type == 'code'" class="col-span-6 sm:col-span-4">
                <form
                    @submit.prevent="uploadCodeTest"
                    method="post"
                    enctype="multipart/form-data">
                    <jet-label for="file" value="Suba los archivos necesarios para realizar la prueba"/>
                    <input type="file" ref="testCode" class="mt-1 block w-full" @change="updateTestFile"/>
                    <br/>
                    <jet-button> Guardar archivo</jet-button>
                </form>
            </div>
            <div class="col-span-3 sm:col-span-4" v-if="form.type == 'quiz'">
                <div class="sm:col-span-4">
                    <jet-label for="question" value="Texto de la pregunta"/>
                    <jet-input name="question" id="question" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.question"/>
                    <jet-input-error :message="form.error('question')" class="mt-2"/>
                </div>
                <div  class="sm:col-span-4">
                    <jet-label for="answer1" value="Respuesta 1"/>
                    <jet-input name="answer1" id="answer1" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t1"/>
                    <jet-input-error :message="form.error('answer1')" class="mt-2"/>
                </div>
                <div  class="sm:col-span-4">
                    <jet-label for="answer2" value="Respuesta 2"/>
                    <jet-input name="answer2" id="answer2" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t2"/>
                    <jet-input-error :message="form.error('answer2')" class="mt-2"/>
                </div>
                <div class="sm:col-span-4">
                    <jet-label for="answer3" value="Respuesta 3"/>
                    <jet-input name="answer3" id="answer3" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t3"/>
                    <jet-input-error :message="form.error('answer3')" class="mt-2"/>
                </div>
                <div  class="sm:col-span-4">
                    <jet-label for="answer4" value="Respuesta 4"/>
                    <jet-input name="answer4" id="answer4" type="text" class="mt-1 block w-full" v-model="form.properties.quiz.responses.t4"/>
                    <jet-input-error :message="form.error('answer4')" class="mt-2"/>
                </div>
                <div  class="sm:col-span-4">
                    <jet-label for="correctAnswer" value="Respuesta Correcta"/>
                    <select name="correctAnswer" id="correctAnswer" v-model="form.properties.quiz.correctAnswer">
                        <option value="1">Respuesta 1</option>
                        <option value="2">Respuesta 2</option>
                        <option value="3">Respuesta 3</option>
                        <option value="4">Respuesta 4</option>
                    </select>
                    <jet-input-error :message="form.error('correctAnswer')" class="mt-2"/>
                </div>
            </div>
            <div class="col-span-3 sm:col-span-4" v-if="form.type == 'card'">
                <div class="col-span-4 sm:col-span-4">
                    <jet-label for="cardQuestion" value="Texto de la pregunta"/>
                    <jet-input name="cardQuestion" id="cardQuestion" type="text" class="mt-1 block w-full" v-model="form.properties.card.front"/>
                    <jet-input-error :message="form.error('cardQuestion')" class="mt-2"/>
                </div>
                <div  class="col-span-4 sm:col-span-4">
                    <jet-label for="answer" value="Respuesta"/>
                    <jet-input name="answer" id="answer" type="text" class="mt-1 block w-full" v-model="form.properties.card.back"/>
                    <jet-input-error :message="form.error('answer')" class="mt-2"/>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-4" v-if="form.type === 'document' || form.type === 'code'">
                <jet-label  value="Descripción de la tarea"/>
                <markdown-editor-section id="content" :properties.sync="form.properties.content"></markdown-editor-section>
            </div >
            <div class="col-span-6 sm:col-span-4" v-if="form.type === 'code'">
                <jet-label  value="Comandos que deben ejecutarse antes de realizar la prueba"/>
                <markdown-editor-section id="previous" :properties.sync="form.properties.scriptPrevious"></markdown-editor-section>
            </div >
            <div class="col-span-6 sm:col-span-4" v-if="form.type === 'code'">
                <jet-label  value="Código ejecutable para testar la prueba"/>
                <markdown-editor-section id="after" :properties.sync="form.properties.scriptAfter"></markdown-editor-section>
            </div >
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Guardado.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Guardar Tarea
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
                            scriptPrevious: '',
                            scriptAfter: '',
                            quiz: {
                                question: '',
                                responses: {
                                    "0": '',
                                    "1": '',
                                    "2": '',
                                    "3": ''
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
            updateTestFile() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.testCode = e.target.result;

                };

                reader.readAsDataURL(this.$refs.testCode.files[0]);
            },
            uploadCodeTest() {

                if (this.$refs.testCode) {
                    this.file.testCode = this.$refs.testCode.files[0]

                }
                this.file.post(route('uploadTest', {'course': this.courseId, 'task': this.task.id}), {
                    preserveScroll: true
                });
            },
            updateTask() {
                this.form.put(route('courses.tasks.update', {'course': this.courseId, 'task': this.task.id}), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
