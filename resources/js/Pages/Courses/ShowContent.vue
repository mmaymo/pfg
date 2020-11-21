<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <course-sidebar :course="this.course" :teacher="this.teacher" :itinerary="this.itinerary"></course-sidebar>
               <task :itinerary="this.itinerary"></task>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import CourseSidebar from "../../Jetstream/CourseSidebar";
import Task from "../../Jetstream/Task"

export default {
    components: {
        AppLayout,
        Task,
        CourseSidebar
    },
    data() {
        return {
            course: "Nombre del Curso",
            teacher: "Nombre profesor",
            itinerary: [
                {
                    name: "Task name 1",
                    type: "document",
                    chapter: "Nombre del Capítulo",
                    textContent: [
                        {
                            'type': 'markdown',
                            'content': "Te cambio solo para ver powered HTML5 Markdown editor\n" +
                                "  - Type some Markdown on the left\n" +
                                "  - See HTML in the right\n" +
                                "  - Magic\n" +
                                "\n" +
                                "# New Features!\n" +
                                "\n" +
                                "  - Import a HTML file and watch it magically convert to Markdown\n" +
                                "  - Drag and drop images (requires your Dropbox account be linked)\n" +
                                "> The overriding design goal for Markdown's\n" +
                                "> formatting syntax is to make it as readable\n" +
                                "\n![esto es un gif](https://media.giphy.com/media/xT5LMzIK1AdZJ4cYW4/giphy.gif)"
                        },
                        {
                            'type': 'testTask',
                            'content': {
                                user: "Dave",
                                questions: [
                                    {
                                        text: "What is the full form of HTTP?",
                                        responses: [
                                            {text: "Hyper text transfer package"},
                                            {text: "Hyper text transfer protocol", correct: true},
                                            {text: "Hyphenation text test program"},
                                            {text: "None of the above"}
                                        ],
                                        correctAnswerIndex: 1
                                    },
                                    {
                                        text: "HTML document start and end with which tag pairs?",
                                        responses: [
                                            {text: "HTML", correct: true},
                                            {text: "WEB"},
                                            {text: "HEAD"},
                                            {text: "BODY"}
                                        ],
                                        correctAnswerIndex: 0
                                    },
                                    {
                                        text: "Which tag is used to create body text in HTML?",
                                        responses: [
                                            {text: "HEAD"},
                                            {text: "BODY", correct: true},
                                            {text: "TITLE"},
                                            {text: "TEXT"}
                                        ],
                                        correctAnswerIndex: 1
                                    }
                                ]
                            }
                        },
                    ]
                },
                {name: "Task name 2", type: "quiz", chapter: "Nombre del Capítulo"},
                {name: "Task name 3", type: "code", chapter: "Nombre del Capítulo"},
                {name: "Task name 4", type: "card", chapter: "Nombre del Capítulo"},
            ]
        }
    },

}
</script>
