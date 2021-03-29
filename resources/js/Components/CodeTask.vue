<template>
    <div>
        <div v-if="textContent.content" class="p-4 text-justify">
            <VueShowdown :markdown="textContent.content"/>
        </div>
        <div class="flex justify-center">
            <div id="terminal" class="xterm p-8"/>

        </div>
        <div class="flex justify-center">
            <jet-button class="mb-8" @click.native="testCode">Muestra respuesta</jet-button>

        </div>
        <div class="flex justify-center">{{ testResult }}</div>

    </div>
</template>

<script>
import JetButton from "../Jetstream/Button";
import 'xterm/css/xterm.css'
import {Terminal} from "xterm";

export default {
    components: {
        JetButton,
    },
    props: ['textContent', 'courseId', 'taskId', 'buffer', 'title', 'textContent'],
    data() {
        return {
            testResult: "",
            studentCommand:"",
            options: {
                cursorBlink: true,
                theme: {
                    background: "#202B33",
                    foreground: "#F5F8FA",
                }
            }
        }
    },

    mounted(){

        let term = new Terminal(this.options)
        term.open(document.getElementById('terminal'))
        term.writeln('Puede escribir su código a continuación');
        term.prompt = () => {
            term.write('\r\n$ ')
        }
        term.prompt()
        let currentObj = this;
        term.onKey((e) => {
            const ev = e.domEvent;
            const printable = !ev.altKey && !ev.ctrlKey && !ev.metaKey;

            if (ev.keyCode === 13) {
                console.log('intro')
                term.prompt();
                currentObj.studentCommand = currentObj.studentCommand.concat(e.key)
            } else if (ev.keyCode === 8) {
                // Do not delete the prompt
                if (term._core.buffer.x > 2) {
                    term.write('\b \b');
                    currentObj.studentCommand = currentObj.studentCommand.slice(0, -1)
                }
            } else if (printable) {
                term.write(e.key);
                currentObj.studentCommand = currentObj.studentCommand.concat(e.key)
            }
        });

    },
    methods: {
        testCode() {
            let currentObj = this;
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.post(route('testCodeTask', {'course': this.courseId, 'task': this.taskId}), {
                userAnswer: currentObj.studentCommand
            }).then(response => {
                currentObj.testResult = response.data
            }).catch(function (error) {
                console.log(error)
            });
        },

    },
}
</script>
