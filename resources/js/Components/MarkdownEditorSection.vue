<template>
    <div class="col-span-1 sm:col-span-6">

        <editor :initialValue="editorText" ref="toastuiEditor" height="500px"></editor>
        <button @click="getMarkdown">Guardar</button>
    </div>

</template>

<script>
import 'codemirror/lib/codemirror.css';
import '@toast-ui/editor/dist/toastui-editor.css';

import { Editor } from '@toast-ui/vue-editor';
    export default {
        props:{
            properties:{default:'hola'}
        },
        data(){
            return {
                editorText:'hola'
            }
        },
        components: {
            Editor
        },
        methods: {
            getMarkdown() {
                let md = this.$refs.toastuiEditor.invoke('getMarkdown');
                this.$emit('update:properties', md)
            }
        }
    }
</script>
