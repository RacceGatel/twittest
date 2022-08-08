<script setup>
import { ref } from 'vue';
import MainLayout from '@/Layouts/Main.vue';
import { Head } from '@inertiajs/inertia-vue3';
import BreezeDropdown from '@/Components/Dropdown.vue';
import Message from '@/Components/Message.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';
import NewMessage from "@/Components/NewMessage.vue";


const showingNavigationDropdown = ref(false);
</script>

<template>
    <Head title="Твиты" />

    <MainLayout>
        <div class="flex justify-center mt-5">
            <BreezeDropdown align="left" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{  getCurCat() ?? "Категории" }}
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out" v-for="category in categories" as="button" v-on:click="changeCategory(category.id)">
                        {{ category.title }}
                    </button>
                </template>
            </BreezeDropdown>
        </div>

    <NewMessage :category_id="category_id" :processing="processing"></NewMessage>
        <div class="max-h-96 mt-1 scrollbar-hide w-full flex justify-start overflow-y-scroll no-scrollbar scroll-smooth flex-col items-center">
            <TransitionGroup name="messages">
                <Message v-for="twit in twits" :key="twit.id" :twit="twit"></Message>
            </TransitionGroup>
        </div>
    </MainLayout>
</template>

<style scoped>
.messages-enter-active,
.messages-leave-active {
    transition: all 0.3s ease-in-out;
}
.messages-enter-from,
.messages-leave-to {
    transform: translateX(30px);
    opacity: 0;
}

</style>

<script>
export default {
    name:"home",
    props: {
        categories: Array,
    },
    data(){
        return {
            category_id: null,
            processing: true,
            connection: null,
            twits: [],
        }
    },
    mounted() {

    },
    methods:{
        getCurCat() {
            let cat;
            if(cat = this.categories.find((el) => { return el.id == this.category_id})) {
                return cat.title
            }
            return null;
        },

        changeCategory(id) {
            this.category_id = id;
            this.loadCategory(id);
        },

        loadCategory(id) {
            this.twits = [];

            if(this.connection) {
                this.connection.close();
            }

            this.connection = new WebSocket("ws://localhost:"+import.meta.env.VITE_LARAVELS_LISTEN_PORT+"?category_id="+id);
            this.connection.onerror = ((event) => {
                alert('Не удалось открыть WS соединение');
            });

            this.connection.onopen = ((event) => {
                this.processing = false;
            });

            this.connection.onmessage = ((event) => {
                let jsonable = JSON.parse(event.data);

                if("twits" in jsonable) {
                    this.twits = jsonable.twits
                }

                if("new_twit" in jsonable) {
                    this.twits.unshift(jsonable.new_twit);
                    if(this.twits.length > 10) {
                        this.twits.pop();
                    }
                }
            });

            this.connection.onclose = ((event) => {
                this.processing = true;
            });
        }
    }
}
</script>