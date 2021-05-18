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
            <pre> {{ showResult }}</pre>
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
            pid:'',
            readyToTest:false,
            showResult:"",
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
            let currentObj = this;
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.post('http://0.0.0.0:8999/terminals', {
            }).then(response => {
                currentObj.pid = response.data
                this.startTerminal(response.data)
            }).catch(function (error) {
                console.warn(error)
            });
        },
        startTerminal(pid){
            let term = new Terminal(this.options)
            const socket = new WebSocket('ws://0.0.0.0:8999/terminals/'+ pid)
            const user = this.task.user
            const attachAddon = new AttachAddon(socket)
            term.loadAddon(attachAddon)
            term.open(document.getElementById('terminal'))
            term.focus();
            term.writeln('Puede escribir su código a continuación')
            let data = `cd codetest/${user} \n`
            this.preparationScript(pid, data)
            data = this.task.contents.scriptPrevious
            this.preparationScript(pid, data)
            let currentObj = this;
            socket.onmessage = function(evt){
                currentObj.testResult += evt.data
                currentObj.checkAnswer()
            }
        },
        checkAnswer(){
            let countTrue = (this.testResult.match(/true/g) || []).length;
            let countFalse = (this.testResult.match(/false/g) || []).length;
            if(countTrue === 0 && countFalse === 0){return}
            if(countFalse === 2){
                this.showResult = "La respuesta es incorrecta, puede seguir adelante :)"
                this.addPoints(false)
            }
            if(countTrue === 2){
                this.showResult = "Excelente, puede seguir adelante :)"
                this.addPoints(true)
            }
        },
        testCode() {
            const data = this.task.contents.scriptAfter
            this.testScript(this.pid, data)
        },
        addPoints(passed){
            let currentObj = this;
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.post(route('testCodeTask', {'course': this.courseId, 'task': this.task.id}), {
                userAnswer: passed
            }).then(response => {
                currentObj.task.isDone = true;
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
                console.log(error)
            });
        },
        testScript(pid, data) {
            axios.post('http://0.0.0.0:8999/terminals/'+ pid+'/data', {
                pid:pid,
                data: data
            }).then(response => {
                this.readyToTest = true
            }).catch(function (error) {
                console.log(error)
            });
        },
    },
}
</script>
