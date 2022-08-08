<script setup>
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
</script>

<template>
    <form class="w-full sm:max-w-md mt-2 px-2 py-2 bg-white shadow-md overflow-hidden sm:rounded-lg" @submit.prevent="onSubmit">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex text-sm pt-2 pb-1 pl-2 bg-white border-gray-200">
                <input v-model="username" placeholder="Имя" :class="'placeholder:text-slate-400 p-0 pl-1 pr-1 focus:border-indigo-300 focus:ring focus:ring-gray-300 focus:ring-opacity-50 rounded-md shadow-sm text-sm ' + ((validation_errors.username ?? null) ? 'border-red-600' : 'border-gray-300')" type="text">
                <div class="ml-2 text-red-600 text-xs">{{ validation_errors.username ?? '' }}</div>
            </div>
            <div class="text-sm p-2 pb-1 pt-1 bg-white border-gray-200">
                <textarea v-model="message" placeholder="Сообщение" cols="3" rows="3" :class="'placeholder:text-slate-400 h-auto p-2 resize-none w-full text-sm focus:ring-gray-300 rounded-md ' + (validation_errors.message ? 'border-red-600 ' : 'border-gray-300')" name="text"></textarea>
            </div>
            <div class="flex justify-between text-xs text-right pt-0 pr-2 bg-white border-gray-200">
                <div class="text-left ml-2 text-red-600 text-xs">{{ validation_errors.message ?? '' }}</div>
                <Button type="submit" v-on:click="sendTwit">
                    Отправить
                </Button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "new_message",
    props: {
        category_id: {
            type: Number,
            default: null,
        },
        processing: {
            type: Boolean,
            default: true,
        },
    },
    data: () => ({
        username: '',
        message: '',
        validation_errors: {},
    }),
    methods: {
        async sendTwit() {
            if(!this.category_id) {
                alert('Должна быть выбрана категория')
                return
            }

            this.validation_errors = {};

            if (!this.username) {
                this.validation_errors.username = 'Укажите имя.';
            }
            if (!this.message) {
                this.validation_errors.message  = 'Сообщение не должно быть пустым.';
            } else if (this.message.length < 3) {
                this.validation_errors.message = 'Сообщение должно составлять не меньше 3 символов.';
            }

            if (Object.keys(this.validation_errors).length) {
                return;
            }

            await axios.post('http://localhost:'+import.meta.env.VITE_WEBSERVER_EXTERNAL_PORT+'/api/twit', {
                category_id: this.category_id,
                username: this.username,
                message: this.message
            }).then(({data})=>{

            }).catch(({response})=>{
                if(response.status===422){
                    alert(response.data.message)
                }else{
                    this.validationErrors = {}
                    alert(response.data.message)
                }
            }).finally(()=>{

            })
        },

        onSubmit() {

        }
    }
}

</script>
