<template>
    <jet-form-section @submitted="createTask">
        <template #title>
            Tarea tipo Documento
        </template>

        <template #description>

        </template>

        <template #form>
           <basic-details-task-form :form.sync="form"></basic-details-task-form>
            <markdown-editor-section :properties.sync="form.properties[0].content">

            </markdown-editor-section>
            <jet-button  >
                Añadir sección
            </jet-button>
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

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            MarkdownEditorSection,
            BasicDetailsTaskForm
        },
        props:[
            'courseId'
        ],
        data() {
            return {
                sections: 1,
                form: this.$inertia.form({
                    name:'',
                    type:'Document',
                    position:'200',
                    points:'',
                    time:'',
                    properties: [
                        {
                            sectionName:"",
                            type: "markdown",
                            content:""
                        }
                    ]
                }, {
                    bag: 'createTask',
                    resetOnSuccess: false,
                })
            }
        },

        methods: {
            createTask() {
                console.log("APRETADO")
                let algo = this.form.post(route('courses.tasks.store', {'course':this.courseId}), {
                    preserveScroll: true
                });
                console.log(algo)
            },
        },
    }
</script>
