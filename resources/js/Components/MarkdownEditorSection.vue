<template>
    <div class="col-span-1 sm:col-span-6">

        <editor :initialValue="properties" ref="toastuiEditor" height="500px"></editor>
        <jet-button @click="getMarkdown">Guardar</jet-button>
    </div>

</template>

<script>
import 'codemirror/lib/codemirror.css';
import '@toast-ui/editor/dist/toastui-editor.css';


import { Editor } from '@toast-ui/vue-editor';
import JetButton from "../Jetstream/Button";

    export default {
        props:['properties'],
        data(){
            return {
            }
        },
        components: {
            Editor,
            JetButton,
        },
        methods: {
            getMarkdown() {
                let md = this.$refs.toastuiEditor.invoke('getMarkdown');
                this.$emit('update:properties', md)
            }
        }
    }
</script>
