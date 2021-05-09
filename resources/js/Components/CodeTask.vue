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
        <div v-if="testResult" class="flex justify-center">
            <pre> {{ testResult }}</pre>
        </div>

    </div>
</template>

<script>
import JetButton from "../Jetstream/Button";
import 'xterm/css/xterm.css'
import {Terminal} from "xterm";
import {AttachAddon} from 'xterm-addon-attach';

export default {
    components: {
        JetButton,
    },
    props: ['textContent', 'courseId', 'task', 'buffer', 'title', 'textContent'],
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

    mounted() {
        this.getTerminalPid()
    },
    methods: {
        getTerminalPid(){
            console.log('en get terminal pid')
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.post('http://0.0.0.0:8999/terminals', {
            }).then(response => {
                const pid = response.data
                this.startTerminal(pid)
            }).catch(function (error) {
                console.warn(error)
            });
        },
        startTerminal(pid){
            let term = new Terminal(this.options)
            const socket = new WebSocket('ws://0.0.0.0:8999/terminals/'+ pid)
            //TODO pillo el id del user creo su carpeta si no existe y entro en ella
            const user = 2
            let data = `mkdir alumno${user} \n cd alumno${user} \n`
            this.preparationScript(pid, data)
            //TODO pillo data del objeto task code
            data = 'mkdir prueba2 \n'
            this.preparationScript(pid, data)
            const attachAddon = new AttachAddon(socket)
            term.loadAddon(attachAddon)
            term.open(document.getElementById('terminal'))
            term.focus();
            term.writeln('Puede escribir su código a continuación');
            term.prompt = () => {
                term.write('\r\n$ ')
            }
            term.prompt()
        },
        testCode() {
            let currentObj = this;
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.post(route('testCodeTask', {'course': this.courseId, 'task': this.task.id}), {
                userAnswer: currentObj.studentCommand
            }).then(response => {
                currentObj.testResult = response.data
                currentObj.task.isDone = true
            }).catch(function (error) {
                console.warn(error)
            });
        },

        preparationScript(pid, data) {
            axios.post('http://0.0.0.0:8999/terminals/'+ pid+'/data', {
                pid:pid,
                data: data
            }).then(response => {

            }).catch(function (error) {
                console.warn(error)
            });
        }
    },
}
</script>
