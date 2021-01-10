<template>
    <div class="col-span-1 sm:col-span-6">
        <editor :initialValue="properties" ref="toastuiEditor" height="500px" @change="onEditorChange"></editor>
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
            onEditorChange() {
                let md = this.$refs.toastuiEditor.invoke('getMarkdown');
                this.$emit('update:properties', md)
            }
        }
    }
</script>
